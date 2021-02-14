<?php declare(strict_types=1);

/**
 * Test class for the mailer factory
 */
class Mumsys_Mail_FactoryMyTestMailer
{
    const VERSION = '0.0.0';
}


/**
 * Mumsys_Mail_Factory Test
 */
class Mumsys_Mail_FactoryTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Mail_Factory
     */
    protected $_object;


    protected function setUp(): void
    {
        $this->_object = new Mumsys_Mail_Factory();
    }


    protected function tearDown(): void
    {
        unset( $this->_object );
    }


    /**
     * @covers Mumsys_Mail_Factory::getAdapter
     */
    public function testGetAdapter()
    {
        $actual1 = $this->_object->getAdapter( 'Default' );
        $actual2 = $this->_object->getAdapter( 'PHPMailer' );

        $this->assertingInstanceOf( 'Mumsys_Mail_Interface', $actual1 );
        $this->assertingInstanceOf( 'Mumsys_Mail_Interface', $actual2 );
        $this->assertingInstanceOf( 'Mumsys_Mail_Default', $actual1 );
        $this->assertingInstanceOf( 'Mumsys_Mail_PHPMailer', $actual2 );
    }

    /**
     * @covers Mumsys_Mail_Factory::getAdapter
     */
    public function testGetAdapterException1()
    {
        $regex = '/(Invalid characters in adapter name "Mumsys_Mail_PHP\$Mailer")/i';
        $this->expectingExceptionMessageRegex( $regex );
        $this->expectingException( 'Mumsys_Mail_Exception' );
        $actual1 = $this->_object->getAdapter( 'PHP$Mailer' );
    }

    /**
     * @covers Mumsys_Mail_Factory::getAdapter
     */
    public function testGetAdapterException2()
    {
        $regex = '/(Adapter "Mumsys_Mail_NoExistsAdapter" not available)/i';
        $this->expectingExceptionMessageRegex( $regex );
        $this->expectingException( 'Mumsys_Mail_Exception' );
        $actual1 = $this->_object->getAdapter( 'NoExistsAdapter' );
    }

    /**
     * @covers Mumsys_Mail_Factory::getAdapter
     */
    public function testGetAdapterException3()
    {
        $regex = '/(Adapter "Mumsys_Mail_FactoryMyTestMailer" does not implement '
            . 'interface "Mumsys_Mail_Interface")/i';
        $this->expectingExceptionMessageRegex( $regex );
        $this->expectingException( 'Mumsys_Mail_Exception' );
        $actual1 = $this->_object->getAdapter( 'FactoryMyTestMailer' );
    }

}
