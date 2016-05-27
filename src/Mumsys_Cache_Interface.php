<?php

/* {{{ */
/**
 * Mumsys_Cache_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Cache
 * Created: 2013-12-10
 */
/* }}} */


/**
 * Caching interface
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Cache
 */
interface Mumsys_Cache_Interface
{


    /**
     * Initialize the cache object and sets group and id to store it.
     *
     * The cache filename will be created like: path/ + prefix + group + _ + id
     *
     * @param string $group Groupname
     * @param string $id Unique ID e.g. requested area + userid
     */
    public function __construct( $group, $id );


    /**
     * Cache the content.
     *
     * @param int $ttl Time to live in seconds
     * @param mixed $data Content to be cached
     */
    public function write( $ttl, $data );


    /**
     * Returns the cached contents.
     */
    public function read();


    /**
     * Checks if an entry is cached.
     *
     * @param string $group Groupname
     * @param string $id Unique ID
     *
     * @return boolean True if cache exists or false
     */
    public function isCached();


    /**
     * Removes the specific cache file.
     *
     * @return boolean True on success
     */
    public function removeCache();


    /**
     * Sets the filename prefix.
     *
     * @param string $prefix Filename Prefix to use
     */
    public function setPrefix( $prefix );


    /**
     * Returns the filename prefix.
     */
    public function getPrefix();


    /**
     * Sets the path for cache files.
     *
     * @param string $store The dir where to store the cache files
     */
    public function setPath( $path );


    /**
     * Returns the path for cache files.
     */
    public function getPath();


    /**
     * Sets caching mode to enabled or not.
     *
     * @param boolean $flag True to enable the cache, false to disable
     */
    public function setEnable( $flag );


    /**
     * Builds a filename/path from group, id and path.
     */
    protected function _getFilename();

}