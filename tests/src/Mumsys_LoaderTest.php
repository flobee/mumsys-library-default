<?php

/**
 * Test class for Mumsys_Loader.
 */
class Mumsys_LoaderTest extends PHPUnit_Framework_TestCase
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
        $o2 = $this->object->load('unittest');
        $o3 = $this->object->load('Mumsys_Timer');

        $this->assertInstanceof('unittest', $o2);
        $this->assertInstanceof('Mumsys_Timer', $o3);
    }


    public function testLoadException1()
    {
        $this->setExpectedException('Mumsys_Exception', 'Error! could not load: "Mumsys_NoExists".');
        $o4 = $this->object->load('Mumsys_NoExists', array());
    }


    public function testLoadException2()
    {
        $this->setExpectedException('Mumsys_Exception', 'Error! could not load: "Mumsys_Templates_Base".');
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
        $expected = array();

        $this->assertEquals(is_array($expected), is_array($actual));
    }


}
