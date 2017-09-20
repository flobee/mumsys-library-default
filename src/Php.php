<?php

/* {{{ */
/**
 * Php
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Php
 * Created on 2006-04-30
 */
/* }}} */


/**
 * PHP_VERSION_ID is available as of PHP 5.2.7, if our
 * version is lower than that, then emulate it
 * @see http://us2.php.net/manual/en/function.phpversion.php
 * @see http://us2.php.net/manual/en/reserved.constants.php#reserved.constants.core
 */
if ( !defined('PHP_VERSION_ID') ) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

// PHP_VERSION_ID is defined as a number, where the higher the number
// is, the newer a PHP version is used. It's defined as used in the above
// expression:
//
// $version_id = $major_version * 10000 + $minor_version * 100 + $release_version;
//
// Now with PHP_VERSION_ID we can check for features this PHP version
// may have, this doesn't require to use version_compare() everytime
// you check if the current PHP version may not support a feature.
//
// For example, we may here define the PHP_VERSION_* constants thats
// not available in versions prior to 5.2.7

if ( PHP_VERSION_ID < 50207 ) {
    define('PHP_MAJOR_VERSION', $version[0]);
    define('PHP_MINOR_VERSION', $version[1]);
    define('PHP_RELEASE_VERSION', $version[2]);
}


/** {{{
 * Class for php improvements.
 *
 * Improved or missing functionality you will find here.
 * This comes from old times where functionality not exists but still implemented
 * somewhere.
 * All methodes should be called staticly.
 *
 * Example:
 * <code>
 * <?php
 * $value = Php::float('123');
 * ?>
 * </code>
 *
 * @category    Mumsys
 * @package     Php
 * }}} */
class Php
    extends Mumsys_Php
{

}
