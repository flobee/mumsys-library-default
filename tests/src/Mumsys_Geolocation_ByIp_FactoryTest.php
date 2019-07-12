<?php

/**
 * Mumsys_Geolocation_ByIp_FactoryTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 * @version     1.0.0
 */


/**
 * Mumsys_Geolocation_ByIp_Factory Test
 * Generated on 2013-12-09 at 11:44:24.
 */
class Mumsys_Geolocation_ByIp_FactoryTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Geolocation_ByIp_Factory
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_object = new Mumsys_Geolocation_ByIp_Factory;
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->_object = null;
        unset( $this->_object );
    }


    /**
     * @covers Mumsys_Geolocation_ByIp_Factory::getInstance
     * @covers Mumsys_Geolocation_ByIp_Factory::autodetectService
     * @covers Mumsys_Geolocation_ByIp_Factory::_initService
     */
    public function testGetInstance()
    {
        $lc = 'EUR';

        $actual = $this->_object->getInstance( 'autodetect', '80.171.12.252', $lc );
        $this->assertInstanceOf( 'Mumsys_Geolocation_ByIp_GeoIPPhp', $actual );

        $actual = $this->_object->getInstance( 'geoip_php', '80.171.12.252', $lc );
        $this->assertInstanceOf( 'Mumsys_Geolocation_ByIp_GeoIPPhp', $actual );

        $actual = $this->_object->getInstance( 'geoplugin', '80.171.12.252', $lc );
        $this->assertInstanceOf( 'Mumsys_Geolocation_ByIp_GeoPlugin', $actual );

        $actual = $this->_object->getInstance( 'geoip_apache', '80.171.12.252', $lc );
        $this->assertInstanceOf( 'Mumsys_Geolocation_ByIp_GeoIPApache', $actual );

        $_SERVER['GEOIP_CITY'] = 'Hamburg';
        $actual = $this->_object->getInstance( 'autodetect', '80.171.12.252', $lc );
        $this->assertInstanceOf( 'Mumsys_Geolocation_ByIp_GeoIPApache', $actual );
        unset( $_SERVER['GEOIP_CITY'] );

        $this->expectException( 'Mumsys_Geolocation_Exception' );
        $this->expectExceptionMessage( 'Missing IP address' );
        $this->_object->getInstance( 'autodetect', false, $lc );
    }


    /**
     * @covers Mumsys_Geolocation_ByIp_Factory::getInstance
     * @covers Mumsys_Geolocation_ByIp_Factory::autodetectService
     * @covers Mumsys_Geolocation_ByIp_Factory::_initService
     */
    public function testGetInstanceExceptions()
    {
        $this->expectException( 'Mumsys_Geolocation_Exception' );
        $this->expectExceptionMessage( 'Service "unknown" is not available' );
        Mumsys_Geolocation_ByIp_Factory::getInstance(
            'unknown', '80.171.12.252', 'EUR'
        );
    }

}
