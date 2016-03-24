<?php

/*{{{*/
/**
 * Mumsys_Permissions_Abstract
 * for MUMSYS (Multi User Management System)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Permissions
 * Created: 2016-01-19
 * @filesource
 */
/*}}}*/


/**
 * Class to deal with the permissions (acl)
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Permissions
 */
abstract class Mumsys_Permissions_Abstract
    extends Mumsys_Abstract
    implements Mumsys_Permissions_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '3.1.0';

    /**
     * Tracks the current request.
     * old alias function track_onlineuser
     *
     * @throws Mumsys_Permissions_Exception Throws exception on errors
     */
     public function trackRequest()
     {
         throw new Mumsys_Permissions_Exception(__METHOD__ . ' Not implemented yet');
     }

    /**
     * get/set language (get_language)
     * load basic language and program language
     *
     * @param string|false $language Languge to load otherwise the default
     * language will be loaded
     *
     * @return boolean true on success
     * @throws Mumsys_Permissions_Exception Throws exception on errors
     */
    public function languageLoad( $language = false )
    {
        throw new Mumsys_Permissions_Exception(__METHOD__ . ' Not implemented yet');
    }

}
