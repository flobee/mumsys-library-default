<?php


/**
 * Test class for Mumsys_Timer.
 */
class Mumsys_TimerTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Timer
     */
    protected $_object;


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
        $this->assertInstanceof( 'Mumsys_Timer', $this->_object );
        $this->assertingEquals( 0, $this->_object->startTimeGet() );
        $this->assertingEquals( 0, $this->_object->stopTimeGet() );

        $this->_object = new Mumsys_Timer( true );
        $this->assertInstanceof( 'Mumsys_Timer', $this->_object );
        $this->assertingEquals( 0, $this->_object->stopTimeGet() );
        $this->assertGreaterThan( 0, $this->_object->startTimeGet() );

        $this->_object = new Mumsys_Timer( microtime( 1 ) );
        $this->_object->stop();

        $this->assertingTrue( ( ( $this->_object->stopTimeGet() - $this->_object->startTimeGet() ) < 0.08 ) );
    }


    public function testStart()
    {
        $this->_object->start();
        $expected = microtime( 1 );
        $actual = $this->_object->startTimeGet();
        $this->assertingTrue( ( (int)$expected == (int)$actual ) );
        //echo PHP_EOL.$expected.PHP_EOL.$actual. PHP_EOL;
        //echo round($expected, 3) . PHP_EOL . round($actual, 3) . PHP_EOL;
        $this->assertingEquals( round( $expected, 2 ), round( $actual, 2 ) );
    }


    public function testStop()
    {
        $this->_object->stop();
        $expected = microtime( 1 );
        $actual = $this->_object->stopTimeGet();

        $this->assertingEquals( round( $expected, 2 ), round( $actual, 2 ) );
    }


    public function testStartTimeGet()
    {
        $this->assertingEquals( 0, $this->_object->startTimeGet() );
    }


    public function testStopTimeGet()
    {
        $this->assertingEquals( 0, $this->_object->stopTimeGet() );
    }


    public function testElapsedTimeGet()
    {
        $this->assertingEquals( 0, $this->_object->elapsedTimeGet() );
    }


    public function test__ToString()
    {
        $this->_object->__toString();
        $expected = microtime( 1 );
        $actual = $this->_object->stopTimeGet();

        $this->assertingEquals( round( $expected, 2 ), round( $actual, 2 ) );
    }


    // Abstract class

    public function testGetVersion()
    {
        $this->assertingEquals( 'Mumsys_Timer 3.2.0', $this->_object->getVersion() );
        $this->assertingEquals( '3.2.0', $this->_object->getVersionID() );
    }

}
