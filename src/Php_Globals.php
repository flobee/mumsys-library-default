<?php

/**
 * Php_Globals
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Php
 * @subpackage  Globals
 */


/**
 * Nice interface for php's GLOBAL VARIABLES.
 *
 * Wraper for $GLOBALS, $_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION,
 * $_REQUEST and $_ENV and getenv().
 *
 * When ever using the server or env variables and your are bored about testing
 * if an array key exists and/or has a value you may find this class useful to
 * always have a default value if something NOT EXISTS. eg.: When switching to
 * shell, something is not available. This will solve some or more overhead
 * implementing things but brings more memory usage.
 * If you dont really need some of the methodes: don't use them! As long the
 * initialisation of the super globals is not needed you are in a good
 * performace way. With or without this class.
 *
 * @category    Mumsys
 * @package     Php
 * @subpackage  Globals
 */
class Php_Globals
    extends Mumsys_Abstract
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Cache container for uploaded files
     * @var array
     */
    private static $_files;

    /**
     * Remote user to be detected via {@link getRemoteUser()}
     * @var string
     */
    private static $_remoteuser = null;


    /**
     * Returns an eviroment variable in this order: getenv() befor _ENV befor _SERVER.
     *
     * @param string $key ID to check for
     * @param mixed $default Return value
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function getServerVar( $key, $default = null )
    {
        return self::_getEnvVar($key, $default);
    }


    /**
     * Returns an eviroment variable in this order: getenv() befor _ENV befor _SERVER.
     *
     * @param string $key ID to check for
     * @param mixed $default Return value
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function getEnvVar( $key, $default = null )
    {
        return self::_getEnvVar($key, $default);
    }


    /**
     * Returns an eviroment variable in this order: getenv() befor _ENV befor _SERVER.
     *
     * @param string $key ID to check for
     * @param mixed $default Return value
     *
     * @return mixed Value or $default if $key is not set/null
     */
    private static function _getEnvVar( $key, $default = null )
    {
        if ( isset($_SERVER[$key]) ) {
            $default = $_SERVER[$key];
        } elseif ( isset($_ENV[$key]) ) {
            $default = $_ENV[$key];
        } elseif ( ($x = getenv($key) ) ) {
            $default = $x;
        }

        return $default;
    }


    /**
     * Returns a post variable by given key.
     * If $key is NULL it will return all posts parameters
     *
     * @param string $key ID to check for
     * @param mixed $default Default return value if key not exists
     *
     * @return array|mixed Value or $default if $key not exists
     */
    public static function getPostVar( $key = null, $default = null )
    {
        if ( isset($_POST) && $key === null ) {
            return $_POST;
        }

        if ( isset($_POST[$key]) ) {
            $default = $_POST[$key];
        }

        return $default;
    }


    /**
     * Returns a get variable by given key.
     * If $key is NULL it will return all get parameters
     *
     * @param string $key ID to check for
     * @param mixed $default Default return value if key not exists
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function getGetVar( $key = null, $default = null )
    {
        if ( isset($_GET) && $key === null ) {
            return $_GET;
        }

        if ( isset($_GET[$key]) ) {
            $default = $_GET[$key];
        }

        return $default;
    }


    /**
     * Returns a get variable by given key.
     * If $key is NULL it will return all cookie parameters
     *
     * @param string $key ID to check for
     * @param mixed $default Default return value if key not exists
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function getCookieVar( $key = null, $default = null )
    {
        if ( isset($_COOKIE) && $key === null ) {
            return $_COOKIE;
        }

        if ( isset($_COOKIE[$key]) ) {
            $default = $_COOKIE[$key];
        }

        return $default;
    }


    /**
     * Returns a list of uploaded _FILES variables by given key.
     *
     * If $key is NULL it will return all file parameter BUT in a new/
     * normalised way.: E.g:
     * upload "file[]" or "file"
     *  - files[file][0][name] and files[file][1][name] are available and NOT:
     *  - files[file][name][0] and files[file][name][1] (PHP default style)
     *
     * @param string $key ID to check for
     * @param mixed $default Default return value if key not exists
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function getFilesVar( $key = null, $default = null )
    {
        if ( isset($_FILES) ) {
            if ( self::$_files === null ) {
                $newFiles = array();

                foreach ( $_FILES as $index => $file ) {
                    if ( !is_array($file['name']) ) {
                        $newFiles[$index][] = $file;
                        continue;
                    }

                    foreach ( $file['name'] as $idx => $name ) {
                        $newFiles[$index][$idx] = array(
                            'name' => $name,
                            'type' => $file['type'][$idx],
                            'tmp_name' => $file['tmp_name'][$idx],
                            'error' => $file['error'][$idx],
                            'size' => $file['size'][$idx]
                        );
                    }
                }

                self::$_files = $newFiles;
            }

            if ( $key === null ) {
                $default = self::$_files;
            }

            if ( isset(self::$_files[$key]) ) {
                $default = self::$_files[$key];
            }
        }

        return $default;
    }


    /**
     * Returns a global variable if set.
     *
     * If $key is NULL it will return all global parameters
     * This does not check the super global variables like _ENV, _SERVER, _GET,
     *  _POST, _REQUEST, _COOKIE, _FILES ... but they are in as array key
     *
     * @param string $key ID to check for
     * @param mixed $default Return value
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function getGlobalVar( $key = null, $default = null )
    {
        if ( isset($GLOBALS) && $key === null ) {
            return $GLOBALS;
        }
        if ( isset($GLOBALS[$key]) ) {
            $default = $GLOBALS[$key];
        }

        return $default;
    }


    /**
     * Returns a global value and look in the other super globals if the global
     * variable could not be found.
     *
     * Returns a value of the super global variables except the upload files in
     * the following order:
     *      GLOBALS
     *      befor (if cli mode) argv
     *      befor getenv()
     *      befor _ENV
     *      befor _SERVER
     *      befor _SESSION
     *      before _COOKIE
     *      befor _REQUEST:
     *
     * Dont use it until you really need to look for a global variable!
     * Returns a global variable and looks in all super globals.
     *
     * @param string $key ID to check for
     * @param mixed $default Return value if no other can be found
     *
     * @return mixed Value or $default if $key is not set/null
     */
    public static function get( $key, $default = null )
    {
        if ( isset($GLOBALS[$key]) ) {
            return $GLOBALS[$key];
        } elseif ( isset($GLOBALS['_REQUEST'][$key]) ) {
            $return = $GLOBALS['_REQUEST'][$key];
        } elseif ( isset($GLOBALS['_COOKIE'][$key]) ) {
            $return = $GLOBALS['_COOKIE'][$key];
        } elseif ( isset($GLOBALS['_SESSION'][$key]) ) {
            $return = $GLOBALS['_SESSION'][$key];
        } elseif ( PHP_SAPI == 'cli' && isset($_SERVER['argv'][$key]) ) {
            $return = $_SERVER['argv'][$key];
        } else {
            $return = self::_getEnvVar($key, $default);
        }

        return $return;
    }


    /**
     * Returns the remote user.
     *
     * This can be the http autenticated user or the remote user when using
     * apache, a logname from the envoroment or the user from a shell account.
     *
     * @return string remote username
     */
    public static function getRemoteUser()
    {
        if ( self::$_remoteuser !== null ) {
            return self::$_remoteuser;
        }

        if ( isset($_SERVER['PHP_AUTH_USER']) ) {
            self::$_remoteuser = (string) $_SERVER['PHP_AUTH_USER'];
        } else if ( isset($_SERVER['REMOTE_USER']) ) {
            self::$_remoteuser = (string) $_SERVER['REMOTE_USER'];
        } else if ( isset($_SERVER['USER']) ) {
            self::$_remoteuser = (string) $_SERVER['USER'];
        } else if ( isset($_SERVER['LOGNAME']) ) {
            self::$_remoteuser = (string) $_SERVER['LOGNAME'];
        } else {
            self::$_remoteuser = 'unknown';
        }

        return self::$_remoteuser;
    }

}
