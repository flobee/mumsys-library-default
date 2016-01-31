<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys
 * @version     1.0.0
 * Created: 2011-04-11
 * @filesource
 * ----------------------------------------------------------------------------
 */
/*}}}*/


/**
 * Abstract class to extend mumsys with base methodes and features.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys
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
     * Returns a list of class/versionID pairs which are loaded in this moment.
     *
     * @return array Returns a list of class/versionID pairs
     */
    public static function getVersions()
    {
        $list = Mumsys_Loader::loadedClassesGet();
        $versions = array();

        foreach ($list as $class) {
            if (!preg_match('/(exception|interface)/i', $class)) {
                $versions[$class] = $class::VERSION;
            }
        }

        return $versions;
    }

    // getter/setter checks
    
    /**
     * Check given key to be a valid string.
     *
     * @param string $key Key to register
     * @throws Mumsys_Registry_Exception Throws exception if key is not a string
     */
    private function _checkKey( $key )
    {
        if (!is_string($key)) {
            $message = 'Invalid registry key. It\'s not a string';
            throw new Mumsys_Registry_Exception($message);
        }
    }

}
