<?php

/**
 * Mumsys_Loader
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2010 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Loader
 * @version     3.1.2
 * 0.4 Created on 28.08.2010
 */


/**
 * Factory/loader for mumsys library classes.
 *
 * Basic class handle: loads the relevant mumsys class if needed
 * eg.: $db = Mumsys_Loader::load("Mumsys_Timer");
 *
 * Note: in your bootstrap the following should be added:
 * <code>
 * require_once  '/path/to/src/Mumsys_Loader.php';
 * spl_autoload_register(array('Mumsys_Loader', 'autoload'));
 * </code>
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Loader
 */
class Mumsys_Loader
{
    /**
     * Version ID information
     */
    const VERSION = '3.1.2';

    /**
     * Container of objects which are loaded.
     *
     * @staticvar array
     */
    protected static $loadedClasses;


    /**
     * Factory for mumsys library
     *
     * @param string $instance Name of the Class to be loaded
     * @param array $args Arguments for the initialisation
     *
     * @return object The class to be instanceiated
     *
     * @throws Mumsys_Exception Throws exception if loading of class file fails.
     */
    public static function load( $instance, array $args = array() )
    {
        try {
            // autoload will be called for "new $instance($args)"
            if ( !class_exists($instance) && !isset(self::$loadedClasses[$instance]) ) {
                $message = sprintf('Could not load: "%1$s".', $instance);
                throw new Mumsys_Loader_Exception($message);
            } else {
                $x = new $instance($args);
            }
        }
        catch ( Exception $e ) {
            throw $e;
        }

        return $x;
    }


    /**
     * Autoload class.
     *
     * @param string $instance Name of the class to be loaded.
     *
     * @return boolean Returns true on success otherwise false
     */
    public static function autoload( $instance )
    {
        $test = false;
        if ( !class_exists($instance) )
        {
            // default lib path
            $path = __DIR__ . '/';
            $classfile = $path . $instance . '.php';

            if ( ($test = file_exists($classfile) ) ) {
                $test = require_once $classfile;
            }

            if ( $test !== false ) {
                self::$loadedClasses[$instance] = $instance;
            }
        }

        return $test;
    }


    /**
     * Get loaded classes.
     *
     * @return array Returns a list of loaded classes
     */
    public static function loadedClassesGet()
    {
        return self::$loadedClasses;
    }

}
