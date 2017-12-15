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
        if (isset($_GET) && is_array($_GET)) {
            $this->_inputGet = $_GET;
            $this->_input += $_GET;
        }

        if (isset($_POST) && is_array($_POST)) {
            $this->_inputPost = $_POST;
            $this->_input += $_POST;
        }
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
     * Returns _GET parameters.
     *
     * @return type Copy of the _GET parameters
     */
    public function getInputGet()
    {
        return $this->_inputGet;
    }

}