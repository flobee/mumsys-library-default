<?php

class Mumsys_Cookie_MissingIfaceTest
{

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
    protected function setUp()
    {
        $this->_object = new Mumsys_Cookie_Factory;
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
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapter()
    {
        $actual1 = $this->_object->getAdapter( 'Default' );
        $actual2 = $this->_object->getAdapter( 'Memory' );

        $this->assertInstanceOf( 'Mumsys_Cookie_Interface', $actual1 );
        $this->assertInstanceOf( 'Mumsys_Cookie_Interface', $actual2 );

        $this->expectException( 'Mumsys_Cookie_Exception' );
        $this->_object->getAdapter( 'NoAdapter' );
    }


    /**
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapterException1NoALNUM()
    {
        $this->expectException( 'Mumsys_Cookie_Exception' );
        $regex = '/(Invalid characters in adapter name "Mumsys_Cookie_12\$\&3")/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->_object->getAdapter( '12$&3' );
    }


    /**
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapterException1MissingIface()
    {
        $this->expectException( 'Mumsys_Cookie_Exception' );
        $regex = '/(Adapter "Mumsys_Cookie_MissingIfaceTest" does not '
            . 'implement interface "Mumsys_Cookie_Interface")/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->_object->getAdapter( 'MissingIfaceTest' );
    }


    /**
     * @covers Mumsys_Cookie_Factory::getAdapter
     */
    public function testGetAdapterException2NotAvailable()
    {
        $this->expectException( 'Mumsys_Cookie_Exception' );
        $regex = '/(Adapter "Mumsys_Cookie_12345" not available)/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->_object->getAdapter( 12345 );
    }

}
