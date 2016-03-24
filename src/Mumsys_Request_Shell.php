<?php

/* {{{ */
/**
 * Mumsys_Request_Shell
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Request
 * @filesource
 */
/* }}} */


/**
 * Request class to get input parameters from shell input
 *
 * @category    Mumsys_
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Request
 */
class Mumsys_Request_Shell
    extends Mumsys_Request_Abstract
    implements Mumsys_Request_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.1';


    /**
     * Initialise the request class using servers argv array.
     * After init _SERVER[argv] will be reseted.
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

        if (isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
            $this->_input += $_SERVER['argv'];
        }

        $_SERVER['argv'] = array();
    }

}
