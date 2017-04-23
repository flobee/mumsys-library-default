<?php

/**
 *  Test class for tests
 */
class Mumsys_Logger_Decorator_AbstractTestTestClass extends Mumsys_Logger_Decorator_Abstract
{

}


/**
 * Mumsys_Logger_Decorator_Abstract Test
 */
class Mumsys_Logger_Decorator_AbstractTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Logger_Decorator_Abstract
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Logger_Decorator_AbstractTestTestClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object =null;
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::__clone
     */
    public function test__clone()
    {
        $obj = clone $this->_object;
        $this->assertInstanceOf('Mumsys_Logger_Decorator_Interface', $obj);
        $this->assertInstanceOf('Mumsys_Logger_Decorator_Interface', $this->_object);
        $this->assertNotSame($obj, $this->_object);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::log
     * @todo   Implement testLog().
     */
    public function testLog()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::emerg
     * @todo   Implement testEmerg().
     */
    public function testEmerg()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::alert
     * @todo   Implement testAlert().
     */
    public function testAlert()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::crit
     * @todo   Implement testCrit().
     */
    public function testCrit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::err
     * @todo   Implement testErr().
     */
    public function testErr()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::warn
     * @todo   Implement testWarn().
     */
    public function testWarn()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::notice
     * @todo   Implement testNotice().
     */
    public function testNotice()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::info
     * @todo   Implement testInfo().
     */
    public function testInfo()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::debug
     * @todo   Implement testDebug().
     */
    public function testDebug()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Logger_Decorator_Abstract::checkLevel
     * @todo   Implement testCheckLevel().
     */
    public function testCheckLevel()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}