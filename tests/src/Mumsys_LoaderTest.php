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
    protected function setUp()
    {
        $this->_version = '3.1.2';
        $this->object = new Mumsys_Loader;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object = null;
    }

    public function testLoad()
    {
        $o3 = $this->object->load('Mumsys_Timer');
        $this->assertInstanceof('Mumsys_Timer', $o3);

        $this->setExpectedExceptionRegExp('Mumsys_Loader_Exception', '/(Could not load: "unittest")/i');
        $this->object->load('unittest');
    }

    public function testLoadException1()
    {
        $this->setExpectedExceptionRegExp('Mumsys_Exception', '/(Could not load: "Mumsys_NoExists".)/');
        $o4 = $this->object->load('Mumsys_NoExists', array());
    }

    public function testLoadException2()
    {
        $this->setExpectedExceptionRegExp('Mumsys_Exception', '/(Could not load: "Mumsys_Templates_Base".)/');
        $o4 = $this->object->load('Mumsys_Templates_Base', array());
    }

    public function testAutoload()
    {
        $this->object->autoload('Mumsys_Timer');
        $this->assertTrue(class_exists('Mumsys_Timer', $autoload = true));
    }

    public function testLoadedClassesGet()
    {
        $actual = $this->object->loadedClassesGet();
        $expected = array('Mumsys_Timer' => 'Mumsys_Timer');

        $this->assertEquals($expected['Mumsys_Timer'], $actual['Mumsys_Timer']);
    }

    public function testGetVersionID()
    {
        $this->assertEquals($this->_version, Mumsys_Loader::VERSION);
    }

}
