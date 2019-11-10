<?php declare(strict_types=1);

/**
 * Mumsys_Cache
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Cache
 * Created: 2013-12-10
 */


/**
 * Mumsys tests helper class
 */
class MumsysTestHelper
{
    /**
     * Config parametes
     * @var array
     */
    private static $_configs;

    /**
     * Context object including Mumsys_Config_File object
     * @var Mumsys_Context
     */
    private static $_context;

    /**
     * New default config object
     * @var Mumsys_Config_Interface
     */
//not in use yet?    private static $_config;

    /**
     * Mixed config pararameters container
     * @var array
     */
    private static $_params;


    /**
     * Return the context object needed for the tests including the default config object.
     */
    public static function getContext()
    {
        if ( !isset( self::$_context ) ) {
            self::$_context = new Mumsys_Context_Item();

            $paths = array( __DIR__ . '/config' );
            $oConfig = new Mumsys_Config_File( array(), $paths );
            self::$_context->registerConfig( $oConfig );
        }

        return self::$_context;
    }


    /**
     * Return the config from context object
     */
    public static function getConfig()
    {
        return self::getContext()->getConfig();
    }


    /**
     * Return config parameters.
     *
     * @return array
     */
    public static function getConfigs()
    {
        if ( !isset( self::$_configs ) ) {
            self::$_configs = require __DIR__ . '/config/default.php';
        }

        return self::$_configs;
    }


    /**
     * Returns the location of the tests directory.
     *
     * Note: without a DIRECTORY_SEPARATOR at the end of string
     *
     * @return string location
     */
    public static function getTestsBaseDir()
    {
        if ( isset( self::$_params['testsBaseDir'] ) ) {
            $dir = self::$_params['testsBaseDir'];
        } else {
            self::$_params['testsBaseDir'] = __DIR__;
            $dir = self::$_params['testsBaseDir'];
        }

        return rtrim( $dir, DIRECTORY_SEPARATOR );
    }

}
