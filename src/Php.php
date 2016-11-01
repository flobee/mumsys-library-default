<?php

/**
 * Php
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Php
 * Created on 2006-04-30
 */


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


/**
 * {{{ Class for php improvements.
 *
 * Improved or missing functionality you will find here. Missing functionality
 * you will find at its best @pear's "Compat" package.
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
{
    /**
     * @var string
     */
    public static $os;

    /**
     * get_magic_quotes_gpc() value PHP < 7
     * @var boolean
     */
    public static $getMagicQuotesGpc;


    /**
     * Initialisation of PHP class
     */
    public function __construct()
    {
        self::$os = strtoupper(substr(PHP_OS, 0, 3));
        self::$getMagicQuotesGpc = false;
    }


    /**
     * Magic getter
     *
     * @param string $key Key to test class property
     * @return mixed Returns the value by given key
     */
    public function __get( $key )
    {
        $return = null;
        switch ( $key )
        {
            case 'os':
                $return = self::$os;
                break;
            case 'get_magic_quotes_gpc':
                $return = false;
                break;
        }

        return $return;
    }

    /**
     * Magic setter
     *
     * Only "get_magic_quotes_gpc" can be set at the moment.
     *
     * @param string $k Key to be set.
     * @param mixed $v Value to be set
     *
     * @throws PHP_Exception If key is not implemented to react on. This will
     * prevent public access to this class
     */
    public function __set( $k, $v )
    {
        switch ( $k )
        {
            case 'get_magic_quotes_gpc':
                self::$getMagicQuotesGpc = boolval($v);
                break;
            default:

                throw new PHP_Exception('__set: "' . $k . '"="' . $v . '" not allowed.');
                break;
        }
    }


    /**
     * php >= 5.3.0
     * public Mixed __call():
     * Re-route all function calls to the PHP-functions
     *
     * Note: Don't use it! if you have a better/ different solution. Performance reasons!
     */
    public static function __callStatic( $function, $arguments )
    {
        return call_user_func_array($function, $arguments);
    }


    /**
     * Re-route php function calls to the PHP-functions.
     * Note: Don't use it! if you have a better/ different solution. Performance reasons!
     *
     * @param string $function Function to call
     * @param mixed $arguments Mixed arguments
     * @return mixed
     */
    public function __call( $function, $arguments )
    {
        return call_user_func_array($function, $arguments);
    }


    // +-- start features ----------------------------------------


    // --- Variable handling Functions -----------------------------------------


    /**
     * Check if a value is an integer
     *
     * @param interger $value Value to be checked
     * @return integer|false Returns the casted interger value or false if value
     * is not a nummeric type
     */
    public static function is_int( $value )
    {
        return (is_numeric($value) ? intval($value) == $value : false);
    }


    /**
     * Cast to float and check if a given value is a float value.
     * If the value contains colons or dot's the given value will be cleand to
     * be a tehnical value.
     * Note: Behavior belongs to setlocale().
     *
     * @see setlocale()
     *
     * @param scalar $value
     * @return boolean True on success or false
     */
    public static function floatval( $value )
    {
        if ( strstr($value, ',') ) {
            $value = str_replace('.', '', $value);  // replace dots (thousand seps) with blancs
            $value = str_replace(',', '.', $value); // replace ',' with '.'
        }

        return floatval($value);
    }

    //
    // --- Filesystem functions ------------------------------------------------

    /** {{{
     * Test if a file exists
     *
     * altn. function to file_exists() std file_exists() overwrites the last
     * access time (atime). If php version >= 5 and the url (in given parameter)
     * contains a scheme the test will use fopen instead of php internal
     * file_exists().
     * For local files you may use "file://" as prefix to not change the atime.
     *
     * @see http://php.net/manual/en/function.file-exists.php Standard documentation
     *
     * @param string $url Location or url of the file to be checked
     *
     * @return boolean Returns TRUE if the file or directory specified by $url
     * exists; FALSE otherwise. This function will return FALSE for symlinks
     * pointing to non-existing files.
     * }}} */
    public static function file_exists( $url = '' )
    {
        if ( empty($url) ) {
            return false;
        }

        // @see http://php.net/manual/en/function.parse-url.php
        // scheme: http:// https:// ftp:// file:// php:// c:\ d:\ etc..
        $scheme = self::parseUrl($url, PHP_URL_SCHEME);

        if ( (PHP_VERSION_ID >= '50000') && !empty($scheme) ) {
            if ( @fclose(@fopen($url, 'r')) ) {
                return true;
            } else {
                return false;
            }
        } else {
            // file_exists overwrites the last access (atime) !
            return @file_exists($url);
        }
    }

    /*
      public static function url_exists($url) {
      // Version 4.x supported
      $handle   = curl_init($url);
      if (false === $handle)
      {
      return false;
      }
      curl_setopt($handle, CURLOPT_HEADER, false);
      curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
      curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
      curl_setopt($handle, CURLOPT_NOBODY, true);
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
      $connectable = curl_exec($handle);
      curl_close($handle);
      return $connectable;
      }
     */


    /** {{{
     * Get the contens of a specified file
     *
     * @deprecated since version 2016-07-01
     *
     * This function is similar to file(), except that file_get_contents() returns
     * the file in a string, starting at the specified offset up to maxlen bytes.
     * On failure, file_get_contents() will return FALSE.
     *
     * @desc file_get_contents( string $filename  [, int $flags = 0  [, resource $context
     * [, int $offset = -1  [, int $maxlen = -1  ]]]] )
     *
     * @param string $path Location of the file to get to contents
     * @param mixed $flags Optional Flag to be set. Available flags:
     * # FILE_USE_INCLUDE_PATH   	 Search for filename  in the include directory.
     * See include_path for more information.
     * # FILE_TEXT 	As of PHP 6, the default encoding of the read data is UTF-8.
     * You can specify a different encoding by creating a custom context or by
     * changing the default using stream_default_encoding(). This flag cannot be
     * used with FILE_BINARY.
     * # FILE_BINARY 	With this flag, the file is read in binary mode. This is
     * the default setting and cannot be used with FILE_TEXT.
     *
     * Note: Prior to PHP 6, this parameter is called use_include_path  and is a
     * bool. As of PHP 5 the FILE_USE_INCLUDE_PATH can be used to trigger
     * include path  search.
     * The value of flags  can be any combination of the following flags (with
     * some restrictions), joined with the binary OR (|) operator.
     *
     * @param resource $stream_context A valid context resource created with stream_context_create().
     * If you don't need to use a custom context, you can skip this parameter by NULL.
     * @param <type> $offset
     * @param <type> $maxlen
     *
     * @return string|false Returns the contents of the given file or false on error (too old php version for this)
     * }}} */
    public static function file_get_contents( $path, $flags = 0, $streamContext = null, $offset = -1, $maxlen = -1 )
    {
        $data = false;

        if ( PHP_VERSION_ID >= '40300' ) {
            if ( $maxlen >= 0 ) {
                $data = file_get_contents($path, $flags, $streamContext, $offset, $maxlen);
            } else {
                $data = file_get_contents($path, $flags, $streamContext, $offset);
            }
        }

        return $data;
    }


    /**
     * Get a php.ini variable and return a more technical useable value
     *
     * E.g: If memory limit returns 32M -> 32*1048576 will be returned
     *
     * @param string $key Key to get from php.ini
     * @return string Returns the translated nummeric value if a nummeric value was detekted
     */
    public static function ini_get( $key )
    {
        $val = ini_get($key);
        $val = trim($val);
        if ( empty($val) ) {
            return null;
        } else {
            $last = $val{strlen($val) - 1};
            switch ( $last )
            {
                case 'k':
                case 'K':
                    return (int) $val * 1024;
                    break;
                case 'm':
                case 'M':
                    return (int) $val * 1048576;
                    break;
                case 'g':
                case 'G':
                    $val = $val * 1048576 * 1000;
                    break;
                default:
                    return $val;
                    break;
            }
            return $val;
        }
    }


    //
    // --- String methodes -----------------------------------------------------


    /**
     * Quote string with slashes.
     * If magic_quotes_gpc() is true the string will return or the escaped string.
     *
     * @param string $string String to add slashes if needed
     * @return string Returns the escaped string or the string
     */
    public static function addslashes( $string )
    {
        if ( self::$getMagicQuotesGpc ) {
            return $string;
        } else {
            return addslashes($string);
        }
    }


    /**
     * Un-quotes a quoted string.
     * If magic_quotes_gpc() is true the string will return or the stripped string.
     *
     * @param string $string String to strrip slashes from
     * @return string Returns a string with backslashes stripped off.
     * \' becomes ' and so on. Double backslashes (\\) are made into a single backslash (\).
     */
    public static function stripslashes( $string )
    {
        if ( self::$getMagicQuotesGpc ) {
            return stripslashes($string);
        } else {
            return $string;
        }
    }


    /**
     * {{{ Check if a string is in another string.
     *
     * Alias like function of str(i)str()
     * If needle is not a string, it is converted to an integer and applied as
     * the ordinal value of a character.
     *
     * @param string $needle String to be checked
     * @param string $heystack
     * @param boolean $insensitive If true needle will be checked insensitive
     * @param boolean $beforeNeedle If TRUE, strstr() returns the part of the
     * haystack before the first occurrence of the needle.
     * @return string|false Returns the portion of string, or FALSE if needle is not found.
     * }}} */
    public static function in_string( $needle, $heystack, $insensitive = false, $beforeNeedle = false )
    {
        if ( $beforeNeedle ) {
            if ( $insensitive ) {
                return stristr($heystack, $needle, $beforeNeedle);
            } else {
                return strstr($heystack, $needle, $beforeNeedle);
            }
        } else {
            if ( $insensitive ) {
                return stristr($heystack, $needle);
            } else {
                return strstr($heystack, $needle);
            }
        }
    }


    /**
     * {{{ Convert special characters to HTML entities. Improved version
     *
     * The translations performed are:
     *  # '&' (ampersand) becomes '&amp;'
     *  # '"' (double quote) becomes '&quot;' when ENT_NOQUOTES  is not set.
     *  # ''' (single quote) becomes '&#039;' only when ENT_QUOTES is set.
     *  # '<' (less than) becomes '&lt;'
     *  # '>' (greater than) becomes '&gt;'
     *
     * Improvment to this funktion belongs to the forward look up to only
     * convert "&" not &#123; and not &amp; seems to be fixed since php > 5
     *
     * @see http://php.net/manual/en/function.htmlspecialchars.php
     * @param string $str The string being converted.
     * @param mixed $style by default: ENT_QUOTES ; ENT_COMPAT (convert "); ENT_QUOTES convert
     * both; ENT_NOQUOTES no quote conversation
     * @return string
     * }}} */
    public static function htmlspecialchars( $str = '', $style = ENT_QUOTES )
    {
        // use forward look up to only convert & not &#abc; and not &amp;
        $str = preg_replace('/&(?!(#[0-9]|amp)+;)/s', "&amp;", $str);
        //$str = preg_replace("/&(?![0-9a-z]+;)/s",'&amp;', $str );
        $str = str_replace('<', '&lt;', $str);
        $str = str_replace('>', '&gt;', $str);
        switch ( $style )
        {
            case ENT_COMPAT:
                $str = str_replace('"', "&quot;", $str);
                break;
            case ENT_NOQUOTES:
                // no quotes translation
                break;
            case ENT_QUOTES:    // both quotes translations
            default:
                $str = str_replace('"', "&quot;", $str);
                $str = str_replace("'", '&#039;', $str);
        }
        return $str;
    }


    /**
     * {{{ Re Convert HTML entities (htmlspecialchars reverse)
     *
     * @param string $str The string being converted.
     * @param mixed $style by default: ENT_QUOTES ; ENT_COMPAT (convert "); ENT_QUOTES convert
     * both; ENT_NOQUOTES no quote conversation
     * @return stringreturns the re-converted html entity
     * }}} */
    public static function xhtmlspecialchars( $str = '', $style = ENT_QUOTES )
    {
        $str = str_replace('&amp;', '&', $str);
        $str = str_replace('&lt;', '<', $str);
        $str = str_replace('&gt;', '>', $str);

        switch ( $style )
        {
            case ENT_COMPAT:
                $str = str_replace('&quot;', '"', $str);
                break;

            case ENT_NOQUOTES:
                // no quotes translation
                break;

            case ENT_QUOTES:    // both quotes translations
            default:
                $str = str_replace('&quot;', '"', $str);
                $str = str_replace('&#039;', '\'', $str);
        }

        return $str;
    }


    /**
     * nl2br — Inserts HTML line breaks before all newlines in a string \n -> <br />\n
     *
     * @todo \n -> <br />\n; currently: \n -> <br />
     *
     * @param string $s String to be check/added with <br> tags
     * @return string Returns nl2br  ( string $string  [, bool $is_xhtml=true] )
     */
    public static function nl2br( $string, $isXhtml = true )
    {
        if ( $isXhtml ) {
            $r = '<br />';
        } else {
            $r = '<br>';
        }

        return strtr($string, array("\r\n" => $r, "\r" => $r, "\n" => $r));
    }


    /**
     * Convert html "br" tags to newline break's
     *
     * @param string $string String to be converted
     * @param string $breakChar Element for the breakline eg: \n, \rn, \r; default: \n
     * @return string Returns the converted string
     */
    public static function br2nl( $string = '', $breakChar = "\n" )
    {
        $search = array('<br />', '<br/>', '<br>');
        $replace = array($breakChar, $breakChar, $breakChar);
        $result = str_replace($search, $replace, $string);

        return $result;
    }


    /** {{{
     * parseUrl — Parse a URL and return its components.
     *
     * @param string $url The URL to parse. Invalid characters are replaced by _.
     * @param string $component Specify one of PHP_URL_SCHEME, PHP_URL_HOST,
     * PHP_URL_PORT, PHP_URL_USER, PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY or
     * PHP_URL_FRAGMENT to retrieve just a specific URL component as a string.
     *
     * @return array|string|false|null On seriously malformed URLs, parse_url()
     * may return FALSE. If the component parameter is omitted, an associative
     * array is returned. At least one element will be present within the array.
     * Potential keys within this array are:<br />
     * 		scheme - e.g. http <br />
     * 		host - e.b.: localhost<br />
     * 		port<br />
     * 		user<br />
     * 		pass<br />
     * 		path<br />
     * 		query - after the question mark ?<br />
     * 		fragment - after the hashmark #<br />
     * If the component parameter is not specified, parseUrl() returns a string
     * instead of an array. If the requested component doesn't exist within the
     * given URL, NULL will be returned.
     *
     * @thows Php_Exception Throws exception if parseUrl would return false.
     * }}} */
    public static function parseUrl( $url, $component = null )
    {
        if ( isset($component) ) {
            $x = parse_url($url, $component);
        } else {
            $x = parse_url($url);
        }
        if ( $x === false ) {
            throw new Php_Exception('parseUrl() failt.', 1);
        }

        return $x;
    }


    /**
     * Parses a string into array.
     * Similar to phps parse_url except an exception will be thrown if an empty
     * result will return.
     *
     * Note: <br/>
     * - To get the current QUERY_STRING, you may use the variable $_SERVER['QUERY_STRING'].
     * Also, you may want to read the section on variables from external sources.<br/>
     * - The magic_quotes_gpc setting affects the output of this function, as parse_str()
     * uses the same mechanism that PHP uses to populate the $_GET, $_POST, etc. variables.
     *
     * Example:
     * <code>
     * <?php
     *  $string = "first=value&arr[]=foo+bar&arr[]=baz";
     *  $output = Php::parse_str($string);
     *  echo $output['first'];  // value
     *  echo $output['arr'][0]; // foo bar
     *  echo $output['arr'][1]; // baz
     * ?>
     *
     * @link http://php.net/manual/en/function.parse-str.php
     *
     * @param string $string String to parse.
     * @return array Returns all portions in an associative array
     *
     * @throws Php_Exception Throws exception if string could not be converted.
     */
    public static function parseStr( $string )
    {
        $x = parse_str($string, $res);

        if ( empty($res) ) {
            throw new Php_Exception('Php::parseStr() failt.', 1);
        }

        return $res;
    }


    /**
     * Pad a number (int) to a certain length with another string as prefix.
     * Adds prefixes to an interger number for a given length.
     * E.g: You want number 123 to be 6 chars lenght and want zero fills as
     * prefix like: 000123
     * Note: Given number will be casted to interger.
     * This is a simple helper and alias method of php's str_pad()
     *
     * @param interger $integer Un/signd number
     * @param type $digits Number of characters your number should contain
     * @param type $padString String to be used as prefix char
     *
     * @return string The padded string
     */
    public static function numberPad( $integer, $digits, $padString = '0' )
    {
        return str_pad((int) $integer, $digits, $padString, STR_PAD_LEFT);
    }

    //
    // --- Array methodes ------------------------------------------------------
    //

    /**
     * Return the current element in an array by reference.
     * The current function simply returns the value of the array element that's
     * currently being pointed to by the internal pointer. It does not move the
     * pointer in any way. If the internal pointer points beyond the end of the
     * elements list or the array is empty, current returns false.
     *
     * @see http://php.net/manual/en/function.current.php
     * @return mixed Returns the current value by reference
     */
    public static function &current( &$s )
    {
        return $s[key($s)];
    }


    /** {{{
     * Compare an array (list of values or list of key=>val pairs) with another
     * array and test if a smaler array with their key or values exists in a
     * bigger array.
     * E.g.: Testing an array as whitelist against an array with current sumbitted data
     *
     * @todo test if the 'keys' variant works
     *
     * @param array $have Array original for the comparison (bigger array)
     * @param array $totest Array to test against $have (smaler array)
     * @param string $way Type of array to check values or array keys
     * @return array Returns the result portion on difference or an empty array
     * for no changes between the arrays
     * }}} */
    public static function compareArray( array $have = array(), array $totest = array(), $way = 'vals' )
    {
        $res = array();
        if ( $have !== $totest ) {
            foreach ( $have AS $keyA => $valA ) {
                foreach ( $totest AS $keyB => $valB ) {
                    switch ( $way )
                    {
                        case 'keys':
                            if ( $keyA === $keyB ) {
                                if ( isset($res[$keyA]) ) {
                                    unset($res[$keyA]);
                                }
                                break;
                            } else {
                                $res[$keyA] = $keyB;
                            }
                            break;
                        case 'vals':
                            // working
                            if ( $valA === $valB ) {
                                if ( isset($res[$valA]) ) {
                                    unset($res[$valA]);
                                }
                                break;
                            } else {
                                /* if (is_array($val_a)) {
                                  $res[ $key_a ] = $val_a;
                                  } else {
                                  $res[ $key_a ] = $val_a;
                                  } */
                                $res[$keyA] = $valA;
                            }
                            break;
                    }
                }
            }
        }
        // echo '<pre>'; print_r($res); echo '</pre>';
        return $res;
    }


    /**
     * Check if a key exists in a multidimensional associative array
     *
     * @author Florian Blasel <info@flo-W-orks.com>
     * @param string $needle Needle to look for
     * @param array $haystack Array to be checked
     * @return boolean Returns true if the search key was found
     */
    public static function array_keys_search_recursive_check( $needle, $haystack )
    {
        foreach ( $haystack as $key => $value ) {
            if ( $key === $needle || ( is_array($value) && ( $x = self::array_keys_search_recursive($needle, $value,
                    true) ) ) ) {
                return true;
            }
        }
        return false;
    }


    /** {{{
     * Search for a given key in a multidimensional associative array.
     * If a match was found a list of matches will be returned by reference
     *
     * @todo $stopOnFirstMatch do not work! break nested stuff; static $stopOnFirstMatch ?
     *
     * Example:
     * <code>
     * <?php
     * $bigarray =  array(
     *             'key1' =>
     *                    array(
     *                       'key2' =>
     *                          array(
     *                              'a' => array( 'text'=>'something'),
     *                              'b' => array( 'id'=>737),
     *                              'c' => array( 'name'=>'me'),
     *                          ),
     *                 )
     *            );
     * $matchedKeys = array_keys_search_recursive( 'name',$bigarray);
     * // returns by reference: array( 0=>array( 'name'=>'me');
     * ?>
     * </code>
     *
     * @param string $needle Needle to look for
     * @param array $haystack Array to be scanned
     * @param boolean $stopOnFirstMatch Flag
     *
     * @return array Returns a list of key->value pairs by reference  array indexes to the specified key. Last value
     * contains the searched $needle; if the array is empty nothing were found
     * }}} */
    public static function array_keys_search_recursive( $needle, & $haystack, $stopOnFirstMatch = false )
    {
        $matches = array();
        foreach ( $haystack as $key => &$value ) {

            if ( $stopOnFirstMatch && $stopOnFirstMatch === 'break' ) {
                break;
            }
            // echo ":$needle:=:$key: m: $stopOnFirstMatch\n";
            if ( $key === $needle ) {
                $matches[] = & $haystack;
                if ( $stopOnFirstMatch ) {
                    $stopOnFirstMatch = 'break';
                    break;
                }
            } else {
                // go deeper
                if ( is_array($value) ) {
                    $array = self::array_keys_search_recursive($needle, $value, $stopOnFirstMatch);
                    $matches = array_merge($matches, $array);
                }
            }
        }
        // echo "re";
        return $matches;
    }


    /**
     * Merge two or more arrays recursively.
     *
     * The difference between this and php's array_merge_recursive are that
     * values of the same key wont be appended.
     * If the input arrays have the same string keys, then the later value for
     * that key will overwrite the previous one.
     *
     * @todo to be tested deeply
     *
     * @param array $array1, $array2, $array3... Arrays be be merged
     * @return array Returns the merged array
     *
     * @throws Mumsys_Exception Throws exception on unexpercted behaviour
     */
    public static function array_merge_recursive()
    {
        if ( func_num_args() < 2 ) {
            throw new Mumsys_Exception(__METHOD__ . ' needs at least two arrays as arguments');
        }

        $arrays = func_get_args();
        $merged = array();

        while ( $arrays ) {
            $array = array_shift($arrays);

            if ( !is_array($array) ) {
                throw new Mumsys_Exception(__METHOD__ . ' given argument is not an array "' . $array . '"');
            }

            if ( !$array ) {
                continue;
            }

            foreach ( $array as $key => $value ) {
                if ( is_string($key) ) {
                    if ( is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key]) ) {
                        $merged[$key] = self::array_merge_recursive($merged[$key], $value);
                    } else {
                        $merged[$key] = $value;
                    }
                } else {
                    $merged[] = $value;
                }
            }
        }

        return $merged;
    }

    //
    // --- Helper methodes -----------------------------------------------------


    /** {{{
     * Check free disk space (df -a)
     *
     * Basicly this function is usefule when working with large files which will
     * be created or copied. This function can help you to break operations befor
     * disk-full errors will occour. The check will work on percentage (%) of
     * free disk space (df -a)
     *
     * @staticvar array $paths Memory for check paths
     * @staticvar array $sizes Memory for checked disksizes
     * @staticvar array $times Memory for time of last check
     *
     * @param string $path Location to call a df -a command it should be the root
     * path of a mountpoit at least or the path e.g: where to store data
     * @param integer $secCmp Number of seconds a compare-time will be OK during
     * a process befor df -a command will be called again (limit the number if you
     * have huge file movements to beware crashes or "disk-full"- errors)
     * @param integer $maxSizeCmp Number in percent. Max size when a disk-full
     * event should be thrown (max allowed/free size compare Value)
     * @param Mumsys_Logger $logger Logger object which need at least the log method
     * @param string $cmd df command to be executed. Maybe different on windows
     * e.g: 'c:/cygwin/bin/df.exe -a [path]; Parameter %1$s in the cmd line
     * is the placeholder for the $path.
     *
     * @return boolean Returns true if disk size exceed the limit or false for OK
     * }}} */
    public static function check_disk_free_space( $path = '', $secCmp = 60, $maxSizeCmp = 92,
        Mumsys_Logger_Interface $logger, $cmd = 'df -a %1$s' )
    {
        // key of exec result in $cmd
        $resultKey = 4;

        if ( !is_dir($path . DIRECTORY_SEPARATOR) ) {
            $logger->log(__METHOD__ . ': path do not exists: "' . $path . '"', 3);
            return true;
        }

        $cmd = sprintf($cmd, $path);

        $now = time();
        $_v = array();

        static $paths = null;
        static $sizes = null;
        static $times = null;

        if ( $paths === null ) {
            $paths = array();
            $sizes = array();
            $times = array();
        }

        $logger->log(__METHOD__ . ': using path: ' . $path, 6);

        // cached data check
        $i = array_search($path, $paths);
        if ( $i !== false && isset($times[$i]) && ($now - $times[$i]) <= $secCmp ) {
            if ( $sizes[$i] >= $maxSizeCmp ) {
                $logger->log(__METHOD__ . ': disc space overflow: ' . $sizes[$i] . ' (' . $maxSizeCmp . ' is max limit!)',
                    3);
                return true;
            } else {
                $logger->log(__METHOD__ . 'disc space OK: ' . $sizes[$i] . '% (' . $maxSizeCmp . ' is max limit!)', 6);
                return false;
            }
        }

        try {
            $tmp = '';
            $data = null;
            $return = null;
            $result = exec($cmd, $data, $return);
            if ( !$result || $return !== 0 ) {
                throw new Exception(__METHOD__ . ': cmd error: "' . $cmd . '"', 1);
            }
            $logger->log(__METHOD__ . ': cmd: "' . $cmd . '"', 7);

            $r = explode(' ', $data[1]);
            while ( list($a, $b) = each($r) ) {
                $b = trim($b);
                if ( $b != '' ) {
                    $_v[] = $b;
                }
            }
            $logger->log($_v, 7);

            $size = (int) $_v[$resultKey];

            $paths[] = $path;
            $sizes[] = $size;
            $times[] = time();

            if ( $size >= $maxSizeCmp ) {
                $logger->log(
                    sprintf(
                        __METHOD__ . ': disc space overflow: size: "%1$s" (max limit: "%2$s") for path: "%3$s"',
                        $sizes[$i], $maxSizeCmp, $path
                    ), 3
                );
                return true;
            } else {
                $logger->log(
                    sprintf(
                        __METHOD__ . 'disc space OK: size "%1$s" (max limit: "%2$s") for path: "%3$s"', $sizes[$i],
                        $maxSizeCmp, $path
                    ), 6
                );

                return false;
            }
        } catch ( Exception $e ) {
            $logger->log(__METHOD__ . ': Catchable exception. Message: "' . $e->getMessage() . '"', 0);
            return true;
        }
    }

}