<?php

/**
 * Mumsys_Service_Spss_ReaderTest
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


/**
 * Mumsys_Service_Spss_Reader Test
 */
class Mumsys_Service_Spss_ReaderTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Service_Spss_Reader
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.0.0';

        $spssFile = __DIR__ . '/../testfiles/Service/Spss/pspp.sav';
        $parser = \SPSS\Sav\Reader::fromString( file_get_contents( $spssFile ) );

        $this->_object = new Mumsys_Service_Spss_Reader( $parser );
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
     * @covers Mumsys_Service_Spss_Reader::getVariableItems
     * @covers Mumsys_Service_Spss_Reader::_checkVariableNameExists
     */
    public function testGetVariableItems()
    {
        $list = array('v1int');
        $obj = new SPSS\Sav\Record\Variable;
        $obj->name = 'V1INT';
        $obj->label = 'v1';
        $obj->width = 0;
        $obj->missingValues = array(1.0, 2.0, 1.0);
        $obj->missingValuesFormat = -3;
        $obj->print = array(
            0 => 0,
            1 => 3,
            2 => 5,
            3 => 0,
        );
        $obj->write = array(
            0 => 2,
            1 => 3,
            2 => 5,
            3 => 0,
        );

        $expected = array($obj);
        $actual = $this->_object->getVariableItems( $list );

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMapByLabels
     */
    public function testGetVariableMapByLabels()
    {
        $labels = array(
            'v1int', 'v2float', 'v3string'
        );
        $actual = $this->_object->getVariableMapByLabels( $labels );
        $expected = array(
            'V1INT' => 'v1int',
            'V2FLOAT' => 'v2float',
            'V3STRING' => 'v3string'
        );

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMapByKeys
     */
    public function testGetVariableMapByKeys()
    {
        $keys = array(
            'V1INT', 'V2FLOAT', 'V3STRING'
        );
        $actual = $this->_object->getVariableMapByKeys( $keys );
        $expected = array(
            'V1INT' => 'v1int',
            'V2FLOAT' => 'v2float',
            'V3STRING' => 'v3string'
        );

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMapByRegex
     */
    public function testGetVariableMapByRegex()
    {
        $regex = array(
            '/(v)/i'
        );
        $actual1 = $this->_object->getVariableMapByRegex( $regex );
        $actual2 = $this->_object->getVariableMapByRegex();
        $expected1 = array(
            'V1INT' => 'v1int',
            'V2FLOAT' => 'v2float',
            'V3STRING' => 'v3string'
        );

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected1, $actual2 );

        ini_set( "error_reporting", 0 );
        $this->expectException( 'Mumsys_Service_Spss_Exception' );
        $this->expectExceptionMessage( 'Regex error' );
        $this->_object->getVariableMapByRegex( array('/f#') );
        ini_set( "error_reporting", -1 );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMap
     */
    public function testGetVariableMap()
    {
        $actual1 = $this->_object->getVariableMap();
        $expected = array(
            'V1INT' => 'v1int',
            'V2FLOAT' => 'v2float',
            'V3STRING' => 'v3string'
        );

        $actual2 = $this->_object->getVariableMap();

        $this->assertEquals( $expected, $actual1 );
        $this->assertEquals( $expected, $actual2 );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getData
     */
    public function testGetData()
    {
        $actual = $this->_object->getData();
        $expected = array(
            array(1, 1.23, 'ab'),
            array(2.0, 2.3399999999999, 'bc'),
            array(3, 3.45, 'cd')
        );

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getDocumentInfo
     */
    public function testGetDocumentInfo()
    {
        $actual = $this->_object->getDocumentInfo();
        $expected = array(
            'comment to file                                                                 ',
            'Added by pspp:  (17 Nov 2017 eingegeben)                                        ',
            '                                                                                ',
            '   (17 Nov 2017 eingegeben)                                                     '
        );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @covers Mumsys_Service_Spss_Reader::getEncoding
     */
    public function testGetEncoding()
    {
        $actual1 = $this->_object->getEncoding();
        $actual2 = $this->_object->getEncoding();
        $expected = 'UTF-8';

        $this->assertEquals( $expected, $actual1 );
        $this->assertEquals( $expected, $actual2 );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVersionOfSource
     */
    public function testGetVersionOfSource()
    {
        $actual1 = $this->_object->getVersionOfSource();
        $actual2 = $this->_object->getVersionOfSource();
        $expected = array(0,10,2);

        $this->assertEquals( $expected, $actual1 );
        $this->assertEquals( $expected, $actual2 );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getFloatingPointInfo
     */
    public function testGetFloatingPointInfo()
    {
        $actual1 = $this->_object->getFloatingPointInfo();
        $actual2 = $this->_object->getFloatingPointInfo();
        $expected = array(
            'sysmis' => -1.7976931348623157E+308,
            'highest' => 1.7976931348623157E+308,
            'lowest' => -1.7976931348623155E+308,
        );

        $this->assertEquals( $expected, $actual1 );
        $this->assertEquals( $expected, $actual2 );
    }



    /**
     * @c o v e r s Mumsys_Service_Spss_Reader::VERSION
     */
    public function testVersion()
    {
        $actual = Mumsys_Service_Spss_Reader::VERSION;

        $this->assertEquals($this->_version, $actual);
    }
}