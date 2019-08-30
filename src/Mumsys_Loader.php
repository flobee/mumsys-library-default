<?php

/**
 * Mumsys_Loader
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2010 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Loader
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
 * @category    Mumsys
 * @package     Library
 * @subpackage  Loader
 */
class Mumsys_Loader
{
    /**
     * Version ID information
     */
    const VERSION = '3.2.2';

    /**
     * Container of objects which are loaded.
     *
     * @staticvar array
     */
    protected static $_loadedClasses = array();


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
        try
        {
            if ( !class_exists( $instance ) ) {
                $message = sprintf( 'Could not load: "%1$s".', $instance );
                throw new Mumsys_Loader_Exception( $message );
            } else {
                $x = new $instance( $args );
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
     * @return boolean Returns true on success or false if class could not be
     * loaded
     */
    public static function autoload( $instance )
    {
        $test = false;
        if ( !class_exists( $instance, true ) ) {
            $path = __DIR__ . '/';
            $classfile = $path . $instance . '.php';

            if ( ( $test = file_exists( $classfile ) ) ) {
                $test = require_once $classfile;
            }
        }

        return $test;
    }


    /**
     * Get loaded classes.
     *
     * @return array Returns a list of loaded classes
     */
    public static function loadedClassesGet( $prefix = 'Mumsys_' )
    {
        $classList = get_declared_classes();
        foreach ( $classList as $class ) {
            if ( substr( $class, 0, strlen( $prefix ) ) === $prefix ) {
                self::$_loadedClasses[$class] = $class;
            }
        }

        return self::$_loadedClasses;
    }

}
