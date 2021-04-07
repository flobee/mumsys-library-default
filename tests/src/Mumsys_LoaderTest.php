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
        $this->assertingInstanceOf( 'Mumsys_Timer', $o3 );

        $this->expectingException( 'Mumsys_Loader_Exception' );
        $this->expectingExceptionMessageRegex( '/(Could not load: "unittest")/i' );
        $this->_object->load( 'unittest' );
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoadException1()
    {
        $this->expectingExceptionMessageRegex( '/(Could not load: "Mumsys_NoExists".)/' );
        $this->expectingException( 'Mumsys_Exception' );
        $o4 = $this->_object->load( 'Mumsys_NoExists', array() );
    }


    /**
     * @covers Mumsys_Loader::load
     */
    public function testLoadException2()
    {
        $this->expectingExceptionMessageRegex( '/(Could not load: "Mumsys_Templates_Base".)/' );
        $this->expectingException( 'Mumsys_Exception' );
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

        $this->assertingTrue( class_exists( 'Mumsys_Counter', $autoload = false ) );

        $loadedList = $this->_object->loadedClassesGet();
        $expectedList = array('Mumsys_Counter', 'Mumsys_Timer', 'Mumsys_Abstract');

        $this->assertingTrue(
            $this->checkClassList( $loadedList, $expectedList ),
            'Check class list failed'
        );

        $actualC = $this->_object->autoload( 'Mumsys_NotExists' ); // 4CC
        $this->assertingFalse( $actualC );
    }


    /**
     * @covers Mumsys_Loader::loadedClassesGet
     */
    public function testLoadedClassesGet()
    {
        $loadedList = $this->_object->loadedClassesGet( '' );
        $expectedList = array('Mumsys_Counter', 'Mumsys_Timer', 'Mumsys_Abstract');

        $actual = $this->checkClassList( $loadedList, $expectedList );
        $this->assertingTrue( $actual, 'Check class list failed' );
    }


    /**
     * @covers Mumsys_Loader::_require
     */
    public function test_require()
    {
        $objA = new Mumsys_LoaderTestClassA();
        $location = MumsysTestHelper::getTestsBaseDir() . '/../src/Mumsys_Timer.php';
        $actualA = $objA->require( $location );

        $this->assertingTrue( $actualA );
    }


    /**
     * @covers Mumsys_Loader::_include
     */
    public function test_include()
    {
        $objA = new Mumsys_LoaderTestClassA();
        $location = MumsysTestHelper::getTestsBaseDir() . '/../src/Mumsys_Timer.php';
        $actualA = $objA->include( $location );

        $this->assertingTrue( $actualA );
    }


    public function testVersion()
    {
        $this->assertingEquals( $this->_version, Mumsys_Loader::VERSION );
    }

}
