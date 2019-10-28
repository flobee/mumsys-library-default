<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Controller_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2014 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * @version     1.0.0
 * Created: 2014-01-10
 * @filesource
 */
/*}}}*/


/**
 * Mumsys abstract mvc base controller
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
abstract class Mumsys_Mvc_Controller_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Mumsys_Context
     * @var Mumsys_Context
     */
    protected $_context;
    /**
     * All config values from mumsys config
     * @var array
     */
    protected $_configs;

    /**
     * Current stat of program/module controller and action
     * @var string
     */
    protected $_program;
    protected $_controller;
    protected $_action;

    /**
     * Initialise Mvc controller
     *
     * @param Mumsys_Context $context
     */
    public function __construct( Mumsys_Context $context )
    {
        $this->_context = $context;
        /** @todo Timing problem? when are all configs loaded? */
        $this->_configs = $context->getConfig()->getAll();
    }

    /**
     * Returns the controller location by given program and controller
     * variables. If the controller can not by found the default controller
     * will be returned!
     *
     * @param string $program Name of the program
     * @param string $controller Name of the controller
     *
     * @return string Returns the location of the controller file
     */
    public function getControllerLocation( & $program = null, & $controller = null )
    {
        $newProgram = preg_replace( '/ +/i', '_', ucwords( $program ) );
        $newCntrl = $controller;

        if ( !$this->checkControllerLocation( $newProgram, $newCntrl ) ) {
            $newProgram = $this->_configs['defaultProgram'];
            $newCntrl = $this->_configs['defaultController'];
        }

        return $this->_configs['pathPrograms'] . $newProgram . '/' . $newCntrl . 'Controller.php';
    }

    /**
     * Checks if a controller location exists.
     *
     * @param string $program Name of the program
     * @param string $controller Name of the controller (filename without
     * extension)
     * @return boolean Returns true on success of false if the location not exists
     */
    public function checkControllerLocation( & $program = null, & $controller = null )
    {
        if ( preg_match( MUMSYS_REGEX_AZ09X, $program )
            && preg_match( MUMSYS_REGEX_AZ09X, $controller ) ) {
            $file = $this->_configs['pathPrograms'] . $program . '/' . $controller . 'Controller.php';
            if ( file_exists( $file ) ) {
                return true;
            }
        }
        return false;
    }

}
