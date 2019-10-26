<?php

/**
 * Mumsys_FileSystem
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright (c) 2006 by Florian Blasel
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  FileSystem
 * Created on 2006-12-01
 */


/**
 * Class for the file system and tools to handle files or directories
 *
 * @deprecated since version 3.0.7 Use Mumsys_FileSystem_Default
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  FileSystem
 */
class Mumsys_FileSystem
    extends Mumsys_FileSystem_Default
    implements Mumsys_FileSystem_Interface
{
    /**
     * Version ID information.
     */
    const VERSION = '3.0.7';

}
