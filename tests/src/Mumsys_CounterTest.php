<?php

/**
 * Test class for Mumsys_Counter.
 */
class Mumsys_CounterTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_CounterTest
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '3.1.0';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_Counter' => $this->_version,
        );
        $this->_object = new Mumsys_Counter();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


    public function testConstructor()
    {
        $this->_object = new Mumsys_Counter();
        $this->assertInstanceof( 'Mumsys_Counter', $this->_object );
        $this->assertEquals( 0, $this->_object->result() );

        $this->_object = new Mumsys_Counter( true );
        $this->assertInstanceof( 'Mumsys_Counter', $this->_object );
        $this->assertEquals( 1, $this->_object->result() );
    }


    public function testAdd()
    {
        $this->_object->add( 1 );
        $this->assertEquals( 1, $this->_object->result() );

        $this->_object->add( -1 );
        $this->assertEquals( 2, $this->_object->result() );
    }


    public function testSub()
    {
        $this->_object->sub( 1 );
        $this->assertEquals( -1, $this->_object->result() );

        $this->_object->sub( -1 );
        $this->assertEquals( -2, $this->_object->result() );

        $this->_object->sub( 1.5 ); // positiv number: -2 -> sub 1.5 = -3.5
        $this->assertEquals( -3.5, $this->_object->result() );
    }


    public function testCount()
    {
        $this->_object->count();
        $this->assertEquals( 1, $this->_object->result() );
    }


    public function testResult()
    {
        $this->assertEquals( 0, $this->_object->result() );
    }


    public function testtoString()
    {
        $this->assertEquals( 0, $this->_object->__toString() );
    }

    // test abstracts


    /**
     * @covers Mumsys_Counter::getVersion
     */
    public function testGetVersion()
    {
        $this->assertEquals( 'Mumsys_Counter ' . $this->_version, $this->_object->getVersion() );
    }


    /**
     * @covers Mumsys_Counter::getVersionID
     */
    public function testgetVersionID()
    {
        $this->assertEquals( $this->_version, $this->_object->getVersionID() );
    }


    /**
     * @covers Mumsys_Counter::getVersions
     */
    public function testgetVersions()
    {
        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $this->assertTrue( isset( $possible[$must] ) );
            $this->assertTrue( ($possible[$must] == $value ) );
        }
    }

}
