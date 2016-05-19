<?php

/* {{{ */
/**
 * Mumsys_Exception
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @copyright (c) 2015 by Florian Blasel
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @version 0.1 - Created on 2009-11-27
 * $Id: Mumsys_Exception.php 3165 2015-04-09 20:25:23Z flobee $
 */
/* }}} */


/**
 * Generic exception class
 *
 * @category    Mumsys
 * @package     Mumsys_Library
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
     * @var string
     */
    const ERRCODE_404 = '404';

}