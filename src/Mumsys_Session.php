<?php

/*{{{*/
/**
 * Mumsys_Session
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 * @version     1.0.0
 * Created: 2005-01-01
 * @filesource
 */
/*}}}*/


/**
 * Class to deal with the session
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 */
class Mumsys_Session extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.1';

    /**
     * Representation of the session befor it will be set to $_SESSION
     * @var array
     */
    private $_records;

    /**
     * Session ID.
     * @var string
     */
    private $_id;

    /**
     * Application key the session belongs to.
     * @var string
     */
    private $_appKey;


    /**
     * Initialize the session object.
     *
     * @param string $appkey Application key the session belongs to.
     */
    public function __construct( $appkey = 'mumsys' )
    {
        /**
         * session_cache_limiter('private');
         * http://de2.php.net/manual/en/function.session-cache-limiter.php
         * session_cache_expire(180);
         * echo $cache_expire = session_cache_expire();
         */
        @session_start();
        $this->_id = session_id();
        $this->_appKey = $appkey;

        if ($_SESSION) {
            $this->_records = & $_SESSION;
        } else {
            $this->_records[$this->_id][$appkey] = array();
        }
    }


    /**
     * Stores session informations to the session on exit.
     */
    public function __destruct()
    {
        $_SESSION = $this->_records;
    }


    /**
     * Returns the value of given key.
     *
     * @param string $key value of the key to return to
     * @param scalar $default
     * @return mixed Stored value or $default if key was not set.
     */
    public function get( $key, $default = null )
    {
        if (isset($this->_records[$this->_id][$this->_appKey][$key])) {
            return $this->_records[$this->_id][$this->_appKey][$key];
        }

        return $default;
    }

    /**
     * Returns the current session data based on the current session id and
     * application key.
     *
     * Note: This is befor it will be available in $_SESSION.
     *
     * @return mixed Stored value
     */
    public function getCurrent()
    {
        return $this->_records[$this->_id][$this->_appKey];
    }

    /**
     * Returns the complete active session data.
     *
     * Note: This is befor it will be available in $_SESSION.
     *
     * @param string $key value of the key to return to
     * @return mixed Stored value
     */
    public function getAll()
    {
        return $this->_records;
    }


    /**
     * Register a new session key.
     *
     * Its like register a domain for the application. This is the failsave
     * setter to add new values to the session.
     *
     * @param string $key Name of the key to register.
     * @param mixed $value optional; Value to be stored
     * @throws Mumsys_Session_Exception Throws exception if key already exists
     */
    public function register( $key, $value = null )
    {
        $key = (string)$key;

        if (array_key_exists($key, $this->_records[$this->_id][$this->_appKey])) {
            $message = sprintf('Session key "%1$s" exists', $key);
            throw new Mumsys_Session_Exception($message);
        }

        $this->_records[$this->_id][$this->_appKey][$key] = $value;
    }


    /**
     * Replace/sets the value for the given key.
     *
     * @param string $key Identifier/ session key to set
     * @param mixed $value Value to be stored
     */
    public function replace( $key, $value = null )
    {
        $this->_records[$this->_id][$this->_appKey][$key] = $value;
    }


    /**
     * Returns the session ID.
     *
     * @return string
     */
    public function getID()
    {
        return $this->_id;
    }


    /**
     * Clears and unsets the current session at all.
     */
    public function clear()
    {
        $this->_records = array();
        session_unset();
    }

}
