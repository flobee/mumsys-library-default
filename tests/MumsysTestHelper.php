<?php


class MumsysTestHelper
    extends PHPUnit_Framework_TestCase
{
    /**
     * Config parametes
     * @var array
     */
    private static $_configs;

    /**
     * New default config object
     * @var Mumsys_Config_File
     */
    private static $_config;

    /**
     * Mixed config pararameters container
     * @var array
     */
    private static $_params;


    /**
     * Return the default config object
     */
    public static function getConfig()
    {
        if ( !isset(self::$_config) ) {
            $paths = array(__DIR__ . '/config');
            self::$_config = new Mumsys_Config_File(new Mumsys_Context(), array(), $paths);
        }

        return self::$_config;
    }


    /**
     * Return config parameters.
     *
     * @return array
     */
    public static function getConfigs()
    {
        if ( !isset(self::$_configs) ) {
            self::$_configs = require __DIR__ . '/config/default.php';
        }

        return self::$_configs;
    }


    /**
     * Returns the location of the tests directory.
     *
     * @return string location
     */
    public static function getTestsBaseDir()
    {
        if ( isset(self::$_params['testsBaseDir']) ) {
            return self::$_params['testsBaseDir'];
        } else {
            self::$_params['testsBaseDir'] = __DIR__ . '/';
            return self::$_params['testsBaseDir'];
        }
    }

}
