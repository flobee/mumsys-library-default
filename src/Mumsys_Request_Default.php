<?php


/* {{{ */
/**
 * Mumsys_Request_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
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
    const VERSION = '1.0.0';


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
        if (isset($options['programKey'])) {
            $this->setProgramKey($options['programKey']);
        }

        if (isset($options['controllerKey'])) {
            $this->setControllerKey($options['controllerKey']);
        }

        if (isset($options['actionKey'])) {
            $this->setActionKey($options['actionKey']);
        }

        if (isset($_GET) && is_array($_GET)) {
            $this->_input += $_GET;

        }

        if (isset($_POST) && is_array($_POST)) {
            $this->_input += $_POST;
        }

        $_GET = $_POST = array();
    }

}
