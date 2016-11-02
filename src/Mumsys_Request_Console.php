<?php


/**
 * Mumsys_Request_Console
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Request
 */


/**
 * Abstract request class to get input parameters.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Request
 */
class Mumsys_Request_Console
    extends Mumsys_Request_Abstract
    implements Mumsys_Request_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.1';


    /**
     * Initialise the request object using servers argv array.
     *
     * @param array $options Optional initial options e.g.:
     * 'programKey','controllerKey', 'actionKey',
     */
    public function __construct( array $options = array() )
    {
        parent::__construct($options);

        if (isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
            $this->_input += $_SERVER['argv'];
        }

        $_SERVER['argv'] = array();
    }

}