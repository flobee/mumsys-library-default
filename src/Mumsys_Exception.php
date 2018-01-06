<?php

/**
 * Mumsys_Exception
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright (c) 2015 by Florian Blasel
 * @author Florian Blasel <flobee.code@gmail.com>

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
     * Default error code for technical errors, no futher reason,
     * discribed in the error message
     * @var constant
     */
    const ERRCODE_DEFAULT = 1;

    /**
     * File not found error code
     * @var integer
     */
    const ERRCODE_404 = 404;

    /**
     * Server error.
     * @var integer
     */
    const ERRCODE_500 = 500;

}
