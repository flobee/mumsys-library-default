<?php

/**
 * Mumsys_Logger_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Logger
 * @version     1.0.0
 * Created on 2011/02
 */


/**
 * Writer interface for Mumsys_Logger object
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Logger
 */
interface Mumsys_Logger_Writer_Interface
{
    /**
     * Write given content to the writer
     *
     * @param string $content String to save to the logfile
     * @return true Returns true on success.
     */
    public function write( $content );
}
