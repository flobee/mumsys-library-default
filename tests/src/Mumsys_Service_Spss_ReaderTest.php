<?php

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
        $spssFile = __DIR__ . '/../testfiles/Service/Spss/pspp.sav';
        $parser = \SPSS\Sav\Reader::fromString( file_get_contents( $spssFile ) );

        $this->_object = new Mumsys_Service_Spss_Reader($parser);
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

        $expected = array( $obj );

        $actual = $this->_object->getVariableItems($list);

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMapByLabels
     * @todo   Implement testGetVariableMapByLabels().
     */
    public function testGetVariableMapByLabels()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMapByKeys
     * @todo   Implement testGetVariableMapByKeys().
     */
    public function testGetVariableMapByKeys()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMapByRegex
     * @todo   Implement testGetVariableMapByRegex().
     */
    public function testGetVariableMapByRegex()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Spss_Reader::getVariableMap
     * @todo   Implement testGetVariableMap().
     */
    public function testGetVariableMap()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}