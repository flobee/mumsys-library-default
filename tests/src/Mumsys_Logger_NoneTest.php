<?php

/**
 * Mumsys_Logger_None Test
 */
class Mumsys_Logger_NoneTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Logger_None
     */
    protected $_object;

    /**
     * Version string.
     * @var string
     */
    protected $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '3.0.1';
        $this->_object = new Mumsys_Logger_None();
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
     * @covers Mumsys_Logger_None::log
     */
    public function testLog()
    {
        $actual1 = $this->_object->log( 'test', 3 );
        $expected1 = date( 'Y-m-d H:i:s', time() ) . ' [flobee] [ERR](3) test' . "\n";

        $actual2 = $this->_object->log( array('test1', 'test2'), 3 );
        $expected2 = date( 'Y-m-d H:i:s', time() ) . ' [flobee] [ERR](3) ["test1","test2"]' . "\n";

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
    }


    /**
     * VERSION check
     */
    public function testVersion()
    {
        $this->assertEquals( $this->_version, Mumsys_Logger_None::VERSION );
    }

}
