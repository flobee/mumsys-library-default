<?php declare(strict_types=1);

/**
 * Set defaults, init autoload...
 */
chdir( __DIR__ );

error_reporting( E_ALL );
date_default_timezone_set( 'UTC' );
setlocale( LC_ALL, 'POSIX' ); // "C" style

require_once './../src/Mumsys_Loader.php';
spl_autoload_extensions( '.php' );
/** @var callable $callable 4SCA */
$callable = array('Mumsys_Loader', 'autoload');
spl_autoload_register(  ); //v2+
