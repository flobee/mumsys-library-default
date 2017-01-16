<?php


/**
 * Mumsys_Service_VdrTest
 */
class Mumsys_Service_VdrTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Service_Vdr
     */
    protected $_object;

    /**
     * Context item with at least the logger object
     * @var Mumsys_Context_Item
     */
    private $_context;
    private $_options;
    private $_logfile;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_context = MumsysTestHelper::getContext();
        $this->_logfile = MumsysTestHelper::getTestsBaseDir() . '/tmp/service_vdr.log';

        try {
            $this->_context->getLogger();
        }
        catch ( Exception $e ) {
            $logOptions = array('logfile' => $this->_logfile);
            $logger = new Mumsys_Logger_File($logOptions);
            $this->_context->registerLogger($logger);
        }

        $this->_options = array();
        $this->_object = new Mumsys_Service_Vdr($this->_context);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if ( file_exists($this->_logfile) ) {
            unlink($this->_logfile);
        }

        $this->_object = null;
    }


    /**
     * @covers Mumsys_Service_Vdr::connect
     */
    public function testConnect()
    {
        $actual1 = $this->_object->connect();

        $this->assertTrue($actual1);
    }


    /**
     * @covers Mumsys_Service_Vdr::__destruct
     */
    public function test__destruct()
    {
        $actual1 = $this->_object->connect();
        $actual2 = $this->_object->__destruct();
        $actual3 = $this->_object->isOpen();

        $this->assertTrue($actual1);
        $this->assertTrue($actual2);
        $this->assertFalse($actual3);
    }


    /**
     * @covers Mumsys_Service_Vdr::disconnect
     */
    public function testDisconnect()
    {
        $actual1 = $this->_object->connect();
        $actual2 = $this->_object->disconnect();

        $this->assertTrue($actual1);
        $this->assertTrue($actual2);
    }


    /**
     * @covers Mumsys_Service_Vdr::execute
     */
    public function testExecute()
    {
        $actual1 = $this->_object->connect();
        $actual2 = $this->_object->execute('LSTC');
        $expected2 = 'Some resut';

        $this->assertTrue($actual1);
        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @covers Mumsys_Service_Vdr::channelsGet
     * @todo   Implement testChannelsGet().
     */
    public function testChannelsGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::timersGet
     * @todo   Implement testTimersGet().
     */
    public function testTimersGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::timerRecordGet
     * @todo   Implement testTimerRecordGet().
     */
    public function testTimerRecordGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::timerStringGet
     * @todo   Implement testTimerStringGet().
     */
    public function testTimerStringGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::timerString2RecordGet
     * @todo   Implement testTimerString2RecordGet().
     */
    public function testTimerString2RecordGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::timerRecord2StringGet
     * @todo   Implement testTimerRecord2StringGet().
     */
    public function testTimerRecord2StringGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::recordingsGet
     * @todo   Implement testRecordingsGet().
     */
    public function testRecordingsGet()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }


    /**
     * @covers Mumsys_Service_Vdr::isOpen
     * @todo   Implement testIsOpen().
     */
    public function testIsOpen()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}
