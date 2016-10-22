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
     * Context object including Mumsys_Config_File object
     * @var Mumsys_Context
     */
    private static $_context;

    /**
     * New default config object
     * @var Mumsys_Config_Interface
     */
    private static $_config;

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
        if ( !isset(self::$_context) ) {
            self::$_context = new Mumsys_Context();

            $paths = array(__DIR__ . '/config');
            $oConfig = new Mumsys_Config_File(self::$_context, array(), $paths);
            self::$_context->registerConfig($oConfig);
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
