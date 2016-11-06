<?php

/**
 * Mumsys_Logger_Decorator_Messages
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Logger
 */


/**
 * Messages decorator for log messages output to stdout/ shell.
 *
 * You may also use the shell decorator if colors for the shell output is
 * something for you.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Logger
 */
class Mumsys_Logger_Decorator_Messages
    extends Mumsys_Logger_Decorator_Abstract
    implements Mumsys_Logger_Decorator_Interface
{
    /**
     * Version ID information.
     */
    const VERSION = '3.0.0';


    /**
     * Initialize the decorated logger object
     *
     * @param Mumsys_Logger_Interface Logger object to be decorated
     */
    public function __construct( Mumsys_Logger_Interface $object, array $options = array() )
    {
        parent::__construct($object);
    }


    /**
     * Clones the object.
     */
    public function __clone()
    {
        $this->_object = clone $this->_object;
    }


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
    public function log( $input, $level = 0 )
    {
        return $this->_object->log($input, $level);
    }


    /**
     * Returns the decorated object.
     *
     * @return Mumsys_Logger_Interface Config object
     */
    protected function _getObject()
    {
        return $this->_object;
    }

}
