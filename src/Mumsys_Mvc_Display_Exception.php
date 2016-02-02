<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Exception
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * @version     1.0.0
 * Created: 2016-01-30
 * @filesource
 */
/*}}}*/


/**
 * Generic Exception class which will be thown if no other exception will do
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
class Mumsys_Mvc_Display_Exception extends Mumsys_Exception
{
    /**
     * @var constant Technical error. General error code if no other code is possible.
     */
    const ERRCODE_DEFAULT = 1;
    /**
     * @var constant HTTP 401 error code
     */
    const ERRCODE_HTTP401 = 401;
    /**
     * @var constant HTTP 403 error code
     */
    const ERRCODE_HTTP403 = 403;
    /**
     * @var constant HTTP 500 error code, server error
     */
    const ERRCODE_HTTP500 = 500;
    /**
     * @var constant HTTP 503 error code
     */
    const ERRCODE_HTTP503 = 503;

}
