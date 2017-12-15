<?php

/**
 * Mumsys_Service_AbstractTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 * @version     1.0.0
 * Created: 2017-11-30
 */


class Mumsys_Service_AbstractTestClass
    extends Mumsys_Service_Abstract
{

}


/**
 * Mumsys_Service_Abstract Test
 */
class Mumsys_Service_AbstractTest extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Service_AbstractTestClass
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.0.0';
        $this->_object = new Mumsys_Service_AbstractTestClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


    /**
     * @c o v e r s Mumsys_Service_Abstract::VERSION
     */
    public function testVersion()
    {
        $actual = Mumsys_Service_Abstract::VERSION;

        $this->assertEquals($this->_version, $actual);
    }
}