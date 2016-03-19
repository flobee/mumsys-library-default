<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Session_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 * @version     1.0.0
 * Created: 2016-03-19
 * @filesource
 */
/*}}}*/


/**
 * Session interface
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 */
interface Mumsys_Session_Interface
    extends Mumsys_GetterSetter_Interface
{
    /**
     * Returns the current session data based on the current session id.
     *
     * Note: This is befor it will be available in $_SESSION.
     *
     * @param string $key value of the key to return to
     * @return mixed Stored value
     */
    public function getCurrent();

    /**
     * Returns the complete active session data.
     *
     * Note: This is befor it will be available in $_SESSION. Existing records
     * in $_SESSION after initialisation of this class are not listed!
     *
     * @param string $key value of the key to return to
     * @return mixed Stored value
     */
    public function getAll();

    /**
     * Returns the session ID.
     *
     * @return string
     */
    public function getID();

    /**
     * Clears and unsets the current session
     */
    public function clear();

}
