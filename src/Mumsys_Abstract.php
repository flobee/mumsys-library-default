<?php

/**
 * Mumsys_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Abstract
 * @version     1.0.0
 */


/**
 * Abstract class to extend mumsys with base methodes and features.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Abstract
 */
abstract class Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '3.0.1';


    /**
     * Retuns the version ID of this program.
     *
     * @return string Returns the version ID string
     */
    public static function getVersionID()
    {
        return static::VERSION;
    }


    /**
     * Retuns the version string of called class.
     *
     * @return string Returns the version string "className versionID"
     */
    public static function getVersion()
    {
        $class = get_called_class();
        $version = '%1$s %2$s';
        return sprintf($version, $class, $class::VERSION);
    }


    /**
     * Returns a list of class/versionID pairs which are loaded at this moment.
     *
     * @return array Returns a list of class/versionID pairs
     */
    public static function getVersions()
    {
        $list = Mumsys_Loader::loadedClassesGet();
        $versions = array();

        foreach ($list as $class) {
            if (!preg_match('/(exception|interface)/i', $class)) {
                if (defined($class.'::VERSION')) {
                    $versions[$class] = $class::VERSION;
                } else {
                    $versions[$class] = '-unknown-';
                }
            }
        }

        return $versions;
    }


    // getter/setter checks


    /**
     * Check given key to be a valid string.
     *
     * @param string $key Key to register
     *
     * @throws Mumsys_Registry_Exception Throws exception if key is not a string
     */
    protected static function _checkKey( $key )
    {
        if (!is_string($key)) {
            $message = 'Invalid initialisation key for a setter.'
                . 'A string is required!';
            throw new Mumsys_Exception($message);
        }
    }

}
