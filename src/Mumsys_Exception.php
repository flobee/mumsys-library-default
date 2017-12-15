<?php

/**
 * Mumsys_Exception
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright (c) 2015 by Florian Blasel
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 *
 * @category    Mumsys
 * @package     Library
 * @version 0.1 - Created on 2009-11-27
 * $Id: Mumsys_Exception.php 3165 2015-04-09 20:25:23Z flobee $
 */


/**
 * Generic exception class
 *
 * @category    Mumsys
 * @package     Library
 */
class Mumsys_Exception
    extends Exception
{
    /**
     * Default error code for technical errors, no futher reason as discribed
     * in the error message.
     */
    const ERRCODE_DEFAULT = 1;

}
