<?php

/**
 * Mumsys_Cookie_Mock Test
 */
class Mumsys_Cookie_MockTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Cookie_Mock
     */
    protected $_object;

    /**
     * Test cookie file
     * @var string
     */
    private $_testCookieFile;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_testCookieFile = '/tmp/MumsysCookieMock.unittest.tmp';
        $this->_object = new Mumsys_Cookie_Mock;
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        unset( $this->_object );
        if ( file_exists( $this->_testCookieFile ) ) {
            @unlink( $this->_testCookieFile );
        }
    }


    /**
     * @covers Mumsys_Cookie_Mock::__construct
     */
    public function test__construct()
    {
        $actual1 = new Mumsys_Cookie_Mock();

        $location = $this->_testCookieFile;
        $actual2 = new Mumsys_Cookie_Mock( $location );

        $this->assertingInstanceOf( 'Mumsys_Cookie_Interface', $actual1 );
        $this->assertingInstanceOf( 'Mumsys_Cookie_Interface', $actual2 );
    }


    /**
     * @covers Mumsys_Cookie_Mock::__destruct
     */
    public function test__destruct()
    {
        $actual1 = new Mumsys_Cookie_Mock();
        $this->assertingInstanceOf( 'Mumsys_Cookie_Interface', $actual1 );
        $actual1->setCookie( 'key', 'val' );
        // now destruction performs by phpunit for CC
    }


    /**
     * @covers Mumsys_Cookie_Mock::getCookie
     * @covers Mumsys_Cookie_Mock::setCookie
     * @covers Mumsys_Cookie_Mock::setRawCookie
     * @covers Mumsys_Cookie_Mock::_loadCookieData
     */
    public function testGetSetCookie()
    {
        $actual1 = $this->_object->getCookie( null, false );

        $actual2 = $this->_object->getCookie( 'none', false );

        $this->_object->setCookie( 'key', 'val' );
        $this->_object->setRawCookie( 'key', 'val' );
        $actual3 = $this->_object->getCookie( 'key' );

        $this->assertingEquals( array(), $actual1 );
        $this->assertingFalse( $actual2 );
        $this->assertingEquals( 'val', $actual3 );
    }


    /**
     * @covers Mumsys_Cookie_Mock::unsetCookie
     */
    public function testUnsetCookie()
    {
        $this->_object->setCookie( 'key', 'val' );
        $actual1 = $this->_object->unsetCookie( 'key' );
        $actual2 = $this->_object->unsetCookie( 'key' );

        $this->assertingTrue( $actual1 );
        $this->assertingTrue( $actual2 );
    }


    /**
     * @covers Mumsys_Cookie_Mock::clear
     */
    public function testClear()
    {
        $this->_object->setCookie( 'key', 'val' );
        $this->_object->clear();
        $actual1 = $this->_object->getCookie();

        $this->assertingEquals( array(), $actual1 );
    }

}
