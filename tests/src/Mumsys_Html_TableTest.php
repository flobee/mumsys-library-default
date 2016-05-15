<?php


/**
 * Mumsys_Html_Table Test
 */
class Mumsys_Html_TableTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Html_Table
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Html_Table(array('width' => '600'));
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = NULL;
    }


    /**
     * @covers Mumsys_Html_Table::__construct
     */
    public function test_construct()
    {
        $this->_object = new Mumsys_Html_Table(array('width' => '600'));
        $actual = $this->_object->getSource();

        $this->assertEquals('', $actual);
        $this->assertEquals(array('No content found'), $this->_object->getErrors());
    }


    /**
     * @covers Mumsys_Html_Table::setTableProps
     */
    public function testSetTableProps()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setHeadlines
     * @todo   Implement testSetHeadlines().
     */
    public function testSetHeadlines()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setHeadlinesAttributes
     * @todo   Implement testSetHeadlinesAttributes().
     */
    public function testSetHeadlinesAttributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getHeadlinesAttributes
     * @todo   Implement testGetHeadlinesAttributes().
     */
    public function testGetHeadlinesAttributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setHeadlinesAttribute
     * @todo   Implement testSetHeadlinesAttribute().
     */
    public function testSetHeadlinesAttribute()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getHeadlinesAttribute
     * @todo   Implement testGetHeadlinesAttribute().
     */
    public function testGetHeadlinesAttribute()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::addHeadlines
     * @todo   Implement testAddHeadlines().
     */
    public function testAddHeadlines()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setAutoFill
     * @todo   Implement testSetAutoFill().
     */
    public function testSetAutoFill()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setContent
     * @todo   Implement testSetContent().
     */
    public function testSetContent()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getNumCols
     * @todo   Implement testGetNumCols().
     */
    public function testGetNumCols()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getNumRows
     * @todo   Implement testGetNumRows().
     */
    public function testGetNumRows()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setAltRowColor
     * @todo   Implement testSetAltRowColor().
     */
    public function testSetAltRowColor()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getAltRowColor
     * @todo   Implement testGetAltRowColor().
     */
    public function testGetAltRowColor()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getHeadlines
     * @todo   Implement testGetHeadlines().
     */
    public function testGetHeadlines()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setColAttributes
     * @todo   Implement testSetColAttributes().
     */
    public function testSetColAttributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getColAttributes
     * @todo   Implement testGetColAttributes().
     */
    public function testGetColAttributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setRowAttributes
     * @todo   Implement testSetRowAttributes().
     */
    public function testSetRowAttributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getRowAttributes
     * @todo   Implement testGetRowAttributes().
     */
    public function testGetRowAttributes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::setColContents
     * @todo   Implement testSetColContents().
     */
    public function testSetColContents()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getColContents
     * @todo   Implement testGetColContents().
     */
    public function testGetColContents()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getHtml
     * @todo   Implement testGetHtml().
     */
    public function testGetHtml()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::getSource
     * @todo   Implement testGetSource().
     */
    public function testGetSource()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Html_Table::toHtml
     * @todo   Implement testToHtml().
     */
    public function testToHtml()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}