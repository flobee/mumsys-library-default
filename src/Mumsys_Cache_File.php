<?php

/**
 * Mumsys_Cache_File
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Cache
 * Created: 2013-12-10
 */


/**
 * Class for file caching
 *
 * Example:
 * <code>
 * $cache = new Mumsys_Cache_File('group-ID', 'individual-ID');
 * $cache->setPath(__DIR__ . '/cache/');
 * if ($cache->isCached()) {
 *      // get the cached data
 *      $data = unserialize( $oCache->read() );
 * } else {
 *      $data = array('a' => 1, 'b' => 2, 'c' => 3);
 *      $cache->write( 3600, serialize($data) );
 * }
 * </code>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Cache
 */
class Mumsys_Cache_File
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.2';

    /**
     * Flag if caching is enabled or not
     * @var bool
     */
    private $_enabled = true;

    /**
     * Path to store the cache file/s
     * @var string
     */
    private $_path = '/tmp/';

    /**
     * Prefix for cache files
     * @var string
     */
    private $_prefix = 'cache_';

    /**
     * Individual group name (a-z) to define a cache file to find on the
     * filesystem more easier if needed to look for manually..
     * @var string
     */
    private $_group;

    /**
     * Individual ID to define a cache file which will be md5 encrypted.
     * @var string
     */
    private $_id;


    /**
     * Initialize the cache object and sets group and id to build the cache
     * filename.
     *
     * The cache filename will be created like: path/ + prefix + group + _ + id
     *
     * @param string $group Groupname
     * @param string $id Unique ID e.g. requested area + userid
     */
    public function __construct( $group, $id )
    {
        $this->_id = md5( (string) $id );
        $this->_group = (string) $group;
    }


    /**
     * Writes the content to the cache file in exclusive + binay mode.
     *
     * @param int $ttl Time to live in seconds
     * @param mixed $content Content to be cached
     */
    public function write( $ttl, $content )
    {
        $filename = $this->_getFilename();

        if ( $fp = fopen( $filename, 'wb' ) ) {
            if ( flock( $fp, LOCK_EX ) ) {
                $data = serialize( $content );
                fwrite( $fp, $data );
            }
            fclose( $fp );

            // Set filemtime
            touch( $filename, time() + (int) $ttl );
        }
    }


    /**
     * Returns the cached content.
     *
     * @return mixed Contens of the cache file
     */
    public function read()
    {
        $filename = $this->_getFilename();
        $data = file_get_contents( $filename );

        return unserialize( $data );
    }


    /**
     * Checks if an entry is cached.
     *
     * @param string $group Groupname
     * @param string $id Unique ID
     *
     * @return boolean True if cache exists or false
     */
    public function isCached()
    {
        if ( $this->_enabled ) {
            $filename = $this->_getFilename();

            if ( file_exists( $filename ) && filemtime( $filename ) > time() ) {
                return true;
            }
            @unlink( $filename );
        }

        return false;
    }


    /**
     * Removes the cache file.
     *
     * @return boolean True on success
     *
     * @throws Exception If remove of the cache fails
     */
    public function removeCache()
    {
        $filename = $this->_getFilename();
        try {
            file_exists( $filename );
            unlink( $filename );
        }
        catch ( Exception $ex ) {
            throw new Mumsys_Cache_Exception( $ex->getMessage(), $ex->getCode() );
        }

        return true;
    }


    /**
     * Sets the filename prefix.
     *
     * @param string $prefix Filename prefix for the cache filename
     */
    public function setPrefix( $prefix )
    {
        $this->_prefix = (string) $prefix;
    }


    /**
     * Returns the filename prefix.
     *
     * @return string Prefix of the cache filename default: "cache_"
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }


    /**
     * Sets the path for cache files.
     *
     * @param string $store The dir where to store the cache files
     */
    public function setPath( $path )
    {
        $this->_path = rtrim( (string) $path, '/' ) . '/';
    }


    /**
     * Returns the path for cache files.
     *
     * @return string Path of the cache files
     */
    public function getPath()
    {
        return $this->_path;
    }


    /**
     * Sets caching mode to enabled or not.
     *
     * If set to false it forces the isCached() method to return false.
     *
     * @param boolean $flag True to enable the cache, false to disable
     */
    public function setEnable( $flag )
    {
        $this->_enabled = (bool) $flag;
    }


    /**
     * Builds a filename/path from group, id and path.
     *
     * @return string File location of the cache file
     */
    protected function _getFilename()
    {
        return $this->_path . $this->_prefix . $this->_group . '_' . $this->_id;
    }

}
