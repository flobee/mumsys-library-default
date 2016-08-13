<?php

/**
 * Mumsys_GetOpts
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_GetOpts
 * @version     3.4.0
 * Created: 2011-04-11
 */


/**
 * Class to handle/ pipe shell arguments in php context.
 *
 * Shell arguments will be parsed and an array list of key value pairs will be created.
 * When using long and shot options the longer one will be used and the short
 * one will map to it. Short options always have a single character.
 * Dublicate options can't be handled. First comes first serves will take affect.
 *
 * Flags will be handled as boolean true if set.
 * The un-flag option take affect when: Input args begin with a "--no-" string.
 * E.g. --no-history. It will check if the option --history was set and will
 * unset it like it wasn't set in the cmd line. This is usfule when working with
 * different options. One from a config file and the cmd line adds or replace
 * some options. But this must be handled in your buissness logic. E.g. see
 * Mumsys_Multirename class. The un-flag option will always disable a value
 *
 * @todo global config parameters like "help", "version" or "cron" ?
 * @todo Actions groups must be validated, extend whitelist configuration
 *
 * Example:
 * <code>
 * // Simple usage:
 * // Parameter options: A list of options or a list of key value pairs where the
 * // value contains help/ usage informations.
 * // The colon at the end of an option shows that an input must
 * // follow. Otherwise is will be handled as flag (boolean set or not set) and
 * // if this do not match an error will be thrown.
 * $paramerterOptions = array(
 *  '--cmd:', // No help message or:
 *  '--program:' => 'optional: your help/ usage information as value',
 *  '--pathstart:', => 'Path where your files are'
 *  '--delsource'
 * );
 * // Advanced usage (including several actions like):
 * // e.g.: action1 --param1 val1 --param2 val2 action2 --param1 val1 ...
 * $paramerterOptions = array(
 *  'action1' => array(
 *      '--param1:', // No help message or:
 *      '--param2:' => 'optional: your usage information as array value',
 *  'action2' => array(
 *      '--param1:', => 'Path where your files are',
 *      // ...
 * );
 *
 * // input is optional, when not using it the $_SERVER['argv'] will be used.
 * $input = null;
 * // or:
 * $input = array(
 *      '--program:',
 *      'programvalue',
 *      '--pathstart:',
 *      'pathstartvalue,
 *      '--delsource'
 * );
 *
 * $getOpts = new Mumsys_GetOpts($paramerterOptions, $input);
 * $programOptions = $getOpts->getResult();
 * // it will return like:
 * // $programOptions = array(
 * //      'program'=> 'programvalue',
 * //      'pathstart'=>'pathstartvalue',
 * //      'delsource' => true // as boolean true
 * // In advanced setup it will return something like this:
 * // $programOptions = array(
 * //      'action1' => array(
 * //           'program'=> 'programvalue',
 * //           'pathstart'=>'pathstartvalue',
 * //      'action2' => array( ...
 * </code>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_GetOpts
 */
