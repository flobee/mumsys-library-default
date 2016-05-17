<?php

/* {{{ */
/**
 * Mumsys_Session_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 * Created: 2005-01-01, new in 2016-05-17
 */
/* }}} */


/**
 * Default class to deal with the session using php session
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 */
class Mumsys_Session_Default
    extends Mumsys_Session_Abstract
    implements Mumsys_Session_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Representation of the session befor it will be set to $_SESSION
     * @var array
     */
    private $_records = array();

    /**
     * ID of the session of this system.
     * @var string
     */
    private $_id;

    /**
     * Appication secret if you have serveral installations on the same server.
     * @var string
     */
    private $_appSecret;


    /**
     * Initialize the session object.
     *
     * @param string $appSecret Application domain or installation key
     */
    public function __construct( $appSecret = 'mumsys' )
    {
        /**
         * session_cache_limiter('private');
         * http://de2.php.net/manual/en/function.session-cache-limiter.php
         * session_cache_expire(180);
         * echo $cache_expire = session_cache_expire();
         */
        $this->_appSecret = $appSecret;
        $this->_records = array();

        if (($tmp = session_id()) === '') {
            @session_start();
            $tmp = session_id();
        }
        $this->_id = $tmp;

        if (isset($_SESSION[$this->_id])) {
            $this->_records = $_SESSION;
        } else {
            $this->_records = array();
        }

        parent::__construct($this->_records, $this->_id, $appSecret);
    }


    /**
     * Stores session informations to the session
     */
    public function __destruct()
    {
        $_SESSION[$this->_id] = parent::getCurrent();
        session_write_close();
    }


    /**
     * Clears and unsets the current session
     */
    public function clear()
    {
        parent::clear();
        $this->_records = array();
        session_unset();
    }

}