<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_GetOpts
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_GetOpts
 * @version     3.3.0
 * Created: 2011-04-11
 * @filesource
 */
/*}}}*/


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
 * Mumsys_Multirename class.
 *
 * @todo global config parameters like "help", "version" or "cron" ?
 *
 * Example:
 * <code>
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
 *
 * // input is optional, when not using it $_SERVER['argv'] will be used.
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
 * // $programOptions will return like:
 * $programOptions = array(
 *      'program'=> 'programvalue',
 *      'pathstart'=>'pathstartvalue',
 *      'delsource' => true
 * );
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
    const VERSION = '3.3.1';

    /**
     * Cmd line.
     * @var string
     */
    private $_cmd;

    /**
     * List, whitelist of argument parameters to look for.
     * Note: When using several keys e.g.: -a|--append: the longer one will be
     * used, the short will map to it and: first come, first serves.
     * E.g: /program -a "X" --append "Y" --> -a will be "X", --append ignored!
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
    private $_resultClean;

    /**
     * List of argument values (argv)
     * @var array
     */
    private $_argv;


    /**
     * Initialise the object and parse incoming parameters.
     *
     * @todo Check/verify php-doc
     * @todo Some parameters can be required in combination
     *
     * @param array $options List of argument parameters to look for
     * @param array $input List of input arguments
     */
    public function __construct(array $options=array( ), array $input=null)
    {
        if ( empty($options) ) {
            $msg = 'Empty options detected. Can not parse shell arguments';
            throw new Mumsys_GetOpts_Exception($msg);
        }

        if ( empty($input) ) {
            $argv = $_SERVER['argv'];
            $argc = $_SERVER['argc'];
        } else {
            $argv = $input;
            $argc = count($input);
        }
        $this->_argv = $argv;

        $map = $this->_mapOptions($options);

        $this->_options = $options;

        $argPos = 1; // zero is the called program
        $return = array($argv[0]);
        $errorMsg = '';
        $unflag = array();

        while ( $argPos < $argc )
        {
            $arg = $argv[$argPos];

            // skip values as they are expected in argPos + 1, if any
            if (isset($arg[0]) && $arg[0] == '-' )
            {
                if ( $arg[1] == '-' ) {
                    $argTag = '--' . substr($arg, 2, strlen($arg));
                } else {
                    $argTag = '-' . $arg[1]; // take the short flag
                }

                if (!isset($map[ $argTag ]))
                {
                    // a --no-FLAG' to unset?
                    $test = substr($argTag, 5, strlen($argTag));
                    if (strlen($test)==1) {
                        $unTag = '-' . $test;
                    } else {
                        $unTag = '--' . $test;
                    }

                    if ( isset($map[ $unTag ]) ) {
                        $unflag[] = $unTag;
                    } else {
                        $errorMsg .= sprintf(
                            'Option "%1$s" not found in option list/configuration' . PHP_EOL,
                            $argTag
                        );
                        $argPos++;
                        continue;
                    }
                } else {
                    $var = $map[ $argTag ];
                }

                foreach ( $options as $_opk => $_opv )
                {
                    if ( is_string($_opk) ) {
                        $_opv = $_opk;
                    }

                    if ( !isset($return[$var]) )
                    {
                        if ( strpos($_opv, $arg) !== false )
                        {
                            if ( strpos($_opv, ':') !== false )
                            {
                                if (isset($argv[$argPos + 1])
                                    && isset($argv[$argPos + 1][0])
                                    && $argv[$argPos + 1][0] != '-')
                                {
                                    $return[$var] = $argv[++$argPos];
                                } else {
                                    /*@todo value[1] is a "-" ... missing parameter or is the value ? */

                                    //required not set for: $var
                                    $errorMsg .= sprintf('Missing value for parameter "%1$s"' . PHP_EOL, $var);
                                }
                            } else {
                                $return[$var] = true;
                            }

                            unset($options[$_opk]);
                        }
                    }
                }
            }
            $argPos++;
        }

        foreach ($unflag as $unTag) {
                $return[$unTag] = false;
        }

        $this->_result = $return;

        if ($errorMsg) {
            $errorMsg .= PHP_EOL . 'Help: ' . PHP_EOL . $this->getHelp() . PHP_EOL;
            throw new Mumsys_GetOpts_Exception($errorMsg);
        }
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
     * Returns the list of of key/value pairs of the input parameters without
     * "-" and "--" from cmd line.
     *
     * @return array List of key/value pair from incoming cmd line.
     */
    public function getResult()
    {
        if ($this->_resultClean) {
            return $this->_resultClean;
        } else {
            $result = $this->_result;
            $this->_resultClean[] = array_shift($result);
            // drop - and -- from keys
            foreach ($result as $key => $value)
            {
                if (isset($key[1]) && $key[1] == '-') {
                    $n = 2;
                } else {
                    $n = 1;
                }
                $this->_resultClean[substr($key, $n)] = $value;
            }
            return $this->_resultClean;
        }
    }


    /**
     * Returns the validated string of incoming arguments.
     *
     * @todo can not decide between flag and boolean values, long and short options!
     *
     * @return string Argument string
     */
    public function getCmd()
    {
        $this->_cmd = false;
        foreach ($this->_result AS $k => $v) {
            if ($k===0) {
                continue;
            }
            if ( $v === false || $v === true )
            {
                foreach ( $this->_options as $opk => $opv )
                {
                    if (is_string($opk)) {
                        $opv = $opk;
                    }

                    if ( preg_match('/(' . $k . ')/', $opv) )
                    {
//                        if ( strpos($opv, ':') )
//                        {
//                            if ( $v === false ) {
//                                $this->_cmd .= $k . ' false ';
//                            } elseif ( $v === true ) {
//                                $this->_cmd .= $k . ' true ';
//                            } else {
//                                $this->_cmd .= $k . ' ';
//                            }
//                        } else {
//                            $this->_cmd .= $k . ' ';
//                        }
//                        break;
                        if ($v === false) {
                            $this->_cmd .= '--no' . str_replace('--', '-', $this->_mapping[$k]) . ' ';
                        } else {
                            $this->_cmd .= $k . ' ';
                        }
                    }
                }
            } else {
                $this->_cmd .= sprintf('%1$s %2$s ', $k, $v);
            }
        }
        $this->_cmd = trim($this->_cmd);
        /* debug
        echo '$this->_options:';print_r($this->_options);
        echo '$this->_cmdresults:';print_r($this->_cmdresults);
        echo '$this->_cmd:';print_r($this->_cmd);*/
        return $this->_cmd;
    }


    /**
     * Returns help/ parameter informations by given options from initialisation.
     *
     * @param integer $wordWrap Number of chars to wrap to a new line
     * @param integer $indentComment Character/s to indent the comments (prefix)
     * eg: "\t" or 4 spaces (default). Note that e.g. on the shell a TAB can be
     * shown as 8 spaces.
     *
     * @return string Help informations
     */
    public function getHelp($wordWrap=80, $indentComment="    ")
    {
        $str = '';
        $wrap = $wordWrap - strlen($indentComment);

        foreach ( $this->_options AS $k => $v )
        {
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
                $desc = $indentComment . wordwrap($desc, $wrap, PHP_EOL . $indentComment) . PHP_EOL . PHP_EOL;
            }

            $str .= $option . PHP_EOL . $desc;
        }
        $str = trim($str);
        return $str;
    }


    /**
     * Returns the mapping of options if several short and long options exists.
     *
     * @param array $options List of incoming options
     * @return array List of key value pair which is the mapping of options
     */
    private function _mapOptions(array $options=array())
    {
        $mapping = array();

        foreach ($options as $opkey => $opValue)
        {
            if (is_string($opkey)) {
                $opValue = $opkey;
            }

            $opValue = str_replace(':', '', $opValue);

            $parts = explode('|', $opValue);

//            foreach($parts as $pk => & $pv) {
//                $parts[$pk] = preg_replace('/^(--|-)/', '', $pv, -1);
//            }

            if (isset($parts[1]))
            {
                if (strlen($parts[0]) > strlen($parts[1])) {
                    $mapping[$parts[0]] = $parts[0];
                    $mapping[$parts[1]] = $parts[0];
                } else {
                    $mapping[$parts[0]] = $parts[1];
                    $mapping[$parts[1]] = $parts[1];
                }
            } else {
                $mapping[$parts[0]] = $parts[0];
            }
        }

        $this->_mapping = $mapping;
        return $mapping;
    }

}