class Mumsys_GetOpts
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '3.4.0';

    /**
     * Cmd line.
     * @var string
     */
    private $_cmd;

    /**
     * List, whitelist of argument parameters to look for.
     * Note: When using several keys e.g.: -a|--append: the longer one will be
     * used, the short will map to it and: first come, first serves.
     * E.g: /program -a "X" --append "Y" -> --append will be "X", "Y" ignored!
     * @var array
     */
    private $_options;

    /**
     * Mapping for short and long options.
     * @var array
     */
    private $_mapping;

    /**
     * List (key value pairs) of all parameter which are in the whitelist.
     * @var array
     */
    private $_result;

    /**
     * List (key value pairs) of all parameter without - and -- parameter prefixes
     * @var array
     */
    private $_resultCache;

    /**
     * List of argument values (argv)
     * @var array
     */
    private $_argv;

    /**
     * Internal flag to deside if action handling will be activated or not.
     * @var boolean
     */
    private $_hasActions;

    /**
     * Internal flag to deside if data has changed so that the results must be created again.
     * @var boolean
     */
    private $_isModified;


    /**
     * Initialise the object and parse incoming parameters.
     *
     * @todo a value can contain a "-" or '--' or -9
     * @todo Some parameters can be required in combination
     *
     * @param array $configOptions List of configuration parameters to look for
     * @param array $input List of input arguments. Optional, uses default input handling then
     */
    public function __construct( array $configOptions = array(), array $input = null )
    {
        if (empty($configOptions)) {
            $msg = 'Empty options detected. Can not parse shell arguments';
            throw new Mumsys_GetOpts_Exception($msg);
        }

        if (empty($input)) {
            $argv = $_SERVER['argv'];
            $argc = $_SERVER['argc'];
        } else {
            $argv = $input;
            $argc = count($input);
        }

        $this->_argv = $argv;


        $options = $this->_checkOptions($configOptions);

        $this->_mapping = $map = $this->_mapOptions($options);

        $this->_options = $options;
        print_r($argv);

        $argPos = 1; // zero is the called program
        $var = null;
        $return = array();
        $errorMsg = '';
        $unflag = array();


        foreach ($options as $action => $params) {
            while ($argPos < $argc) {
                $arg = $argv[$argPos];

// skip values as they are expected in argPos + 1, if any
                if (isset($arg[0]) && $arg[0] == '-') {
                    if ($arg[1] == '-') {
                        $argTag = '--' . substr($arg, 2, strlen($arg));
                    } else {
                        $argTag = '-' . $arg[1]; // take the short flag
                    }

                    if (isset($map[$action][$argTag])) {
                        $var = $map[$action][$argTag];
                    } else {
// a --no-FLAG' to unset?
                        $test = substr($argTag, 5, strlen($argTag));
                        if (strlen($test) == 1) {
                            $unTag = '-' . $test;
                        } else {
                            $unTag = '--' . $test;
                        }

                        if (isset($map[$action][$unTag])) {
                            $unflag[$action][] = $unTag;
                        } else {
                            $errorMsg .= sprintf(
                                'Option "%1$s" not found in option list/configuration for action "%2$s"' . PHP_EOL,
                                $argTag, $action
                            );
                            $argPos++;
                            continue;
                        }
                    }

// whitelist check
                    foreach ($options[$action] as $_opk => $_opv) {
                        if (is_string($_opk)) {
                            $_opv = $_opk;
                        }

                        if (!isset($return[$action][$var])) {
                            if (strpos($_opv, $arg) !== false) {
                                if (strpos($_opv, ':') !== false) {
                                    if (isset($argv[$argPos + 1]) && isset($argv[$argPos + 1][0]) && $argv[$argPos + 1][0]
                                        != '-') {
                                        $return[$action][$var] = $argv[++$argPos];
                                    } else {
                                        /* @todo value[1] is a "-" ... missing parameter or is it the value ? */
                                        $errorMsg .= sprintf('Missing value for parameter "%1$s" in action "%2$s"' . PHP_EOL,
                                            $var, $action);
                                    }
                                } else {
                                    $return[$action][$var] = true;
                                }

//unset($options[$_opk]);
                            } else {
// ???
                            }
                        } else {
// we got it already: was it req and had a value?
//echo PHP_EOL . 'xx: ';print_r($argv[$argPos]); print_r($argv[++$argPos]) ;
//$argPos+=2;
//break;
                        }
                    }
                } else {
// action / sub program call or flag detected!
//$action = $arg;
//$return[$action] = array();
//throw new Mumsys_GetOpts_Exception('action / sub program call or flag detected' . $arg);
                }

                $argPos++;
            }
        }


        if ($errorMsg) {
            $errorMsg .= PHP_EOL . 'Help: ' . PHP_EOL . $this->getHelp() . PHP_EOL;
            $message = 'Invalid input parameters detected!' . PHP_EOL . $errorMsg;
            throw new Mumsys_GetOpts_Exception($message);
        }

        if ($unflag) {
            foreach ($unflag as $action => $values) {
                foreach ($values as $key => $unTag) {
                    if (isset($return[$action][$unTag])) {
                        $return[$action][$unTag] = false;
                    }
                }
            }
        }

        if (count($return) == 1) {
            $this->_hasActions = false;
        } else {
            $this->_hasActions = true;
        }
//print_r($return);
        $this->_result = $return;
    }


    /**
     *
     * @param array $configOptions
     */
    private function _checkOptions( array $config )
    {
        $key = key($config);

        if (
            ( isset($config[$key]) && isset($config[$key][0]) && $config[$key][0] == '-') || ( $key[0] == '-' && (is_string($config[$key])
            || is_bool($config[$key]) ) )
        ) {
            $return = array('_default_' => $config);
        } else if (isset($config[$key]) && is_integer($config[$key])) {
            $message = 'Invalid input config found' . print_r($config, true);
            throw new Mumsys_GetOpts_Exception($message);
        } else {
            $keys = array_keys($config);
            if (is_string($keys[0]) && $keys[0][0] != '-') {
                $return = $config;
            } else {
                $message = 'Invalid input config found';
                throw new Mumsys_GetOpts_Exception($message);
            }
        }

        return $return;
    }


    /**
     * Returns the list of key/value pairs of the input parameters without
     * "-" and "--" from the cmd line.
     *
     * @return array List of key/value pair from incoming cmd line.
     */
    public function getResult()
    {
        if ($this->_resultCache && !$this->_isModified) {
            return $this->_resultCache;
        } else {
            $result = array();
            foreach ($this->_result as $action => $params) {
                if ($action != '_default_') {
                    $result[$action] = array();
                }
                foreach ($params as $k => $v) {
// drop - and -- from keys
                    if (isset($k[1]) && $k[1] == '-') {
                        $n = 2;
                    } else {
                        $n = 1;
                    }

                    $result[$action][substr($k, $n)] = $v;
                }
            }

            if ($this->_hasActions) {
                $this->_resultCache = $result;
            } else {
                $this->_resultCache = $result['_default_'];
                $this->_hasActions = false;
            }

            return $this->_resultCache;
        }
    }


    /**
     * Returns the validated string of incoming arguments.
     *
     * @return string Argument string
     */
    public function getCmd()
    {
        $cmd = false;
        foreach ($this->_result AS $action => $values) {
            if ($action != '_default_') {
                $cmd .= $action . ' ';
            }

            foreach ($values AS $k => $v) {
                if ($k === 0) {
                    continue;
                }

                if ($v === false || $v === true) {
                    foreach ($this->_options as $opk => $opv) {
                        if (is_string($opk)) {
                            $opv = $opk;
                        }

                        if (preg_match('/(' . $k . ')/', $opv)) {
                            if ($v === false) {
                                $cmd .= '--no' . str_replace('--', '-', $this->_mapping[$k]) . ' ';
                            } else {
                                $cmd .= $k . ' ';
                            }
                        }
                    }
                } else {
                    $cmd .= sprintf('%1$s %2$s ', $k, $v);
                }
            }
        }

        $this->_cmd = trim($cmd);

        return $this->_cmd;
    }


    /**
     * Returns help/ parameter informations by given options on initialisation.
     *
     * @return string Help informations
     */
    public function getHelp()
    {
        $str = '';
        $tab = '';

        foreach ($this->_options AS $action => $values) {
            if ($action != '_default_') {
                $str .= $action . '' . PHP_EOL;
                $tab = "\t";
            }

            foreach ($values AS $k => $v) {
                if (is_string($k)) {
                    $option = $k;
                    $desc = $v;
                } else {
                    $option = $v;
                    $desc = '';
                }

                $needvalue = strpos($option, ':');
                $option = str_replace(':', '', $option);

                if ($needvalue) {
                    $option .= ' <yourValue/s>';
                }

                if ($desc) {
                    $desc = PHP_EOL . "\t" . wordwrap($desc, 76, PHP_EOL . "\t") . PHP_EOL;
                }

                $str .= $tab . $option . $desc . '' . PHP_EOL;
            }
            $str = trim($str);
            return $str;
        }
    }


    /**
     * Return the list of actions and list of key/value pairs right after the
     * parser process at construction time.
     *
     * @return array List key/value pais of the incoming parameters
     */
    public function getRawData()
    {
        return $this->_result;
    }


    /**
     * Returns the raw input.
     *
     * @return array Returns the given input array or _SERVER['argv'] array
     */
    public function getRawInput()
    {
        return $this->_argv;
    }


    /**
     * Free/ cleanup collected, calculated results to generate them new. Given
     * default/ setup data will be still available.
     */
    public function resetResults()
    {
        $this->_resultCache = array();
        $this->_mapping = array();
        $this->_result = array();
    }


    /**
     * Returns the mapping of short and long options.
     *
     * @return array List of key value pairs where the key is the option and the
     * value the target to map to it.
     */
    public function getMapping()
    {
        return $this->_mapping;
    }


    /**
     * Returns the mapping of options if several short and long options exists.
     *
     * @param array $options List of incoming options
     * @return array List of key value pair which is the mapping of options
     */
    private function _mapOptions( array $options = array() )
    {
        $mapping = array();


        foreach ($options as $action => $values) {
            foreach ($values as $opkey => $opValue) {
                if (is_string($opkey)) {
                    $opValue = $opkey;
                }

                $opValue = str_replace(':', '', $opValue);

                $parts = explode('|', $opValue);

//            foreach($parts as $pk => & $pv) {
//                $parts[$pk] = preg_replace('/^(--|-)/', '', $pv, -1);
//            }

                if (isset($parts[1])) {
                    if (strlen($parts[0]) > strlen($parts[1])) {
                        $mapping[$action][$parts[0]] = $parts[0];
                        $mapping[$action][$parts[1]] = $parts[0];
                    } else {
                        $mapping[$action][$parts[0]] = $parts[1];
                        $mapping[$action][$parts[1]] = $parts[1];
                    }
                } else {
                    $mapping[$action][$parts[0]] = $parts[0];
                }
            }
        }

        return $mapping;
    }


    /**
     * Prints the help message.
     */
    public function __toString()
    {
        echo $this->getHelp();
    }

}