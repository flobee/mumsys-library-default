<?php

/**
 * Mumsys_Context
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2014 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Context
 * Created: 2014-01-08
 */


/**
 * Mumsys context object.
 *
 * Component container to place in needed constructs like MVC or other
 * application structures. This one will be usedfor the mumsys cms system but
 * can be used also for library only tasks. E.g. the tests
 *
 * @todo implement more tests
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Context
 */
class Mumsys_Context
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.4';

    /**
     * Configuration vars in an array container.
     * @var array
     */
    private $_config = array();

    /**
     * Returns the config object.
     *
     * @return Mumsys_Config
     *
     * @throws Mumsys_Exception if config was not set
     */
    public function getConfig()
    {
        return $this->_get('Mumsys_Config');
    }

    /**
     * Register the default config object.
     *
     * @param Mumsys_Config $config
     *
     * @throws Mumsys_Exception if config was already set
     */
    public function registerConfig( Mumsys_Config $config )
    {
        $this->_register('Mumsys_Config', $config);
    }

    /**
     * Returns the permissions object. ACL handling
     *
     * @return Mumsys_Permissions_Interface Returns the Mumsys_Permissions object
     *
     * @throws Mumsys_Exception Throws exception if object was not set
     */
    public function getPermissions()
    {
        return $this->_get('Mumsys_Permissions_Interface');
    }

    /**
     * Sets the default permissions object. ACL handling
     *
     * @param Mumsys_Permissions $permissions
     *
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    public function registerPermissions( Mumsys_Permissions_Interface $permissions )
    {
        $this->_register('Mumsys_Permissions_Interface', $permissions);
    }

    /**
     * Returns the session object.
     *
     * @return Mumsys_Session_Interface Returns the Mumsys_Session object
     *
     * @throws Mumsys_Exception Throws exception if object was not set
     */
    public function getSession()
    {
        return $this->_get('Mumsys_Session_Interface');
    }

    /**
     * Register the default session object.
     *
     * @param Mumsys_Session_Interface $session Session object
     *
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    public function registerSession( Mumsys_Session_Interface $session )
    {
        $this->_register('Mumsys_Session_Interface', $session);
    }

    /**
     * Returns the database object.
     *
     * @return Mumsys_Db_Driver_Interface Returns the database object
     * @throws Mumsys_Exception If database was not set
     */
    public function getDatabase()
    {
        return $this->_get('Mumsys_Db');
    }

    /**
     * Sets the default database object.
     *
     * @param Mumsys_Db_Driver_Interface $db Database object
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    public function registerDatabase( Mumsys_Db_Driver_Interface $db )
    {
        $this->_register('Mumsys_Db', $db);
    }

    /**
     * Replace the database object.
     *
     * @param Mumsys_Db_Driver_Interface $db Database object
     */
    public function replaceDatabase( Mumsys_Db_Driver_Interface $db )
    {
        $this->_replace('Mumsys_Db', $db);
    }

    /**
     * Returns the mumsys controller object.
     *
     * @return Mumsys_Controller_Backend Returns the mumsys backend controller object
     *
     * @throws Mumsys_Exception If controller was not set
     */
    public function getControllerBackend()
    {
        return $this->_get('Mumsys_Controller_Backend');
    }

    /**
     * Sets the default backend controller object.
     *
     * @param Mumsys_Controller_Backend $controller Backend controller object
     *
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    public function registerControllerBackend( Mumsys_Controller_Backend $controller )
    {
        $this->_register('Mumsys_Controller_Backend', $controller);
    }

    /**
     * Returns the display object. The the view! for the set and requested
     * output e.g.: HTML with some needed helper methodes to create the output.
     * This object it available in program context. So a program/module box or
     * navigation is used.
     *
     * @return Mumsys_Mvc_Display_Control_Interface Returns display object
     *
     * @throws Mumsys_Exception If controller was not set
     */
    public function getDisplay()
    {
        return $this->_get('Mumsys_Mvc_Display_Control_Interface');
    }

    /**
     * Sets the display controller object. The view.
     * Note: When calling Mumsys_Display_Factory->load() it will be set automatically!
     *
     * @param Mumsys_Mvc_Display_Control_Interface $display object
     *
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    public function registerDisplay( Mumsys_Mvc_Display_Control_Interface $display )
    {
        $this->_register('Mumsys_Mvc_Display_Control_Interface', $display);
    }

    /**
     * Replaces the display controller object. The view.
     * Note: When calling Mumsys_Display_Factory->load() it will be set automatically!
     *
     * @param Mumsys_Display_Control $display object which will be set in
     * dispaly factory
     */
    public function replaceDisplay( Mumsys_Mvc_Display_Control_Interface $display )
    {
        $this->_replace('Mumsys_Mvc_Display_Control_Interface', $display);
    }

    /**
     * Alias method for getDisplay.
     * @link getDisplay()
     */
    public function getControllerFrontend()
    {
        return $this->_get('Mumsys_Mvc_Display_Control_Interface');
    }

    /**
     * Alias method for registerDisplay.
     * @see registerDisplay()
     */
    public function registerControllerFrontend( Mumsys_Mvc_Display_Control_Interface $controller )
    {
        $this->_register('Mumsys_Mvc_Display_Control_Interface', $controller);
    }

    /**
     * Returns the translation object.
     *
     * @return Mumsys_I18n_Interface Returns display object
     *
     * @throws Mumsys_Exception If object was not set
     */
    public function getTranslation()
    {
        return $this->_get('Mumsys_I18n_Interface');
    }

    /**
     * Sets the translation object.
     *
     * @param Mumsys_I18n_Interface $translate Translation object
     *
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    public function registerTranslation( Mumsys_I18n_Interface $translate )
    {
        $this->_register('Mumsys_I18n_Interface', $translate);
    }

    /**
     * Returns the logger object.
     *
     * @return Mumsys_Logger Returns the logger object
     *
     * @throws Mumsys_Exception If class was not set
     */
    public function getLogger()
    {
        return $this->_get('Mumsys_Logger_Interface');
    }

    /**
     * Sets the logger object.
     *
     * @param $logger $logger Logger object
     * @throws Mumsys_Exception Throws exception if the object was already set
     */
    public function registerLogger( Mumsys_Logger_Interface $logger )
    {
        $this->_register('Mumsys_Logger_Interface', $logger);
    }


    /**
     * Returns a generic, already registered, interface/ object.
     *
     * @param string $interface Name of the Interface the object implements
     * @param mixed $default Default value to return if interface not exists
     *
     * @return object Retuns the requested interface/object
     * @throws Mumsys_Exception Throws exception if the object was not set before and if default was not set
     */
    public function getGeneric( $interface, $default = null )
    {
        try {
            $return = $this->_get($interface);
        }
        catch ( Exception $e ) {
            if ( $default === null ) {
                $message = sprintf('Generic interface "%1$s" not found. Message: "%2$s"', $interface, $e->getMessage());
                throw new Mumsys_Exception($message);
            } else {
                $return = $default;
            }
        }

        return $return;
    }

    /**
     * Registers a new interface/ object.
     *
     * @param string $interface Name of the Interface the object implements
     * @param object $value Object to register
     *
     * @throws Mumsys_Exception Throws exception if the object exists or if
     * incoming type is invalid
     */
    public function registerGeneric( $interface, $value )
    {
        if ( !is_object($value) || !is_a($value, $interface) ) {
            $message = sprintf('Value does not implement the interface "%1$s"', $interface);
            throw new Mumsys_Exception($message);
        }

        $this->_register($interface, $value);
    }

    /**
     * Returns the object by given key.
     *
     * @param string $key Name of the class to reqister
     * @return object Returns the object by given key if it was set
     * @throws Mumsys_Exception Throws exception if the object was not set
     */
    private function _get( $key )
    {
        if ( !isset($this->_config[$key]) ) {
            throw new Mumsys_Exception('"' . $key . '" not set');
        }

        return $this->_config[$key];
    }

    /**
     * Register/ set initially the object by given key.
     *
     * @param string $key Name of the object to register
     * @param object $value The object to register
     * @throws Mumsys_Exception Throws exception if object already set
     */
    private function _register( $key, $value )
    {
        if ( array_key_exists($key, $this->_config) ) {
            throw new Mumsys_Exception('"' . $key . '" already set');
        }

        $this->_config[$key] = $value;
    }

    /**
     * Replaces the object by given key. If the object already exists it will be replaced.
     *
     * @param string $key Name of the object to register
     * @param object $value The object to be register
     * @throws Mumsys_Exception Throws exception if object was already set
     */
    private function _replace( $key, $value )
    {
        $this->_config[$key] = $value;
    }

}
