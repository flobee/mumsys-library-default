<?php

/**
 * Mumsys_Request_Default
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
 * Default request class to get input parameters $_GET, $POST, $_COOKIE.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Request
 */
class Mumsys_Request_Default
    extends Mumsys_Request_Abstract
    implements Mumsys_Request_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * _POST parameters container
     * In case they are cleared or modified
     * @var array
     */
    private $_inputPost = array();

    /**
     * _GET parameters container
     * In case they are cleared or modified
     * @var array
     */
    private $_inputGet = array();


    /**
     * Initialise the request class using _GET and _POST arrays.
     *
     * @param array $options Optional initial options e.g.:
     * 'programKey','controllerKey', 'actionKey',
     */
    public function __construct( array $options = array() )
    {
        $_get = Mumsys_Php_Globals::getGetVar();
        if ( isset($_get) && is_array($_get) ) {
            $this->_inputGet = $_get;
            $this->_input += $_get;
        }

        $_post = Mumsys_Php_Globals::getPostVar();
        if ( isset($_post) && is_array($_post) ) {
            $this->_inputPost = $_post;
            $this->_input += $_post;
        }

        unset($_get, $_post);
    }


    /**
     * Returns _POST parameters.
     *
     * @return array Copy of the _POST parameters
     */
    public function getInputPost()
    {
        return $this->_inputPost;
    }


    /**
     * Sets an input post and standard input parameter.
     *
     * If value is NULL it will be unset if key exists
     *
     * @param string $key Parameter key to set
     * @param mixed $value Parameter value to set
     * @return self
     */
    public function setInputPost( $key, $value )
    {
        $key = (string) $key;

        if ( (null === $value) && isset($this->_inputPost[$key]) ) {
            unset($this->_inputPost[$key]);
            unset($this->_input[$key]);
        } elseif ( null !== $value ) {
            $this->_inputPost[$key] = $value;
            $this->_input[$key] = $value;
        }

        return $this;
    }


    /**
     * Returns _GET parameters.
     *
     * @return array Copy of the _GET parameters on initialisation
     */
    public function getInputGet()
    {
        return $this->_inputGet;
    }


    /**
     * Sets an input get and standard input parameter.
     *
     * If value is NULL it will be unset if key exists
     *
     * @param string $key Parameter key to set
     * @param mixed $value Parameter value to set
     * @return self
     */
    public function setInputGet( $key, $value )
    {
        $key = (string) $key;

        if ( (null === $value) && isset($this->_inputGet[$key]) ) {
            unset($this->_inputGet[$key]);
            unset($this->_input[$key]);
        } elseif ( null !== $value ) {
            $this->_inputGet[$key] = $value;
            $this->_input[$key] = $value;
        }

        return $this;
    }

}
