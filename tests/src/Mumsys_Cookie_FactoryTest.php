<?php

/**
 * Test class for these tests
 */
class Mumsys_Cookie_FactoryMissingIfaceTest
{
    const VERSION = '0.0.0';
}


/**
 * Mumsys_Cookie_FactoryTest
 */
class Mumsys_Cookie_FactoryTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Cookie_Factory
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_object = new Mumsys_Cookie_Factory;
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
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapter()
    {
        $actual1 = $this->_object->getAdapter( 'Default' );
        $actual2 = $this->_object->getAdapter( 'Memory' );

        $this->assertingInstanceOf( 'Mumsys_Cookie_Interface', $actual1 );
        $this->assertingInstanceOf( 'Mumsys_Cookie_Interface', $actual2 );

        $this->expectingException( 'Mumsys_Cookie_Exception' );
        $this->_object->getAdapter( 'NoAdapter' );
    }


    /**
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapterException1NoALNUM()
    {
        $this->expectingException( 'Mumsys_Cookie_Exception' );
        $regex = '/(Invalid characters in adapter name "Mumsys_Cookie_12\$\&3")/i';
        $this->expectingExceptionMessageRegex( $regex );
        $this->_object->getAdapter( '12$&3' );
    }


    /**
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapterException1MissingIface()
    {
        $this->expectingException( 'Mumsys_Cookie_Exception' );
        $regex = '/(Adapter "Mumsys_Cookie_FactoryMissingIfaceTest" does not '
            . 'implement interface "Mumsys_Cookie_Interface")/i';
        $this->expectingExceptionMessageRegex( $regex );
        $this->_object->getAdapter( 'FactoryMissingIfaceTest' );
    }


    /**
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapterException2NotAvailable()
    {
        $this->expectingException( 'Mumsys_Cookie_Exception' );
        $regex = '/(Adapter "Mumsys_Cookie_12345" not available)/i';
        $this->expectingExceptionMessageRegex( $regex );
        $this->_object->getAdapter( '12345' );
    }

}
