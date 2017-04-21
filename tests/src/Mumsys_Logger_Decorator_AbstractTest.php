<?php

/**
 *  Test class for tests
 */
class Mumsys_Logger_Decorator_AbstractTestTestClass
    extends Mumsys_Logger_Decorator_Abstract
{
}


/**
 * Mumsys_Logger_Decorator_Abstract Test
 */
class Mumsys_Logger_Decorator_AbstractTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Logger_Decorator_Abstract
     */
    protected $_object;

    private $_testsDir;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_testsDir = MumsysTestHelper::getTestsBaseDir();
        $this->_logfile = $this->_testsDir . '/tmp/' . basename(__FILE__) .'.test';

        $this->_opts = $opts = array(
            'logfile' => $this->_logfile,
            'way' => 'a',
            'logLevel' => 7,
            'msglogLevel' => 999,
            'maxfilesize' => 1024 * 2,
            'msgLineFormat' => '%5$s',
        );
        $this->_logger = new Mumsys_Logger_File($this->_opts);

        $this->_object = new Mumsys_Logger_Decorator_AbstractTestTestClass($this->_logger);
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