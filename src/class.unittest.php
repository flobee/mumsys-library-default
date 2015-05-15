<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * MUMSYS 2 Library for Multi User Management Interface
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2011 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Tests
 * @package     Mumsys_Library
 * @subpackage  Testfile
 * @version     0.0.1
 * 0.1 -  Created on 24.02.2011
 * $Id$
 * @filesource
 * ----------------------------------------------------------------------------
 */
/*}}}*/


/**
 * Unittest test class
 *
 * @category    Tests
 * @package     Mumsys_Library
 * @subpackage  Testfile
 */
class unittest
{
    /**
     * Version ID information
     */
    const VERSION = '0.0.1';

    /**
     * Some variable
     *
     * @var string
     */
    private $_something;


    /**
     * Constructor.
     *
     * @param string $_something
     */
    public function __construct($something='a')
    {
        $this->_something = $something;
    }

}
