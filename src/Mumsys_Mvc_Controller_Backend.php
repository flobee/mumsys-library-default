<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Controller_Backend
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
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
 * Mumsys base backend controller.
 *
 * Initializes by a program/module controller. Implements checks for controller,
 * config, boxes or settings of file locations and so on
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
class Mumsys_Mvc_Controller_Backend
    extends Mumsys_Mvc_Controller_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the programm controller and run its requested or default
     * action.
     *
     * @param string $program Program/module
     * @param string $controller Program controller
     * @param string $action Program action
     * @throws Mumsys_Exception
     */
    public function initProgram($program, $controller, $action)
    {
        $config = $this->_configs;
        $programSettings = array();
        /**
         * include settings file if exists for a program.
         * if not, please always create a blank file (performance!)
         */
        if ( file_exists($config['pathPrograms'] . $program . '/config/default.php') ) {
            $programSettings = require_once $config['pathPrograms'] . $program . '/settings.php';
            if ( !is_array($programSettings) )
            {
                $this->_context->getPermissions()->trackRequest();
                $msg = 'Warning. inclusion of settings failed or invalid type';
                throw new Mumsys_Mvc_Controller_Exception($msg, Mumsys_Exception::ERRCODE_DEFAULT);
            }
        }

        $programConfig = new Mumsys_Mvc_Program_Config($this->_context, $programSettings);

        $file = $this->getControllerLocation($program, $controller);

        if ( $file ) {
            include_once $file;

            $strControllerObject = 'Mumsys_Program_' . ucfirst($program) . '_' . ucfirst($controller) . '_Controller';
            $controllerAction = ucfirst($action) . 'Action';

            if (!class_exists($strControllerObject, false)) {
                $msg = sprintf('Class "%1$s" not found',$strControllerObject);
                throw new Mumsys_Mvc_Controller_Exception($msg, Mumsys_Exception::ERRCODE_DEFAULT);
            }
            $controllerObject = new $strControllerObject( $this->_context, $programConfig );

            if ( method_exists($controllerObject, $controllerAction) ) {
                $controllerObject->$controllerAction();
            } else {
                $msg = sprintf('Method "%1$s" not found', $controllerAction);
                throw new Mumsys_Mvc_Controller_Exception($msg, Mumsys_Exception::ERRCODE_DEFAULT);
            }

        } else {
            $msg = 'Abort! Unable to load program "' . $program .'" / "'. $controller . '"';
            throw new Mumsys_Mvc_Controller_Exception($msg, Mumsys_Exception::ERRCODE_DEFAULT);
        }

    }


}
