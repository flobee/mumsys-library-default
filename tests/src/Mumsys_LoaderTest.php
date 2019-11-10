<?php

/**
 * Test class A
 */
class Mumsys_LoaderTestClassA
    extends Mumsys_Loader
{
    public function require( string $location ): bool
    {
        return parent::_require( $location );
    }
    public function include( string $location ): bool
    {
        return parent::_include( $location );
    }
}


/**
 * Mumsys_Loader Tests.
 */
class Mumsys_LoaderTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Loader
     */
    private $_object;

    /**
     * @var string
     */
    private $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_version = '3.2.2';
        $this->_object = new Mumsys_Loader;
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
     * @covers Mumsys_Loader::load
     */
    public function testLoad()
    {
        $o3 = $this->_object->load( 'Mumsys_Timer' );
        $this->assertInstanceof( 'Mumsys_Timer', $o3 );

        $this->expectException( 'Mumsys_Loader_Exception' );
        $this->expectExceptionMessageRegExp( '/(Could not load: "unittest")/i' );
        $this->_object->load( 'unittest' );
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoadException1()
    {
        $this->expectExceptionMessageRegExp( '/(Could not load: "Mumsys_NoExists".)/' );
        $this->expectException( 'Mumsys_Exception' );
        $o4 = $this->_object->load( 'Mumsys_NoExists', array() );
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoadException2()
    {
        $this->expectExceptionMessageRegExp( '/(Could not load: "Mumsys_Templates_Base".)/' );
        $this->expectException( 'Mumsys_Exception' );
        $this->_object->load( 'Mumsys_Templates_Base', array() );
    }


    /**
     * @covers Mumsys_Loader::autoload
     * @covers Mumsys_Loader::_require
     * @covers Mumsys_Loader::loadedClassesGet
     */
    public function testAutoload()
    {
        $this->_object->autoload( 'Mumsys_Counter' );

        $this->assertTrue( class_exists( 'Mumsys_Counter', $autoload = false ) );

        $loadedList = $this->_object->loadedClassesGet();
        $expectedList = array('Mumsys_Counter', 'Mumsys_Timer', 'Mumsys_Abstract');

        $this->assertTrue(
            $this->_checkClassList( $loadedList, $expectedList ),
            'Check class list failed'
        );

        $actualC = $this->_object->autoload( 'Mumsys_NotExists' ); // 4CC
        $this->assertFalse( $actualC );
    }


    /**
     * @covers Mumsys_Loader::loadedClassesGet
     */
    public function testLoadedClassesGet()
    {
        $loadedList = $this->_object->loadedClassesGet( '' );
        $expectedList = array('Mumsys_Counter', 'Mumsys_Timer', 'Mumsys_Abstract');

        $actual = $this->_checkClassList( $loadedList, $expectedList );
        $this->assertTrue( $actual, 'Check class list failed' );
    }


    /**
     * @covers Mumsys_Loader::_require
     */
    public function test_require()
    {
        $objA = new Mumsys_LoaderTestClassA();
        $location = MumsysTestHelper::getTestsBaseDir() . '/../src/Mumsys_Timer.php';
        $actualA = $objA->require( $location );

        $this->assertTrue( $actualA );
    }


    /**
     * @covers Mumsys_Loader::_include
     */
    public function test_include()
    {
        $objA = new Mumsys_LoaderTestClassA();
        $location = MumsysTestHelper::getTestsBaseDir() . '/../src/Mumsys_Timer.php';
        $actualA = $objA->include( $location );

        $this->assertTrue( $actualA );
    }


    public function testVersion()
    {
        $this->assertEquals( $this->_version, Mumsys_Loader::VERSION );
    }

}
