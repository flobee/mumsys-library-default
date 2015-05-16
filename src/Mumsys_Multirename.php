<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Multirename
 * for MUMSYS Library for Multi User Management System
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright (c) 2015 by Florian Blasel
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Multirename
 * @version     1.2.0
 * Created on 2015-02-28
 * @since       File available since Release 0.1
 * @filesource
 * -----------------------------------------------------------------------
 */
/*}}}*/


/**
 * Class for renaming multiple files
 *
 * @todo optional: Reduce crap index of codecoverage
 * @todo handle errors to stop execution? at the moment all is reported to the log
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Multirename
 */
class Mumsys_Multirename
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.2.0';

    /**
     * Logger to log and output messages.
     * @var Mumsys_Logger_Interface
     */
    public $logger;

    /**
     * Current working settings.
     * @var array
     */
    private $_config;

    /**
     * Filesystem object to execute rename or link/symlink tasks.
     * @var Mumsys_FileSystem
     */
    private $_oFiles;

    /**
     * List of substitutions.
     * @var array
     */
    private $_substitutions;

    /**
     * Collected list of actions to be saved if --history is enabled
     * @var array
     */
    private $_history = array();


    /**
     * Initialise Multirename object.
     *
     * @param array $config Setup parameters. @see getSetup() for more.
     * @param Mumsys_FileSystem $oFiles Filesystem object for the primary
     * execution.
     * @param object $logger Log object to track the work and/ or show the
     * output when using as shell script or cronjob
     */
    public function __construct(array $config = array(), Mumsys_FileSystem $oFiles, Mumsys_Logger_Interface $logger)
    {
        // nothing which belongs to root is allowed at the moment!
        if (!empty($config['allowRoot']) || (php_sapi_name() === 'cli' && in_array('root',$_SERVER))) {
            $message = 'Something which belongs to "root" is forbidden until enough tests are present '
                . 'and this program is no alpha version anymore! Sorry! Use a different user!'.PHP_EOL;
            throw new Mumsys_Multirename_Exception($message);
        }
        unset($config['allowRoot']);

        $this->_pathHome = is_dir($_SERVER['HOME']) ? $_SERVER['HOME'] : '/tmp/';
        $this->_collection = $this->_pathHome . '/.multirename/collection';
        $this->logger = $logger;

        if ( isset($config['loglevel']) ) {
            $this->logger->msglogLevel = (int)$config['loglevel'];
        }

        $this->logger->log('### multirename action init', 7);

        $config = $this->setSetup($config);
        #$this->_config = $config;

        $this->_oFiles = $oFiles;


        $actions = array();

        if ( isset($config['undo']) ) {
            $actions['undo'] = 'undo';
        } else {
            $actions['run'] = 'run';
        }

        if (isset($config['set-config'])) {
            $actions['set-config'] = 'set-config';
        }

        if (isset($config['del-config'])) {
            $actions['del-config'] = 'del-config';
        }

        if (isset($config['show-config'])) {
            $actions['show-config'] = 'show-config';
            unset($actions['run']);
        }

        if (isset($config['version'])) {
            $actions = array('version' => 'version');
        }

        foreach($actions as $action)
        {
            $this->logger->log('Will perform action "' . $action . '" now', 7);

            switch ($action) {
                case 'set-config':
                    $this->setConfig($config['path']);
                    break;

                case 'del-config':
                    $this->delConfig($config['path']);
                    break;

                case 'undo':
                    $this->undo($config['path']);
                    break;

                case 'show-config':
                    $this->showConfig();
                    break;

                case 'version':
                    $this->showVersion();
                    break;

                case 'run':
                default:
                    $this->run();
            }
        }

        $this->logger->log('### multirename action done.', 7);
    }


    /**
     * Initialise incoming config parameters and returns the new configuration.
     *
     * Parameters will be validated, defaults set and prepares it for the usage
     * internally.
     *
     * @param array $config Configuration/ setup parameters. see getSetup() for help/ a complete list!
     *
     * @return array Returns the new, checked configuration.
     * @throws Mumsys_Exception Throws exception on any error happen with the incoming data.
     */
    public function setSetup( array $config=array() )
    {
        if (isset($config['from-config']))
        {
            if (is_dir($config['from-config'] .'/')) {
                $config['path'] =& $config['from-config'];
            } else {
                $message = 'Invalid --from-config <your value> parameter. Path not found';
                throw new Mumsys_Multirename_Exception($message);
            }

            if (!($newconfig=$this->getConfig($config['from-config']) ) ) {
                $message = 'Could not read from-config in path: "' . $config['from-config'] . '"';
                throw new Mumsys_Multirename_Exception($message);
            } else {
                $message = 'loaded config from --from-config "' . $config['from-config'] . '"';
                $this->logger->log($message, 7);
                /* @todo flags cant be reset but new values from cmdline should be accepted */
                foreach($config as $cKey => $cValue) {
                     $newconfig[$cKey] = $cValue;
                }
                $config = $newconfig;
            }
        }

        if ( !isset($config['path']) || !is_dir($config['path']) ) {
            throw new Mumsys_Multirename_Exception('Invalid --path <your value>');
        }

        if ( isset($config['test']) && !is_bool($config['test']) ) {
            throw new Mumsys_Multirename_Exception('Invalid --test value');
        }

        if ( isset($config['keepcopy']) && $config['keepcopy'] == true ) {
            $config['keepcopy'] = true;
        } else {
            $config['keepcopy'] = false;
        }

        if ( isset($config['hidden']) && $config['hidden'] == true ) {
            $config['hidden'] = true;
        } else {
            $config['hidden'] = false;
        }

        if ( isset($config['link']) ) {
            $linkParts = explode(';', $config['link']);
            $config['link'] = $linkParts[0];
            if (isset($linkParts[1])) {
                $config['linkway'] = $linkParts[1];
            }
        }

        if ( isset($config['linkway']) ) {
            if ($config['linkway'] != 'abs') {
                $config['linkway'] = 'rel';
            }
        }

        if ( empty($config['fileextensions']) && !isset($config['undo']) ) {
            throw new Mumsys_Multirename_Exception('Missing --fileextensions "<your value/s>"');
        }

        if (!is_array($config['fileextensions'])) {
            $config['fileextensions'] = explode(';', $config['fileextensions']);
        }

        if (!isset($config['substitutions'])) {
            throw new Mumsys_Multirename_Exception('Missing --substitutions "<your value/s>"');
        }
        $this->_substitutions = $this->_buildSubstitutions($config['substitutions']);

        if ( isset($config['recursive']) && $config['recursive'] === true) {
            $config['recursive'] = true;
        } else {
            $config['recursive'] = false;
        }

        if (!isset($config['sub-paths'])) {
            $config['sub-paths'] = false;
        }

        if (!isset($config['find'])) {
            $config['find'] = false;
        } else {
            $config['find'] = explode(';', $config['find']);
        }

        if (!isset($config['history'])) {
            $config['history'] = false;
        }

        if (empty($config['test']) || $config['test'] !== true) {
            $config['test'] = false;
        }

        $this->_config = $config;

        return $config;
    }


    /**
     * Execute.
     * This method will look for required files and will test, link or rename
     * affected files.
     *
     * @todo Reduce crap index of codecoverage
     */
    public function run()
    {
        $pathAll = $subPathsSubs = array();
        $dirinfo = $this->_getRelevantFiles();
        $this->logger->log('Base-Path: "' . $this->_config['path'] . '"', 7);

        foreach ($dirinfo AS $k => $file)
        {
            $path = $file['path'];

            $extension = $file['ext'];
            $cnt_matches = 0;
            if ($extension == '') {
                $newName = $file['name'];
            } else {
                $newName = $this->_oFiles->nameGet($file['name']);
                $extension = '.' . $extension;
            }

            // generate %path0% ... %pathN% for substitution
            if (!isset($pathAll[ $path ])) {
                $pathAll[ $path ] = $this->_buildPathBreadcrumbs( $path);
            }

            if ($this->_config['sub-paths']) {
                if (!isset($subPathsSubs[$path])) {
                    $substitutions = $this->_substitutePaths($this->_substitutions, $pathAll[ $path ]);
                    $subPathsSubs[$path] = $substitutions;
                } else {
                    $substitutions = $subPathsSubs[$path];
                }
            } else {
                $substitutions = $this->_substitutions;
                foreach ($pathAll[ $path ] as $pKey => $pValue) {
                    $substitutions[$pKey] = $pValue;
                }
            }

            foreach ( $substitutions AS $search => $replace )
            {
                if ((is_array($search) && is_array($replace)) || (is_scalar($search) && is_scalar($replace))) {
                    $newName = str_replace($search, $replace, $newName, $counts);
                    $cnt_matches += $counts;
                } else {
                    /* @todo escape operators? do tests */
                    foreach ($replace AS $regex => $repl) {
                        $newName = preg_replace($regex, $repl, $newName, -1, $counts);
                        $cnt_matches += $counts;
                    }
                }
            }

            $source = $path .'/'. $file['name'];
            $destination = $path .'/'. $newName . $extension;

            if (isset($this->_config['link'])) {
                $txtMode = 'link';
                $mode = 'link';
                if ($this->_config['link'] == 'soft') {
                    $txtMode = 'symlink';
                    $mode = 'symlink';
                }
            } else {
                $txtMode = 'rename';
                $mode = 'rename';
            }

            if ($this->_config['test'] !== true)
            {
                if ($source != $destination)
                {
                    $this->logger->log(
                        'Will '.$txtMode.':' . "\n\t" . $file['name']
                        . ' ...TO: ' . "\n\t" . $newName . $extension, 6);

                    try {
                        if (isset($this->_config['link'])) {
                            $newdest = $this->_oFiles->link(
                                $source,
                                $destination,
                                $this->_config['link'],
                                $this->_config['linkway'],
                                $this->_config['keepcopy']
                            );
                        } else {
                            $newdest = $this->_oFiles->rename($source, $destination, $this->_config['keepcopy']);
                        }

                        if ($newdest != $destination) {
                            $message = 'Target "' . $destination . '" exists. Used "' . $newdest . '"';
                            $this->logger->log($message, 5);
                        }
                        $this->_history[$mode][$source] = $destination = $newdest;

                    } catch (Exception $e) {
                        $message = $txtMode .' failt for: "'.$source.'": ' .$e->getMessage();
                        $this->logger->log($message, 3);
                    }
                }
            } else {
                if ($source != $destination)
                {
                    if (file_exists($destination) || is_link($destination)) {
                        $message = 'Target exists! Will overwriting or copying. '
                            . 'Depens on if --keepcopy is set';
                        $this->logger->warn($message);
                    }

                    $this->logger->log(
                        'Test-mode '.$txtMode.' (found: '. $cnt_matches .' actions):' . PHP_EOL
                        . "\t" . $file['name'] .' ...TO: ' . "\n"
                        . "\t" . $newName . $extension . PHP_EOL, 6);


                } else {
                    $message = 'No ' . $txtMode .', identical for "' . $file['name'] . '" in "'.$path.'"';
                    $this->logger->log($message, 7);
                }
            }
        }

        if (!empty($this->_config['history']) && empty($this->_config['test'])) {
            $this->setActionHistory();
        }

    }


    /**
     * Scan for relevant files, filter and return found matches.
     *
     * @todo Reduce crap index of codecoverage
     *
     * @return array List of properties from Mumsys_FileSystem::getFileDetails
     * including 'ext' for extension.
     */
    private function _getRelevantFiles()
    {
        $dirinfo = $this->_oFiles->scanDirInfo(
            $this->_config['path'], ($this->_config['hidden']?false:true), $this->_config['recursive']);

        foreach ($dirinfo as $key => $file)
        {
            if ($file['type'] == 'file')
            {
                $extension = $this->_oFiles->extGet($file['name']);

                if (in_array('*', $this->_config['fileextensions'])
                    || in_array($extension, $this->_config['fileextensions']))
                {
                    // Check in OR condition: if something of given find list matches: take it
                    if ($this->_config['find']) {
                        $check = false;
                        foreach ($this->_config['find'] as $find) {
                            if (preg_match('/^(regex:)/i', $find)) {
                                $regex = substr($find, 6);
                            } else {
                                $regex = '/' . $find . '/i';
                            }

                            $check = preg_match($regex, $file['file']);
                        }

                        if (!$check) {
                            continue;
                        }
                    }

                    $file['ext'] = $extension;
                    $files[] = $file;
                }
            }
        }

        return $files;
    }


    /**
     * Undo last rename action.
     *
     * @param string $path Action directory where the files where renamed
     * @param string $keepCopy Flag to set to what to do if old file already
     * exists again on undo. On true the existing file will be kept, on false
     * overwriting take affect.
     *
     * @return void
     */
    public function undo( $path, $keepCopy=true)
    {
        $file = $path . '/.multirename/lastactions';
        if (file_exists($file)) {
            $data = file_get_contents($file);
            $history = json_decode($data, true);

            foreach ($history as $mode => $lastActions)
            {
                if ($this->_config['test']) {
                    $mode = $mode.'-Test';
                }

                switch ($mode) {
                    case 'link':
                    case 'symlink':
                        $this->_undoLink($lastActions, $keepCopy);
                        break;

                    case 'rename':
                        $this->_undoRename($lastActions, $keepCopy);
                        break;

                    case 'link-Test':
                    case 'symlink-Test':
                    case 'rename-Test':
                        $this->_undoTest($lastActions, $mode, $keepCopy);
                        break;
                    default:
                        $this->logger->log('Undo failt. Invalid mode weather a link,symlink nor rename action set', 3);
                        break;
                }

                $this->logger->log('Undo (mode: "'.$mode.'") done', 7);
            }

        } else {
            $this->logger->log('Undo failt. No action history found ', 6);
        }

        return;
    }


    /**
     * Undo for the testmode to show what it will do.
     *
     * This is not a real test. It yust shows the files which are effeced.
     *
     * @param array $files List of files (orig=>newvalue) to be re-done
     * @param string $mode Type of the undo mode links symlink, rename to show to the output
     * @param string $keepCopy Flag to set to what to do if old file already
     * exists again on undo. On true the existing file will be kept, on false
     * overwriting take affect.
     */
    private function _undoTest(array $files = array(), $mode = '', $keepCopy=true)
    {
        foreach ($files as $to => $from) { // reverse, wording is now correct for this undo case for $to and $from
            if (preg_match('/link/i', $mode)) {
                $this->logger->log('Undo ' . $mode . ': delete: "' . $from . '"', 6);
            } else {
                $this->logger->log('Undo ' . $mode . ': "' . $from . '" TO: "' . $to . '"', 6);
            }
        }
    }


    /**
     * Undo a rename action.
     *
     * @param string $mode Type of the undo mode links symlink, rename to show to the output
     * @param string $keepCopy Flag to set to what to do if old file already
     * exists again on undo. On true the existing file will be kept, on false
     * overwriting take affect.
     *
     * @throws Mumsys_FileSystem_Exception Throws exception on error eg: source not found
     */
    private function _undoRename($files, $keepCopy=true)
    {
        foreach ($files as $to => $from) // reverse (old to is now from)
        {
            try {
                $newTo = $this->_oFiles->rename($from, $to, $keepCopy);

                $this->logger->log('Undo rename ok for: "' . basename($from) . '"', 7);
                if ($newTo != $to) {
                    $this->logger->log(
                        'Undo rename to "' . $to . '" notice: Already exists!. Used "' . basename($newTo) . '" instead', 5);
                }
            } catch (Mumsys_FileSystem_Exception $e) {
                $message = 'Undo rename failt for "' . $from . '" TO: "' . $to . '". Message: ' . $e->getMessage();
                $this->logger->log($message, 3);
            }
        }
    }


    /**
     * Undo a link/symlink action.
     *
     * @param string $mode Type of the undo mode links symlink, rename to show to the output
     * @param string $keepCopy Flag to set to what to do if old link already
     * exists, again, on undo. On true the existing  will be kept, on false the
     * link will be deleted. Default: false.
     */
    private function _undoLink($files, $keepCopy=false)
    {
        foreach ($files as $to => $from) // reverse (old to is now from)
        {
            if (is_link($from) && !@unlink($from)) {
                $this->logger->log('Deleting the link failt for "' . $from . '" ', 3);
            } else {
                $this->logger->log('Undo link ok for: "' . basename($from) . '"', 7);
            }
        }
    }


    /**
     * Set the actions preformed by a rename/symlink action from a later undo.
     *
     * @return boolean Returns true if the action data could be stored or false on errors
     */
    public function setActionHistory() {

        $this->_mkConfigDir($this->_config['path']);

        $file = $this->_config['path'] . '/.multirename/lastactions';

        $data = json_encode($this->_history);

        $result = file_put_contents($file, $data);

        $this->logger->log(
            'Actions saved. To undo/ reverse use multirename --undo --path "' . $this->_config['path'] . '"', 6);
        $this->logger->log(
            'Undo is possible for this path until the next rename action will be performed/ executed', 6);
        return $result;
    }


    /**
     * Creates a config directory to store a config or action history.
     *
     * @param string $path Action/ start directory for renaming files.
     * @return boolean Returns true on success of false if the config dir could not be created
     */
    private function _mkConfigDir($path)
    {
        $path = $path . '/.multirename/';
        if (!is_dir($path)) {
            if (!@mkdir($path, 0755)) {
                $message = 'Can not create directory "' . $this->_config['path'] . '/.multirename"';
                $this->logger->log($message, 3);
                return false;
            } else {
                $this->_trackConfigDir($path);
            }
        }
/* @todo for testing at the moment and upgrading while implement this feature */
$this->_trackConfigDir($path);
        return true;
    }


    /**
     * Collects action directories.
     * The idea behind: To find where this program was used. Also useful for an uninstall process
     *
     * @param string $path Action/ start directory for renaming files
     */
    private function _trackConfigDir($path)
    {
        $this->logger->log('Will track config directory for collection. Path: "' . $path . '"', 7);
        $data = $this->_getCollection();
        $this->_setCollection($data, $path);
    }


    /**
     * Returns a list of action/ start directories.
     *
     * @return array List of start directories
     */
    private function _getCollection()
    {
        $colldata = array();
        if (file_exists($this->_collection)) {
            $data = file_get_contents($this->_collection);
            $colldata = json_decode($data, true);
        }

        return $colldata;
    }


    /**
     * Adds a collection record and saves all collection data.
     *
     * @param array $data List of records of the collection.
     * @param string $path Directory to be added to the collection
     *
     * @return boolean Returns true on success or false on failure
     */
    private function _setCollection(array $data, $path = false)
    {
        $data[ md5($path) ] = str_replace('//', '/', $path);
        asort($data);
        $jdata = json_encode($data);
        return file_put_contents($this->_collection, $jdata);

        return true;
    }


    /**
     * Returns the configuration from the given start directory.
     *
     * @param string $path Action/ start directory for renaming files
     * @param integer|string $configID Config ID to return
     *
     * @return array|false Returns the configuration values or false if no configuration exists.
     */
    public function getConfig( $path = '', $configID = '_' )
    {
        $file = $path . '/.multirename/config';
        if (file_exists($file)) {
            $data = file_get_contents($file);
            $allconfig = json_decode($data, true);

            return $allconfig[$configID]['config'];
        }

        return false;
    }


    /**
     * Saves/ sets the current configuration.
     *
     * Note: configID: '_' will be used for batch processing (When --batch flag
     * is set, currently the only implementation)
     * configID: 0 will be used as default and will be replace and used as
     * latest config if no other ID is given.
     *
     * @param string $path Action/ start directory for renaming files
     * @param integer|string $configID Config ID to set. Optional, for the future.
     * @return integer|false Returns number of bytes written or false on error
     */
    public function setConfig( $path, $configID = '_' )
    {
        if (!$this->_mkConfigDir($path)) {
            return false;
        }

        $file = $path . '/.multirename/config';

        $config = $this->_config;
        unset($config['test'], $config['set-config'], $config['from-config'], $config['loglevel']);

        $config = array(
            $configID => array(
                'name' => 'config or preset name',
                'date' => date('Y-m-d H:i:s', time()),
                'config' => $config,
            ),
        );

        $data = json_encode($config);

        $result = file_put_contents($file, $data);
        $this->logger->log('Set config done', 6);
        return $result;
    }


    /**
     * Removes/ purges the complete configuration file.
     *
     * @param string $path Action/ start directory for renaming files
     * @return boolean Returns true on success or false
     */
    public function delConfig( $path = '' )
    {
        $file = $path . '/.multirename/config';
        if ($path && file_exists($file)) {
            if (@unlink($file)) {
                $this->logger->log('Config deleted', 6);
                return true;
            } else {
                $this->logger->log('Could not delete config', 3);
            }
        } else {
            $this->logger->log('Config not found', 5);
        }

        return false;
    }


    /**
     * Shows the current loaded configuration for the cmd line.
     * Note: This will push the informations to the logger! Enable loglevel 6 if changed!
     */
    public function showConfig()
    {
        $this->logger->log('Show config:', 6);

        $all = '';
        foreach ($this->_config as $key => $value) {
            if (is_int($key)) {
                continue;
            }

            $msg = '';
            if ($value) {
                $msg .= ' --' . $key . ' ';
            }
            if (!is_bool($value)) {
                if (is_array($value)) {
                    $msg .= '\'' . implode(';', $value) .'\'';
                } else {
                    $msg .= "'" . $value . "'";
                }
            }
            if ($msg) {
                $this->logger->log($msg, 6);
            }
            $all .= $msg;
        }
        $this->logger->log('Cmd line:'. PHP_EOL .'multirename' . $all, 6);
    }


    /**
     * Create path-breadcrumbs.
     *
     * This will generate %path0% ... %pathN% as array keys and the part of the
     * path as value. %path0% will be incoming path, %path1 .. %pathN% in reverse
     * order.
     * Example: /var/files/records
     * array(
     *  '%path0%'=> '/var/files/records',
     *  '%path1%' = 'records',
     *  '%path2%' = 'files',
     *  '%path3%' = 'var'
     * );
     *
     * @param string $path Path of the current file
     * @return array List of path-breadcrumbs of the current file.
     */
    private function _buildPathBreadcrumbs( $path = '' )
    {
        $pathAll = array('%path0%' => $this->_config['path']);
        $pathTmp = explode('/', $path);

        $j = 1;
        for ($i = count($pathTmp) - 1; $i > 0; $i--) {
            if ($pathTmp[$i]) {
                $pathAll['%path' . $j . '%'] = $pathTmp[$i];
                $j++;
            }
        }

        return $pathAll;
    }


    /**
     * Build/ prepare the basic substitutions from incoming substitution string.
     *
     * When enabling "--sub-paths" every string, and also regular expressions will
     * be checked and substituted in a second step (see _substitutePaths()). Then
     * things like this will be done: regex:/(%path2%)$/i=%path1%_$1. Be careful
     * using this extra feature! Always use --test and read every test result!
     *
     * @param string $substitutions Semicolon separated list with key value pairs
     * for substitution eg: 'ä=ae;ß=ss;regex:/^(\d{5})$/i=%path1%_number_$1'
     *
     * @return array
     */
    private function _buildSubstitutions( $substitutions = '' )
    {
        $subs = explode(';', $substitutions);
        $result = array();
        foreach ($subs as $expr)
        {
            $keyVal = explode('=', $expr);
            if (!isset($keyVal[1])) {
                $keyVal[1] = '';
            }

            if (preg_match('/^(regex:)/i', $keyVal[0])) {
                $result[] = array(substr($keyVal[0], 6) => $keyVal[1]);
            } else {
                $result[$keyVal[0]] = $keyVal[1];
            }
        }

        return $result;
    }


    /**
     * Replace path informations in substitution configuration
     *
     * @param array $paths List of path-breadcrumbs of the current working file for substitution.
     * @return array Returns the compiled list of substitution to substitude
     */
    private function _substitutePaths( array $substitutions = array(), array $paths = array() )
    {
        foreach ($substitutions AS $search => &$replace)
        {
            if (is_numeric($search) && is_array($replace))
            {
                foreach ($replace as $key => &$value)
                {
                    foreach ($paths as $pk => &$pv)
                    {
                        if (($newValue = str_replace($pk, $pv, $key)) != $key) {
                            $substitutions[$search][$newValue] = $value;
                            unset($substitutions[$search][$key]);
                        }

                        if (($newValue = str_replace($pk, $pv, $value)) != $value) {
                            $substitutions[$search][$key] = $newValue;
                        }
                    }
                }
            } else {
                foreach ($paths as $pk => $pv)
                {
                    $newValue = str_replace($pk, $pv, $search);
                    if ($newValue != $search) {
                        $substitutions[$newValue] = $replace;
                        unset($substitutions[$search]);
                    }

                    $newValue = str_replace($pk, $pv, $replace);
                    if ($newValue != $replace) {
                        $substitutions[$search] = $newValue;
                    }
                }
            }
        }
        return $substitutions;
    }


    /**
     * Install multirename.
     *
     * @todo complete it, more tests of returen values, do tests
     *
     * This will create some hidden paths and files for a better usage later on
     * and will init the collection of working directories for an overview of a
     * possible remove of the program. Data/ history created by the program can
     * be savely removed.
     */
    public function install()
    {
        try {
            $path = $this->_pathHome . '/.multirename/';
            $this->_oFiles->mkdirs($path, 0755);
            $this->_trackConfigDir($path);
        } catch (Exception $err) {
            $this->logger->log('Install failure! Reason: "' . $err->getMessage() . '"');
            throw $err;
        }
    }


    /**
     * Returns all input options for the construction.
     *
     * When using other GetOpt than the current one this is probably the configuration
     * your are looking for.
     * Note: When using your own GetOpt program: The long input values will be used
     * and are required!!! Short options map to the long version.
     *
     * @param boolean $shellOptions Returns shell options if true or array list of
     * input parameters and their description for the construction.
     *
     * @return array Returns a list of all input options which can be set.
     */
    public static function getSetup( $shellOptions = true )
    {
        $result = array(
            '--path|-p:' => 'Path to scann for files (tailing slash is important!) * Required',
            '--fileextensions|-e:' => 'Semicolon separated list of file extensions to scan for '
                . 'eg. "avi;AVI;mpg;MPG" or "*" (with quotes) for all files * Required',
            '--substitutions|-s:' => 'Semicolon separated list with key value pairs for substitution eg:'
                . ' --substitutions ä=ae;ö=oe;ß=ss; =_;\'regex:/^(\d{5})$/i=x_\$1\'... .'
                . 'As simple feature you can use %path1%...%pathN% parameters to substitute '
                . 'path informations in substitution values the file belongs to. For more'
                . 'information see --sub-paths but only use --sub-paths if you really need '
                . 'it. It can became strange side effects when enabling it. * Required',
            '--sub-paths' => 'Flag; Enable substitution for paths. Feature for the substitution: '
                . 'Breadcrumbs of the --path can be found/ substituted with %path1% - %pathN% '
                . ' in reverse. If you want to rename files and want to add the folder '
                . 'the file belongs to you can use %path1%. One folder above is %path2% '
                . 'and so on until the given root in --path. Example: /var/files/records '
                . '=> %path1% = records, %path2% = files, %path3% = var; With this option '
                . 'you can also replace %pathN% in keys or values and also in regular expressions'
                . 'Use the --test flag and test and check the results carefully! '
                . 'WARNING: Enabling this feature can change the behavior of existing substitutions '
                . ' in your cmd line!',
            '--find|-f:' => 'Find files. Semicolon seperated list of search keywords or '
                . 'regular expressions (starting with "regex:"). The list will be handled in OR conditons.'
                . 'The keyword checks for matches in any string of the file location (path and filename). Optional',
            '--recursive|-r' => 'Flag, if set read all files under each directory starting from --path recursively',
            '--keepcopy' => 'Flag. If set keep all existing files',
            '--hidden' => 'Include hidden files (dot files)',
            '--link:' => 'Don\'t rename, create symlinks or hardlinks, relativ or absolut to target '
                . '(Values: soft|hard[;rel|abs]). If the second parameter is not given relativ links will be created',
            '--linkway:' => 'Type of the link to be created relative or absolut: ("rel"|"abs"), default: "rel". '
                . 'This will be used internally if you use --link soft;rel the linkway will be extracted from that line',
            '--history|-h' => 'Flag; If set this will enable the history/ for the moment ONLY the last action log with '
                . 'the option to undo it',
            '--batch' => 'Flag; Not implemented yet. Run the job recusiv from given --path as start directory and '
                . 'start renaming. If a new configuration in the sub directories exists is trys to load the '
                . 'configuration for batch-mode and execute it. This enables --recursiv and --history',
            '--undo' => 'Flag; Revers/ undo the last action',
            '--from-config:' => 'Read saved configuration from given path and execute it',
            '--set-config' => 'Flag; Sets, replaces existing, and saves the parameters to a '
                . 'config file in the given path which adds a new folder ".multirename" '
                . 'for later use with --from-config',
            '--del-config' => 'Flag; Deletes the config from given --path',
            '--show-config' => 'Flag; Shows the config parameter from a saved config to check or rebuild it. '
                . 'Use it with --from-config',
            '--test|-t' => 'Flag: test before execute',
            '--loglevel|--ll:' => 'Logging level for the output of messages (0=Emerg ... 7=verbose/debug). '
                . 'For testing use 6 or 7; For cronjob etc. do not use lower than 5 to get important messages',
            '--version|-v' => 'Flag; Return version informations',
        );

        if ($shellOptions !== true) {
            foreach ($result as $key => $value) {
                $key = str_replace(':', '', $key);
                $key = substr($key, 2);
                $pos = strpos($key, '|');
                if ($pos) {
                    $key = substr($key, 0, $pos);
                }
                $res[$key] = $value;
            }
            $result = $res;
        }
        return $result;
    }


    /**
     * Retuns the version of this program.
     *
     * @return string Returns the version string
     */
    public static function getVersionLong()
    {
        $version = self::getVersionShort();
        $versions = parent::getVersions();
        foreach ($versions as $class => $ver) {
            $version .= str_pad($class, 35, ' ', STR_PAD_RIGHT) . " " . $ver . PHP_EOL;
        }
        $version .= PHP_EOL;
        return $version;
    }

    public static function getVersionShort()
    {
        $version = 'multirename %1$s by Florian Blasel' . PHP_EOL . PHP_EOL;
        return sprintf($version, self::VERSION);
    }


    /**
     * Outputs the version information.
     */
    public static function showVersion()
    {
        echo self::getVersionLong();
    }

}