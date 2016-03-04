<?php

/* {{{ */
/**
 *-----------------------------------------------------------------------------
 * Mumsys_Logger
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 * @version     3.1.0
 * 0.1 Created: 2005-01-01
 * @filesource
 * -----------------------------------------------------------------------
 */
/* }}} */


/**
 * DEPRICATED! see Mumsys_Logger_File
 * 
 * Class to generate log messages to a writer mechanism e.g. a logfile
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 * @uses Mumsys_File Writer class
 */
class Mumsys_Logger
    extends Mumsys_Logger_Abstract
    implements Mumsys_Logger_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '3.2.0';

    /**
     * path and filename to the log file.
     * @var string
     */
    protected $_logfile;

    /**
     * Type of ways to log a message. Default is a fopen "a" (add/ append).
     *
     * @var string
     */
    protected $_logway;

    /**
     * Number of bytes for a logfile befor it will be purged to zero lenght
     * zero means no limit.
     * If $_debug or verbose is enabled $_maxfilesize will not take affect.
	 *
     * @var integer
     */
    protected $_maxfilesize = 0;


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
     * - [maxfilesize] integer Optional Number of Bytes for the logfile Default: 0 (no limit)
     *
     * @uses Mumsys_File Uses Mumsys_File object for file logging
     */
    public function __construct( array $options = array(), Mumsys_Logger_Writer_Interface $writer=null )
    {
        parent::__construct($options, $writer);

        if ( empty($options['logfile']) ) {
            $this->_logfile = '/tmp/' . basename(__FILE__) .'_'. date('Y-m-d', time());
        } else {
            $this->_logfile = $options['logfile'];
        }

        if ( empty($options['way']) ) {
            $this->_logway = $options['way'] = 'a';
        } else {
            $this->_logway = (string)$options['way'];
        }

        if (isset($options['maxfilesize'])) {
            $this->_maxfilesize = $options['maxfilesize'];
        }

        if (!$writer) {
            $fileOptions = array(
                'file' => $this->_logfile,
                'way' => $this->_logway
            );
            $writer = new Mumsys_File($fileOptions);
        }

        $this->_writer = $writer;

        $this->log('DEPRECATED USAGE! use Mumsys_Logger_File or see Mumsys_Logger_Default', Mumsys_Logger_Abstract::INFO);

        // maxfilesize feature
        /** @todo to be removed, to set in writer class? */
        $message = $this->checkMaxFilesize();

        if ($message) {
            $this->log($message, Mumsys_Logger_Abstract::INFO);
        }

    }


    /**
     * Return the logfile property, the location of the logfile.
     *
     * @return string Location of the logfile
     */
    public function getLogFile()
    {
        return $this->_logfile;
    }


    /**
     * Checks if the max filesize reached and drops the logfile.
     * If debug or verbose mode is enabled this methode will return false.
     *
     * @return string|false Returns string with information that the log was
     * purged or false.
     */
    public function checkMaxFilesize()
    {
        $message = false;
        if ( $this->_maxfilesize )
        {
            if ( !($this->_verbose || $this->_debug)
                && ($fsize=@filesize($this->_logfile)) > $this->_maxfilesize) {
                unlink($this->_logfile);
                $message = 'Max filesize reached. Log purged now';
            }
        }

        return $message;
    }

}
