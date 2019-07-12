<?php

/**
 * Mumsys_Cookie_Memory test
 */
class Mumsys_Cookie_MemoryTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Cookie_Memory
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_object = new Mumsys_Cookie_Memory();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->_object = null;
    }


    /**
     * Test 4 CC
     * @covers Mumsys_Cookie_Memory::__construct
     */
    public function test_constructor()
    {
        $this->_object = new Mumsys_Cookie_Memory();

        $this->assertInstanceOf( 'Mumsys_Cookie_Interface', $this->_object );
    }


    /**
     * @covers Mumsys_Cookie_Memory::getCookie
     * @covers Mumsys_Cookie_Memory::setCookie
     * @covers Mumsys_Cookie_Memory::setRawCookie
     */
    public function testGetSetCookie()
    {
        $actual = $this->_object->getCookie();

        $this->_object->setCookie( 'a', 'b' );
        $this->_object->setRawCookie( 'rawkey', 'rawval' );

        $this->assertEquals( array(), $actual );
        $this->assertEquals( 'b', $this->_object->getCookie( 'a' ) );
        $this->assertEquals( 'rawval', $this->_object->getCookie( 'rawkey' ) );
    }


    /**
     * @covers Mumsys_Cookie_Memory::clear
     * @covers Mumsys_Cookie_Memory::unsetCookie
     */
    public function testClear()
    {
        $this->_object->setCookie( 'a', 'b' );
        $actual = $this->_object->unsetCookie( 'a' );
        $this->_object->clear();

        $this->assertTrue( $actual );
        $this->assertTrue( is_array( $this->_object->getCookie() ) );
        $this->assertTrue( empty( $this->_object->getCookie() ) );
    }

}
