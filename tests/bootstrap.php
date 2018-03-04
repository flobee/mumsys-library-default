<?php

/**
 * Bootstrap for Mumsys Library tests
 */
if ( in_array( 'root', $_SERVER ) ) {
    $mesg = 'Something belongs to root. Use a different user! Security exit.'
        . PHP_EOL;
    exit( $mesg );
}

ini_set( 'include_path', '../src' . PATH_SEPARATOR . get_include_path() );
error_reporting( -1 );
ini_set( 'display_errors', 1 );

date_default_timezone_set( 'Europe/Berlin' );

setlocale( LC_ALL, 'POSIX' ); // "C" style

require_once __DIR__ . '/../src/Mumsys_Loader.php';
spl_autoload_register( array('Mumsys_Loader', 'autoload') );
spl_autoload_extensions( '.php' );

require_once __DIR__ . '/testconstants.php';
require_once __DIR__ . '/MumsysTestHelper.php';
