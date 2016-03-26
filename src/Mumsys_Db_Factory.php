<?php

/*{{{*/
/**
 * Mumsys_Db_Factory
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2009 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Db
 * @filesource
 */
/*}}}*/


/**
 * Factory for a database object.
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Db
 */
class Mumsys_Db_Factory
{
    /**
     * Version ID information
     */
    const VERSION = '3.5.0';

    /**
     * Returns the database object.
     *
     * Example for construction:
     * <code>
     * $dbOptions = array(
     *     'host' => 'localhost',
     *     'db' => 'database_name',
     *     'username' => 'user',
     *     'password' => 'pass',
     *     'port' => 'port',
     *     'debug' => true
     *     'type' => 'mysql:msqli' // <servertype>:<driver> eg: "mariadb:mysqli"
     * );
     * $dbDriverIface = new Mumsys_Db_Factory( $dbOptions );
     * </code>
     *
     * @param array $options Arguments to pass to the driver and for the current
     * implementation e.g.:
     *  'type' => required; driver "servertype:drivertype" e.g. mysql:mysql, mysql:mysqli,
     * mysql:pdo, postges:default, oracle:default, sqlite3:default
     *  'debug' => boolean, optional, default: false
     *  'throwErrors' => boolean, optional, default: true
     * More common options:see Mumsys_Db_Driver_Abstract::__construct
     *
     * @return Mumsys_Db_Driver_Interface Returns the database driver object
     * @throws Mumsys_Db_Exception On errors
     */
    public static function getInstance(Mumsys_Context $context, array $options )
    {
        try
        {
            $tps = explode(':', $options['type']);
            $cnt = count($tps);

            if ($cnt != 2) {
                throw new Mumsys_Db_Exception('Invalid Db driver. Can not create instance', 1);
            }

            $types = $tps;
            $class = 'Mumsys_Db_Driver_' . ucwords($types[0]) .'_'. ucwords($types[1]);
            $object = new $class($context, $options);
        }
        catch ( Exception $e ) {
            throw new Mumsys_Db_Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $object;
    }

}
