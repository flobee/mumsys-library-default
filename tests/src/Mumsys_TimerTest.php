<?php


/**
 * Test class for Mumsys_Timer.
 */
class Mumsys_TimerTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_TimerTest
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Timer();
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
        $this->_object = new Mumsys_Timer();
        $this->assertInstanceof('Mumsys_Timer', $this->_object);
        $this->assertEquals(0, $this->_object->startTimeGet());
        $this->assertEquals(0, $this->_object->stopTimeGet());

        $this->_object = new Mumsys_Timer(true);
        $this->assertInstanceof('Mumsys_Timer', $this->_object);
        $this->assertEquals(0, $this->_object->stopTimeGet());
        $this->assertGreaterThan(0, $this->_object->startTimeGet());

        $this->_object = new Mumsys_Timer($_SERVER['REQUEST_TIME_FLOAT']);
        $this->assertEquals($_SERVER['REQUEST_TIME_FLOAT'], $this->_object->startTimeGet());
    }


    public function testStart()
    {
        $this->_object->start();
        $expected = microtime(1);
        $actual = $this->_object->startTimeGet();
        $this->assertTrue(((int)$expected == (int)$actual));
        //echo PHP_EOL.$expected.PHP_EOL.$actual. PHP_EOL;
        //echo round($expected, 3) . PHP_EOL . round($actual, 3) . PHP_EOL;
        $this->assertEquals(round($expected, 2), round($actual, 2));
    }


    public function testStop()
    {
        $this->_object->stop();
        $expected = microtime(1);
        $actual = $this->_object->stopTimeGet();

        $this->assertEquals(round($expected, 2), round($actual, 2));
    }


    public function testStartTimeGet()
    {
        $this->assertEquals(0, $this->_object->startTimeGet());
    }


    public function testStopTimeGet()
    {
        $this->assertEquals(0, $this->_object->stopTimeGet());
    }


    public function testElapsedTimeGet()
    {
        $this->assertEquals(0, $this->_object->elapsedTimeGet());
    }


    public function test__ToString()
    {
        $this->_object->__toString();
        $expected = microtime(1);
        $actual = $this->_object->stopTimeGet();

        $this->assertEquals(round($expected, 2), round($actual, 2));
    }


    // Abstract class

    public function testGetVersion()
    {
        $this->assertEquals('Mumsys_Timer 3.2.0', $this->_object->getVersion());
        $this->assertEquals('3.2.0', $this->_object->getVersionID());
    }

}