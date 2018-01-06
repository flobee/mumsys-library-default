<?php

/**
 * Mumsys_FileSystem_Common_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2007 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  FileSystem
 */


/**
 * Common class for file system usage.
 *
 * Here you will find basic methodes used in several filesystem-classes
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  FileSystem
 */
abstract class Mumsys_FileSystem_Common_Abstract
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '3.1.0';


    /**
     * Get extension of a file
     *
     * @param string $file Filename or file location
     *
     * @return string String of the extension or '' (empty string)
     */
    public static function extGet( $file )
    {
        $ext = strrchr(basename($file), '.');
        if ( $ext ) {
            $ext = substr($ext, 1);
        } else {
            $ext = '';
        }
        return $ext;
    }


    /**
     * Get the name for the file without ".fileextension".
     *
     * @param string $file Filename or file location
     *
     * @return string Returns the name of the file without the extension
     */
    public static function nameGet( $file = '' )
    {
        $pos = strrpos($file, '.');
        if ( $pos === false ) {
            return basename($file);
        } else {
            return substr(basename($file), 0, $pos);
        }
    }


    /**
     * Get extension of current file
     *
     * @return string Returns a string of the extension or '' (empty string)
     */
//    public function extensionGet()
//    {   // $this->extension =
//        return $this->extGet($this->file);
//    }

}