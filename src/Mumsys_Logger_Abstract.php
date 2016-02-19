<?php

/* {{{ */
/**
 *-----------------------------------------------------------------------------
 * Mumsys_Logger_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 * @version     1.0.0
 * 0.1 Created: 2016-02-19
 * @filesource
 * -----------------------------------------------------------------------
 */
/* }}} */


/**
 * Abstract class to generate log messages
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 * @uses Mumsys_File Writer class
 */
abstract class Mumsys_Logger_Abstract
    extends Mumsys_Abstract
    implements Mumsys_Logger_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';


    const EMERG   = 0;  // 0 EMERG 		emerg() 	System is unusable
    const ALERT   = 1;  // 1 ALERT 		alert() 	Immediate action required
    const CRIT    = 2;  // 2 CRIT 		crit() 		Critical conditions
    const ERR     = 3;  // 3 ERR 		err() 		Error conditions
    const WARN    = 4;  // 4 WARNING 	warn()      Warning conditions
    const NOTICE  = 5;  // 5 NOTICE 	notice() 	Normal but significant
    const INFO    = 6;  // 6 INFO       info() 		Informational
    const DEBUG   = 7;  // 7 DEBUG      debug()     Debug-level messages


    /**
     * Flag to also print out log messages directly or not.
     *
     * @var boolean
     */
    protected $_msgEcho = false;

    /**
     * Flag to also return log messages or not.
     *
     * @var boolean
     */
    protected $_msgReturn = true;

    /**
     * Log levels to log
     *
     * Levels higher than $logLevel will be ignored; range 0-7 by default
     *
     * @var integer
     */
    protected $_logLevel = 7;  // log everything to a file

    /**
     * Available log levels.
     * See class constants for more details
     *
     * @var array
     */
    protected $_loglevels;

    /**
     * Level to output logmessages
     * if $msgEcho is true messages lower or with the same value will be printed
     *
     * @var integer
     */
    protected $_msglogLevel = 6; // logmessages that should be displayed

    /**
     * Format for the date/time string in logmessages.
     * @see http://php.net/date function for details.
     *
     * @var string
     */
    protected $_timeFormat = 'Y-m-d H:i:s';

    /**
     * Format of a log message.
     * Default:
     *  1 = dateformat string
     *  2 = username
     *  3 = name of the log level
     *  4 = id of the log level
     *  5 = the message
     *
     * @var string
     */
    protected $_logFormat = '%1$s [%2$s] [%3$s](%4$s) %5$s';

    /**
     * Format of a log message for the output. (When printing log messages directly to stdout)
     * Default:
     *  1 = dateformat string
     *  2 = username
     *  3 = name of the log level
     *  4 = id of the log level
     *  5 = the message
     *
     * @var string
     */
    protected $_logFormatMsg = '';

    /**
     * Flag to enable verbose or not.
     * Verbose mode will print out all log messages to stdout.
     *
     * @var boolean
     */
    protected $_verbose;

    /**
     * Flag to enable debugging or not.
     * Debug mode will print out all log messages.
     *
     * @var boolean Default: true
     */
    protected $_debug;

    /**
     * Username for each log entry
     * This can be useful if several processes will log to the same storage.
     *
     * @var string
     */
    protected $_username;

    /**
     * Linefeed sign to make a new line after a log entry (on files)
     *
     * @var string
     */
    protected $_lf = "\n";

    /**
     * Internal counter.
     *
     * @var integer
     */
    protected $_cnt = 0;

    /**
     * Component to write log messages to e.g. a file or database.
     *
     * @var Mumsys_File_Interface
     */
    protected $_writer;

    /**
     * Config parameter
     *
     * @var array
     */
    protected $_cfg = array();

    /**
     * Initialize the logger object
     *
     * @param array $args Associativ array with additional params
     * - [username] optional otherwise PHP_AUTH_USER will be taken
     * - [lineFormat] optional format of log line;see $_logformat.
     * - [timeFormat] optional format of a timestamp format
     * - [logLevel] integer Optional Number of the loglevel
     *  Default: 7 (debug mode, log all)
     * - [msglogLevel] integer Optional Message log level for messages which
     *  should be printed (if msgEcho=true)
     * - [msgLineFormat] optional Output format which should be printed (if msgEcho=true)
     * - [msgEcho] boolean Optional Echo a log event Default: false
     * - [msgReturn] boolean Optional Return current log event Default: true
     * - [debug] boolean Default: false
     * - [verbose] boolean Default: false
     * - [lf] string Optional Linefeed Default: \n
     * @param Mumsys_Logger_Writer_Interface $writer Wirter intreface to stroe messages
     *
     * @uses Mumsys_File Uses Mumsys_File object for file logging
     */
    public function __construct( array $options = array(), Mumsys_Logger_Writer_Interface $writer = null )
    {
        if ( empty($options['username']) ) {
            if ( isset($_SERVER['PHP_AUTH_USER']) ) {
                $this->username = (string)$_SERVER['PHP_AUTH_USER'];
            } else if ( isset($_SERVER['REMOTE_USER']) ) {
                $this->username = (string)$_SERVER['REMOTE_USER'];
            } else if ( isset($_SERVER['USER']) ) {
                $this->username = (string)$_SERVER['USER'];
            } else if ( isset($_SERVER['LOGNAME']) ) {
                $this->username = (string)$_SERVER['LOGNAME'];
            } else {
                $this->username = 'unknown';
            }
        } else {
            $this->username = $options['username'];
        }

        if ( isset($options['lineFormat']) ) {
            $this->_logFormat = (string)$options['lineFormat'];
            if ( empty($this->_logFormat) ) {
                throw new Mumsys_Logger_Exception('Log format empty');
            }
        }

        if ( isset($options['timeFormat']) ) {
            $this->_timeFormat = (string) $options['timeFormat'];
        }

        if ( isset($options['logLevel']) ) {
            $this->_logLevel = $options['logLevel'];
        }

        if ( isset($options['msglogLevel']) ) {
            $this->_msglogLevel = $options['msglogLevel'];
        }

        if ( isset($options['msgLineFormat']) ) {
            $this->_logFormatMsg = (string)$options['msgLineFormat'];
        }

        if ( isset($options['msgEcho']) ) {
            $this->_msgEcho = $options['msgEcho'];
        }

        if ( isset($options['msgReturn']) ) {
            $this->_msgReturn = $options['msgReturn'];
        }

        if ( isset($options['debug']) ) {
            $this->_debug = $options['debug'];
        }

        if ( isset($options['verbose']) ) {
            $this->_verbose = $options['verbose'];
        }

        if ( isset($options['lf']) ) {
            $this->_lf = $options['lf'];
        }

        $this->_writer = $writer;

        $r = new ReflectionClass($this);
        $this->_loglevels = array_flip( $r->getConstants() );
    }


    /**
     * Create a log entry by a given log level.

     * 0 EMERG 		emerg() 	System is unusable
     * 1 ALERT 		alert() 	Immediate action required
     * 2 CRIT 		crit() 		Critical conditions
     * 3 ERR 		err() 		Error conditions
     * 4 WARNING 	warn()      Warning conditions
     * 5 NOTICE 	notice() 	Normal but significant
     * 6 INFO       info() 		Informational
     * 7 DEBUG      debug() 	Debug-level messages
     *
     * @param string|array $input Message or list of messages to be logged
     * @param integer $level Level number of log priority
     *
     * @return string|void Returns the log message if needed or nothing
     */
    public function log( $input, $level=0 )
    {
        try
        {
            $isArray = false;
            $datesting = '';
            if ( !empty($this->_timeFormat) ) {
                $datesting = date($this->_timeFormat, time());
            }

            $levelName = $this->_loglevels[$level];

            if ( ($isArray=is_array($input) ) )
            {
                $_cnt = 0;
                $message = '';
                while ( list($key, $val) = each($input) )
                {
                    $tmp = 'ff_' . $_cnt . ': array("' . $key . '" => "' . $val.'");';
                    $message .= sprintf(
                        $this->_logFormat,
                        $datesting,
                        $this->_username,
                        $levelName,
                        $level,
                        $tmp . $this->_lf
                    );
                }

                $_cnt++;
            }
            else
            {
                $message = sprintf($this->_logFormat, $datesting, $this->_username, $levelName, $level, $input);
            }

            $message .= $this->_lf;

            if ( $level <= $this->_logLevel || ($this->_verbose || $this->_debug) )
            {
                $this->write( $message );
            }

            if ( $level <= $this->_msglogLevel || ($this->_verbose || $this->_debug) )
            {
                if ( $this->_msgEcho )
                {
                    if ($this->_logFormatMsg && $this->_logFormatMsg != $this->_logFormat) {
                        if ($isArray)
                        {
                            $msgOut = '';
                            $_cnt = 0;
                            reset($input);
                            while ( list($key, $val) = each($input) )
                            {
                                $tmp = 'ff_' . $_cnt . ': array("' . $key . '" => "' . $val.'");';
                                $msgOut .= sprintf(
                                    $this->_logFormatMsg,
                                    $datesting,
                                    $this->_username,
                                    $levelName,
                                    $level,
                                    $tmp . $this->_lf
                                );
                            }

                            $_cnt++;
                        }
                        else
                        {
                            $msgOut = sprintf(
                                $this->_logFormatMsg,
                                $datesting,
                                $this->_username,
                                $levelName,
                                $level,
                                $input . $this->_lf
                            );
                        }
                    } else {
                        $msgOut = $message;
                    }
                    echo $msgOut;
                }

                if ( $this->_msgReturn ) {
                    return $message;
                }
            }

        } catch (Exception $e) {
            throw $e;
        }

        return;
    }


    /**
     * Write given content to the writer
     *
     * @param string $content String to save to the log writer
     * @return true Returns true on success.
     */
    public function write( $content )
    {
        try {
            $this->_writer->write($content);
        } catch (Exception $e) {
            throw $e;
        }

        return true;
    }


    /**
     * Get the name of a loglevel.
     *
     * @param integer $level Nuber of the Log level
     * @return string Returns the string of the errorlevel
     */
    public function levelNameGet( $level )
    {
        if ( !isset($this->_loglevels[$level]) ) {
            return 'unknown';
        }
        return $this->_loglevels[$level];
    }


    /**
     * Create an emergency log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function emerg( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::EMERG);
    }


    /**
     * Create an alert log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function alert( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::ALERT);
    }


    /**
     * Create an critical log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function crit( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::CRIT);
    }


    /**
     * Create an error log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function err( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::ERR);
    }


    /**
     * Create a warning log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function warn( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::WARN);
    }


    /**
     * Create a notice log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function notice( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::NOTICE);
    }


    /**
     * Create an information log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function info( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::INFO);
    }


    /**
     * Create a debug log entry.
     * Alias method for log()
     *
     * @param string $message Message to be logged
     * @return string|void Returns the log message if needed or nothing
     */
    public function debug( $message )
    {
        return $this->log($message, Mumsys_Logger_Abstract::DEBUG);
    }

}
