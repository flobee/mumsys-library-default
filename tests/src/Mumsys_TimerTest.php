<?php declare( strict_types=1 );

/**
 * Mumsys_TimerTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright (c) 2006 by Florian Blasel
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Timer
 */


/**
 * Test class for Mumsys_Timer.
 */
class Mumsys_TimerTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_TimerTest
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_object = new Mumsys_Timer();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->_object = null;
    }


    public function testConstructor()
    {
        $this->_object = new Mumsys_Timer();
        $this->assertInstanceof( 'Mumsys_Timer', $this->_object );
        $this->assertEquals( 0, $this->_object->startTimeGet() );
        $this->assertEquals( 0, $this->_object->stopTimeGet() );

        $this->_object = new Mumsys_Timer( true );
        $this->assertInstanceof( 'Mumsys_Timer', $this->_object );
        $this->assertEquals( 0, $this->_object->stopTimeGet() );
        $this->assertGreaterThan( 0, $this->_object->startTimeGet() );

        $this->_object = new Mumsys_Timer( $_SERVER['REQUEST_TIME_FLOAT'] );

        $this->assertEquals(
            $_SERVER['REQUEST_TIME_FLOAT'], $this->_object->startTimeGet()
        );
    }


    public function testStart()
    {
        $this->_object->start();
        $expected = microtime( true );
        $actual = $this->_object->startTimeGet();
        $this->assertTrue( ( (int) $expected == (int) $actual ) );
        //echo PHP_EOL.$expected.PHP_EOL.$actual. PHP_EOL;
        //echo round($expected, 3) . PHP_EOL . round($actual, 3) . PHP_EOL;
        $this->assertEquals( round( $expected, 2 ), round( $actual, 2 ) );
    }


    public function testStop()
    {
        $this->_object->stop();
        $expected = microtime( true );
        $actual = $this->_object->stopTimeGet();

        $this->assertEquals( round( $expected, 2 ), round( $actual, 2 ) );
    }


    public function testStartTimeGet()
    {
        $this->assertEquals( 0, $this->_object->startTimeGet() );
    }


    public function testStopTimeGet()
    {
        $this->assertEquals( 0, $this->_object->stopTimeGet() );
    }


    public function testElapsedTimeGet()
    {
        $this->assertEquals( 0, $this->_object->elapsedTimeGet() );
    }


    public function test__ToString()
    {
        $this->_object->__toString();
        $expected = microtime( true );
        $actual = $this->_object->stopTimeGet();

        $this->assertEquals( round( $expected, 1 ), round( $actual, 1 ) );
    }

    // Abstract class

    public function testGetVersion()
    {
        $this->assertEquals( 'Mumsys_Timer 3.2.0', $this->_object->getVersion() );
        $this->assertEquals( '3.2.0', $this->_object->getVersionID() );
    }

}
