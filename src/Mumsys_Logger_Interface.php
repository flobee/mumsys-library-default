<?php

/* {{{ */
/**
 * ----------------------------------------------------------------------------
 * MUMSYS 2 Library for Multi User Management Interface
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 * @version     3.1.0
 * Created on 2011/02
 * -----------------------------------------------------------------------
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
     * Create a log entry by a given message and log level.
     *
     * Levels are:
     * 0 EMERG 		emerg() 	System is unusable
     * 1 ALERT 		alert() 	Immediate action required
     * 2 CRIT 		crit() 		Critical conditions
     * 3 ERR 		err() 		Error conditions
     * 4 WARN       warn()      Warn conditions
     * 5 NOTICE 	notice() 	Normal but significant
     * 6 INFO       info() 		Informational
     * 7 DEBUG      debug() 	Debug-level messages
     *
     * @param string|array $input Message or list of messages to log
     * @param integer $level Level number of log priority
     *
     * @return string|void Returns the log message if needed
     */
    public function log( $input, $level=0 );
}
