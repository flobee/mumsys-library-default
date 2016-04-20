<?php

/* {{{ */
/**
 * Mumsys_Mvc_Router_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * @filesource
 */
/* }}} */


/**
 * Default router class to map incoming data to internal functionality.
 *
 * In detail: Incomming parameters like program/module, controler/subprogram
 * action/subcalls are the parameters the router will working on in the mvc
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
class Mumsys_Mvc_Router_Default
    extends Mumsys_Mvc_Router_Abstract
    implements Mumsys_Mvc_Router_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * @var Mumsys_Request_Interface
     */
    protected $_request;

    /**
     * Initialise the router object.
     *
     * @param Mumsys_Request_Interface Request interface
     * @param array $options Optional initial options e.g.: 'programKey', 
     * 'controllerKey', 'actionKey' mappings to initialize the object
     */
    public function __construct( Mumsys_Request_Interface $request, array $options = array() )
    {
        $this->_request = $request;
        
        if (isset($options['programKey'])) {
            $this->setProgramKey($options['programKey']);
        }

        if (isset($options['controllerKey'])) {
            $this->setControllerKey($options['controllerKey']);
        }

        if (isset($options['actionKey'])) {
            $this->setActionKey($options['actionKey']);
        }
    }

}