<?php

/**
 * Bootstrap for Mumsys Library tests
 */

if (in_array('root',$_SERVER)) {
    exit('Something belongs to root. Use a different user!' . PHP_EOL);
}

ini_set('include_path', '../src/' . PATH_SEPARATOR . get_include_path());

date_default_timezone_set('Europe/Berlin');

setlocale(LC_ALL, 'POSIX');// "C" style

require_once  __DIR__ . '/../src/Mumsys_Loader.php';
spl_autoload_register(array('Mumsys_Loader', 'autoload'));

require __DIR__ . '/testconstants.php';

class MumsysTestHelper extends PHPUnit_Framework_TestSuite
{
    private static $_configs;

    private static $_params;


    public static function getConfig()
    {
        if ( !isset(self::$_configs) ) {
            self::$_configs = require __DIR__ . '/testconfig.php';
        }

        return self::$_configs;
    }

    public static function getTestsBaseDir()
    {
        if (isset(self::$_params['testsBaseDir'])) {
            return self::$_params['testsBaseDir'];

        } else {
            self::$_params['testsBaseDir'] = realpath(dirname(__FILE__) .'/');
            return self::$_params['testsBaseDir'];
        }
    }

}