<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_GetterSetter_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_GetterSetter
 * @version     1.0.0
 * Created: 2016-03-19
 * @filesource
 */
/*}}}*/


/**
 * Mumsys getter/setter interface.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_GetterSetter
 */
interface Mumsys_GetterSetter_Interface
{
    /**
     * Replaces/ sets the value to the registry by given key.
     *
     * @param sting $key Key to be set
     * @param mixed $value Value to be set
     * @throws Mumsys_Registry_Exception Throws exception if key is not a string
     */
    public function replace( $key, $value );

    /**
     * Registers the value to the registry by given key.
     *
     * @param sting $key Key to register
     * @param mixed $value Value to be set

     * @throws Mumsys_Registry_Exception Throws exception if key already exists
     */
    public function register( $key, $value );

    /**
     * Returns the value by given key.
     *
     * @param string $key Key which was set
     * @return mixed Returns the value which was set or a default
     */
    public function get( $key, $default = null );

    /**
     * Removes registered entry.
     *
     * @param string $key Key which was set
     *
     * @return boolean True on success or false if the key wasn't found
     */
    public static function remove( $key );

}
