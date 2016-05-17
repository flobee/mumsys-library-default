<?php

/* {{{ */
/**
 * Mumsys_Session_None
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2005 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 * Created: 2005-01-01, new in 2016-05-17
 */
/* }}} */


/**
 * Memory based wrapper class to be used as dummy
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Session
 */
class Mumsys_Session_None
    extends Mumsys_Session_Abstract
    implements Mumsys_Session_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Representation of the session befor it will be set to $_SESSION
     * @var array
     */
    private $_records = array();

    /**
     * ID of the session of this system.
     * @var string
     */
    private $_id;


    /**
     * Initialize the session object.
     *
     * @param string $appSecret Application domain or installation key
     */
    public function __construct( $appSecret = 'mumsys' )
    {
        $this->_id = 'myPrivateSecetPlaysQWERTsY';
        $this->_records = array();

        parent::__construct($this->_records, $this->_id, $appSecret);
    }


    /**
     * Stores session informations and closes it.
     */
    public function __destruct()
    {
        $this->_records = array();
    }


    /**
     * Clears and unsets the current session
     */
    public function clear()
    {
        parent::clear();
    }

}