<?php

/**
 * Mumsys_Cookie_None Test
 */
class Mumsys_Cookie_NoneTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Cookie_None
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_object = new Mumsys_Cookie_None;
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        unset( $this->_object );
    }


    /**
     * Test 4 CC
     * @covers Mumsys_Cookie_None::__construct
     */
    public function test_constructor()
    {
        $this->_object = new Mumsys_Cookie_None();

        $this->assertingInstanceOf( 'Mumsys_Cookie_Interface', $this->_object );
    }


    /**
     * @covers Mumsys_Cookie_None::getCookie
     * @covers Mumsys_Cookie_None::setCookie
     * @covers Mumsys_Cookie_None::setRawCookie
     * @covers Mumsys_Cookie_None::unsetCookie
     */
    public function testGetSetCookie()
    {
        $actual1 = $this->_object->getCookie();
        $actual2 = $this->_object->setCookie( '1', '2' );
        $actual3 = $this->_object->setRawCookie( '1', '2' );
        $actual4 = $this->_object->unsetCookie( '1' );

        $this->assertNull( $actual1 );
        $this->assertingTrue( $actual2 );
        $this->assertingTrue( $actual3 );
        $this->assertingTrue( $actual4 );
    }


    /**
     * @covers Mumsys_Cookie_None::clear
     */
    public function testClear()
    {
        $actual = $this->_object->clear();

        $this->assertingTrue( $actual );
    }

}
