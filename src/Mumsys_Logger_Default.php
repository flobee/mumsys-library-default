<?php


/* {{{ */
/**
 * Mumsys_Logger_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 * 0.1 Created: 2016-02-19
 */
/* }}} */


/**
 * Class to generate log messages to a writer mechanism e.g. a logfile
 *
 * @todo psr-3 compatible http://www.php-fig.org/psr/psr-3/
 *
 * Example:
 * <code>
 * $fileopts = array(
 *      'file' => $logfile,
 *      'way' => 'a',
 * );
  $writer = new Mumsys_File($fileopts);
 *
 * $opts = array(
 *     'logLevel'=>7,
 *     'msglogLevel'=>6,
 *     'msgEcho'=>true,
 *     'msgReturn'=>true
 * ),
 * $oLog = new Mumsys_Logger_Default($opts, $writer);
 * </code>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 */
class Mumsys_Logger_Default
    extends Mumsys_Logger_File
    implements Mumsys_Logger_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';


    /**
     * Initialize the default logger object
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
     * @param Mumsys_Logger_Writer_Interface $writer Writer interface to store messages
     */
    public function __construct( array $options = array(), Mumsys_Logger_Writer_Interface $writer = null )
    {
        parent::__construct($options, $writer);
    }

}
