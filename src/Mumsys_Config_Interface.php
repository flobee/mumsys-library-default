<?php

/**
 * Mumsys_Config_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2009 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 * @version 2.1.0
 * Created: 2009-11-29
 */


/**
 * Mumsys config class 2.0.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 */
interface Mumsys_Config_Interface
{
    /**
     * Initialize the config object.
     *
     * @param Mumsys_Context $context Context object
     * @param array $config Config parameters to be set
     * @param array $paths List of locations for config files
     */
    public function __construct( Mumsys_Context $context, array $config = array(), array $paths = array() );


    /**
     * Get config parameter/s by given path.
     *
     * @param string|array $key Path to the config to get config value/s from
     * e.g. frontend/pageTitle or array('frontend', 'pageTitle)
     * @param mixed|null $default Expectd value or the default value to return
     * if key does not exists
     *
     * @return array Value/s of the requested key or the default will return.
     */
    public function get( $key, $default = null );


    /**
     * Get all config parameters
     *
     * @return array Config parameters
     */
    public function getAll();


    /**
     * Register a configuration parameter if not exists.
     *
     * @param string $key Path including the key to be registered. E.g.: frontend/pageTitle
     * @param mixed $value Mixed value to be set.
     *
     * @throws Mumsys_Config_Exception If key exists
     */
    public function register( $key, $value = null );


    /**
     * Replace/ sets a configuration parameter.
     *
     * @param string $key Path to the value e.g: frontend/pageTitle
     * @param mixed $value Value to be set
     */
    public function replace( $key, $value = null );
}
