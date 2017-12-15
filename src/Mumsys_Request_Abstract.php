<?php

/**
 * Mumsys_Request_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Request
 */


/**
 * Abstract class to get input parameters.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Request
 */
abstract class Mumsys_Request_Abstract
    extends Mumsys_Abstract
    implements Mumsys_Request_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Incomming request parameters
     * @var array
     */
    protected $_input = array();


    /**
     * Constructor to be implemented at the clild class.
     * @param array $options Optional parameters
     */
    abstract function __construct( array $options = array() );


    /**
     * Returns an input parameter by given key.
     *
     * @param string $key Key name to return
     * @param mixed $default Default value to return if the key not exists
     * @return mixed Returns the requested input value of the default
     */
    public function getParam( $key, $default = null )
    {
        $key = (string)$key;
        if (isset($this->_input[$key])) {
            return $this->_input[$key];
        }

        return $default;
    }


    /**
     * Sets an input parameter
     *
     * If value is NULL it will be unset if key exists
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setParam( $key = '', $value = null )
    {
        $key = (string)$key;

        if (($value === null) && isset($this->_input[$key])) {
            unset($this->_input[$key]);
        } elseif ($value !== null) {
            $this->_input[$key] = $value;
        }

        return $this;
    }


    /**
     * Returns all input parameters
     *
     * @return array incomming request valiables (_GET + _POST)
     */
    public function getParams()
    {
        return $this->_input;
    }


    /**
     * Set action parameters en masse; does not overwrite
     *
     * If value is NULL it will be unset if key exists
     *
     * @param array $array List of key/value pairs to set
     * @return self
     */
    public function setParams( array $array )
    {
        $this->_input = $this->_input + (array)$array;

        foreach ($array as $key => $value) {
            if ($value === null) {
                unset($this->_input[$key]);
            }
        }

        return $this;
    }


    /**
     * Unsets/ resets all input parameters.
     *
     * @return self
     */
    public function clearParams()
    {
        $this->_input = array();

        return $this;
    }


    /**
     * Returns an cookie parameter by given key from _COOKIE.
     * If $key is empty it will return the complete _COOKIE array
     *
     * @param string $key Key name to return or null|false for all
     * @param mixed $default Default value to return if key not found
     * @return mixed Returns requested value or the default if the key does not
     * extists
     */
    public function getCookie( $key = null, $default = null )
    {
        if (empty($key)) {
            return $_COOKIE;
        }

        $key = (string)$key;
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return $default;
    }

}