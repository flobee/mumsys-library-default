<?php

/**
 * Test class for Mumsys_Loader.
 */
class Mumsys_LoaderTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Loader
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_version = '3.2.2';
        $this->object = new Mumsys_Loader;
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->object = null;
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoad()
    {
        $o3 = $this->object->load( 'Mumsys_Timer' );
        $this->assertInstanceof( 'Mumsys_Timer', $o3 );

        $this->expectException( 'Mumsys_Loader_Exception' );
        $this->expectExceptionMessageRegExp( '/(Could not load: "unittest")/i' );
        $this->object->load( 'unittest' );
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoadException1()
    {
        $this->expectExceptionMessageRegExp( '/(Could not load: "Mumsys_NoExists".)/' );
        $this->expectException( 'Mumsys_Exception' );
        $o4 = $this->object->load( 'Mumsys_NoExists', array() );
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoadException2()
    {
        $this->expectExceptionMessageRegExp( '/(Could not load: "Mumsys_Templates_Base".)/' );
        $this->expectException( 'Mumsys_Exception' );
        $o4 = $this->object->load( 'Mumsys_Templates_Base', array() );
    }


    /**
     * @covers Mumsys_Loader::autoload
     * @covers Mumsys_Loader::loadedClassesGet
     */
    public function testAutoload()
    {
        $this->object->autoload( 'Mumsys_Counter' );

        $this->assertTrue( class_exists( 'Mumsys_Counter', $autoload = false ) );

        $loadedList = $this->object->loadedClassesGet();
        $expectedList = array('Mumsys_Counter', 'Mumsys_Timer', 'Mumsys_Abstract');

        $this->assertTrue(
            $this->_checkClassList( $loadedList, $expectedList ),
            'Check class list failed'
        );
    }


    /**
     * @covers Mumsys_Loader::loadedClassesGet
     */
    public function testLoadedClassesGet()
    {
        $loadedList = $this->object->loadedClassesGet( '' );
        $expectedList = array('Mumsys_Counter', 'Mumsys_Timer', 'Mumsys_Abstract');

        $actual = $this->_checkClassList( $loadedList, $expectedList );
        $this->assertTrue( $actual, 'Check class list failed' );
    }


    public function testVersion()
    {
        $this->assertEquals( $this->_version, Mumsys_Loader::VERSION );
    }

}
