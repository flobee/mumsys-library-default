<?php

/**
 * Mumsys_Variable_Manager_Factory
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Variable
 * Created: 2006 based on Mumsys_Field, renew 2016
 */


/**
 * Variable item manager factory
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Variable
 */
class Mumsys_Variable_Manager_Factory
{
    /**
     * Version ID information.
     */
    const VERSION = '1.1.1';


    /**
     * Initialises the variable manager by given name.
     *
     * @param string $name Name of the manager to be initialised
     * @param array $config List of key/value configuration pairs containing
     * item properties for the item construction
     * @param array $values List of key/value pairs to set/bind to the item
     * values e.g: the post parameters
     *
     * @return Mumsys_Variable_Manager_Interface|Mumsys_Variable_Manager_Default|
     * Mumsys_Variable_Manager_{name} Manager object
     *
     * @throws Mumsys_Variable_Manager_Exception If name not an alnum type
     */
    public static function createManager( $name = 'Default',
        array $config = array(), array $values = array() )
    {
        if ( ctype_alnum( $name ) ) {
            $class = 'Mumsys_Variable_Manager_' . ucwords( $name );
        } else {
            $message = sprintf( 'Invalid manager name: "%1$s"', $name );
            throw new Mumsys_Variable_Manager_Exception( $message );
        }

        if ( !class_exists( $class ) ) {
            $message = sprintf(
                'Initialisation of "%1$s" failed. Not found/ exists', $class
            );
            throw new Mumsys_Variable_Manager_Exception( $message );
        }

        return new $class( $config, $values );
    }

}
