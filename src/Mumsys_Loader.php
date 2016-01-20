<?php

/* {{{ */
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Loader
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2010 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Loader
 * @version     3.1.0
 * 0.4 Created on 28.08.2010
 * @filesource
 * ----------------------------------------------------------------------------
 */
/* }}} */


 /**
  * Factory for mumsys library
  * Base Class handle: loads the relevant class if needed, else save inclusion
  * or code.
  * eg.: $db = Mumsys_Loader::load("database");
  *
  * @todo autoload needs to be checked, do tests!!!
  *
  * @category Mumsys
  * @package Mumsys_Library
  * @subpackage Mumsys_Loader
  */
final class Mumsys_Loader
{
    /**
     * Version ID information
     */
    const VERSION = '3.1.0';

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
    public static function load($instance, array $args = array())
    {
        try {
            // autoload will be called for "new $instance($args)"
            if (!class_exists($instance) && !isset(self::$loadedClasses[$instance])) {
                throw new Mumsys_Loader_Exception(sprintf('Error! could not load: "%1$s".', $instance));
            } else {
                $x = new $instance($args);
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $x;
    }


    /**
     * Autoload class.
     *
     * @param string $instance Name of the class to be loaded.
     *
     * @return boolean Returns true on success or false if class could not be loaded
     */
    public static function autoload($instance)
    {
        $test = true;
        if (!class_exists($instance)) {
            $path = dirname(__FILE__) . '/';

            if (substr($instance, 0, 6) == 'Mumsys') {
                $classfile = $path . $instance . '.php';
            } else {
                $classfile = $path . 'class.' . $instance . '.php';
            }
            
            if (($test = file_exists($classfile))) {
                $test = require_once $classfile;
            }

            if ($test !== false) {
                self::$loadedClasses[$instance] = $instance;
            }
        }

        return $test;
    }


    /**
     * Get instanceiated classes.
     *
     * @return array Returns a list of loaded classes
     */
    public static function loadedClassesGet()
    {
        return self::$loadedClasses;
    }

}
