<?php

/* {{{ */
/**
 * Mumsys_Request_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Request
 * @filesource
 */
/* }}} */


/**
 * Request class to get input parameters.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Request
 */
class Mumsys_Request_Default
    extends Mumsys_Request_Abstract
    implements Mumsys_Request_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.0';

    /**
     * Incomming get parameters
     * @var array
     */
    protected $_inputGet = array();

    /**
     * Incomming post parameters
     * @var array
     */
    protected $_inputPost = array();


    /**
     * Initialise the request object using _GET and _POST arrays.
     *
     * After init the global arrays _POST and _GET will be reset!
     *
     * @param array $options Optional initial options e.g.:
     * 'programKey','controllerKey', 'actionKey',
     */
    public function __construct( array $options = array() )
    {
        parent::__construct($options);

        if (isset($_GET) && is_array($_GET)) {
            $this->_input += $_GET;
            $this->_inputGet = $_GET;
        }

        if (isset($_POST) && is_array($_POST)) {
            $this->_input += $_POST;
            $this->_inputPost = $_POST;
        }
    }


    /**
     * Returns a post parameter by requested key.
     * If $key is empty it will return all incoming posts parameters
     *
     * @param string $key Key to get
     * @param mixed $default Value to return if key not exists
     * @return mixed
     */
    public function getInputPost( $key = null, $default = null )
    {
        if (empty($key)) {
            return $this->_inputPost;
        }

        if (isset($this->_inputPost[$key])) {
            return $this->_inputPost[$key];
        } else {
            return $default;
        }
    }


    /**
     * Returns a get parameter by requested key.
     * If $key is empty it will return all incomming get parameters
     *
     * @param string $key Array key to get
     * @param mixed $default Value to return if key not exists
     * @return mixed
     */
    public function getInputGet( $key = null, $default = null )
    {
        if (empty($key)) {
            return $this->_inputGet;
        }
        if (isset($this->_inputGet[$key])) {
            return $this->_inputGet[$key];
        } else {
            return $default;
        }
    }

}