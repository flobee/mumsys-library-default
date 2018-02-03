<?php

/**
 * Mumsys_Geolocation_Item_DefaultTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 * @verion      1.0.0
 */


/**
 * Mumsys_Geolocation_Item_Default Test
 * Generated on 2013-12-08 at 18:50:45.
 */
class Mumsys_Geolocation_Item_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Geolocation_Item
     */
    protected $_object;
    protected $_defaults;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @covers Mumsys_Geolocation_Item::__construct
     * @covers Mumsys_Geolocation_Item::_getItemDefaults
     */
    protected function setUp()
    {
        $this->_defaults = array(
            'publisher' => array(
                'id' => 1,
                'name' => 'publisher name',
                'lastupdate' => 123456,
                'language' => 'en',
                'copyright' => 'copyright infos',
            ),
            'location' => array(
                'id' => 123,
                'thatid' => 'ABCRE',
                'street' => 'hamburger str 1',
                'areaCode' => '20095',
                'city' => 'Hamburg',
                'region' => 'Hamburg',
                'countryName' => 'Germany',
                'countryCode' => 'DE',
                'fromattedAddr' => 'formatted addr.',
                'continentCode' => 'EU',
                'sunrise' => 1,
                'sunset' => 100,
                'latitude' => 53.54,
                'longitude' => 10.00,
                'altitude' => 0,
                'tz_offset' => 1,
                'tz_name' => 'Europe/Berlin',
                'dmaCode' => '0',
                'currencyCode' => 'EUR',
                'currencySymbol' => '€',
                'currencyConverter' => 0.1,
                'phonePrefixCode' => '040',
            ),
        );
        $this->_object = new Mumsys_Geolocation_Item_Default( $this->_defaults, true );
    }


    protected function tearDown()
    {
        $this->_object = null;
        unset( $this->_object );
    }


    /**
     * @covers Mumsys_Geolocation_Item_Default::getPublisher
     */
    public function testGetPublisher()
    {
        $actual = $this->_object->getPublisher();
        $this->assertEquals( $this->_defaults['publisher'], $actual );
    }


    /**
     * @covers Mumsys_Geolocation_Item_Default::setPublisher
     */
    public function testSetPublisher()
    {
        $object = new Mumsys_Geolocation_Item_Default( array(), true );
        $object->setPublisher( $this->_defaults['publisher'] );
        $this->assertEquals( $this->_defaults['publisher'], $object->getPublisher() );
    }


    /**
     * @covers Mumsys_Geolocation_Item_Default::getLocation
     */
    public function testGetLocation()
    {
        $actual = $this->_object->getLocation();
        $this->assertEquals( $this->_defaults['location'], $actual );
    }


    /**
     * @covers Mumsys_Geolocation_Item_Default::setLocation
     */
    public function testSetLocation()
    {
        $object = new Mumsys_Geolocation_Item_Default( array(), true );
        $object->setLocation( $this->_defaults['location'] );
        $this->assertEquals( $this->_defaults['location'], $object->getLocation() );
    }


    /**
     * @covers Mumsys_Geolocation_Item_Default::toArray
     */
    public function testToArray()
    {
        $actual = $this->_object->toArray();
        $this->assertEquals( $this->_defaults, $actual );
    }

}
