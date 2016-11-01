<?php

/**
 * Mumsys_Logger_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Logger
 * @version     3.1.0
 * Created on 2011/02
 */
/* }}} */


/**
 * Interface for Mumsys_Logger object
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 */
interface Mumsys_Logger_Interface
{
    /**
     * Initialize the logger object
     *
     * @param array $args Associativ array with additional params
     * - [logfile] string Location of the logfile; optional, if not set
     *  logs will be stored to /tmp/ ! Make sure you have access to it.
     * - [way] string Default: fopen "a"
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
     * - [maxfilesize] integer Optional Number of Bytes for the logfile:
     * Default: 0 (no limit)
     * @param Mumsys_Logger_Writer_Interface $writer Writer intreface to store messages
     *
     * @uses Mumsys_File Uses Mumsys_File object for file logging
     */
    public function __construct( array $options = array(), Mumsys_Logger_Writer_Interface $writer = null );

    /**
     * Create a log entry by a given message and log level.
     *
     * Levels are:
     * 0 EMERG    emerg()   System is unusable
     * 1 ALERT    alert()   Immediate action required
     * 2 CRIT     crit()    Critical conditions
     * 3 ERR      err()     Error conditions
     * 4 WARN     warn()    Warn conditions
     * 5 NOTICE   notice()  Normal but significant
     * 6 INFO     info()    Informational
     * 7 DEBUG    debug()   Debug-level messages
     *
     * @param string|array $input Message or list of messages to log
     * @param integer $level Level number of log priority
     *
     * @return string|void Returns the log message if needed
     */
    public function log( $input, $level=0 );
}
