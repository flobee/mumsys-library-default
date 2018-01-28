<?php

/**
 * Mumsys_Geolocation_ByIp_GeoIPApacheTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 */


/**
 * Mumsys_Geolocation_ByIp_GeoIPApache Test
 * Generated 2018-01-28 at 19:00:34.
 */
class Mumsys_Geolocation_ByIp_GeoIPApacheTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Geolocation_ByIp_GeoIPApache
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $ip = '127.0.0.1';
        $this->_object = new Mumsys_Geolocation_ByIp_GeoIPApache( $ip, 'EUR' );
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
     * @covers Mumsys_Geolocation_ByIp_GeoIPApache::locate
     */
    public function testLocate()
    {
        $this->markTestSkipped( 'apache setup required' );

//        $actual = $this->_object->locate();
//        $expected = '';
//        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Geolocation_ByIp_GeoIPApache::createItem
     */
    public function testCreateItem()
    {
        $actual = $this->_object->createItem();

        $this->assertInstanceOf( 'Mumsys_Geolocation_Item_Default', $actual );
    }

}
