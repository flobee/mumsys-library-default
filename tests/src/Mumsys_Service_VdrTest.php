<?php


/**
 * Mumsys_Service_VdrTest
 */
class Mumsys_Service_VdrTest
    extends Mumsys_Unittest_Testcase
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

        try
        {
            $this->_object = new Mumsys_Service_Vdr($this->_context);
        }
        catch ( Exception $ex ) {
            $this->_object->disconnect();
            $this->markTestSkipped(
                'Service error or not available, skip test. Message: ' . $ex->getMessage()
            );
        }

    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object->disconnect();

        if ( file_exists($this->_logfile) ) {
            //unlink($this->_logfile);
        }

        $this->_object = null;
    }


    /**
     * @covers Mumsys_Service_Vdr::connect
     * @covers Mumsys_Service_Vdr::__construct
     */
    public function testConnect()
    {
         $this->_object = new Mumsys_Service_Vdr($this->_context);

        $actual1 = $this->_object->connect();
        $actual2 = $this->_object->disconnect();
        $actual3 = $this->_object->connect();

        $this->assertTrue($actual1);
        $this->assertTrue($actual2);
        $this->assertTrue($actual3);
    }


    /**
     * @covers Mumsys_Service_Vdr::__destruct
     */
    public function test__destruct()
    {
        $actual2 = $this->_object->__destruct();
        $actual3 = $this->_object->isOpen();

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
     * Expect one or more existing channels.
     *
     * @covers Mumsys_Service_Vdr::execute
     */
    public function testExecute()
    {
        $actual2 = $this->_object->execute('SCAN');
        $expected2 = array('EPG scan triggered');

        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @covers Mumsys_Service_Vdr::channelsGet
     * @covers Mumsys_Service_Vdr::channelGet
     * @covers Mumsys_Service_Vdr::channelsSearch
     * @covers Mumsys_Service_Vdr::_channelsGet
     */
    public function testChannelsGet()
    {
        $channelsList = $this->_object->channelsGet();
        if ( count($channelsList) <= 0 ) {
            $this->markTestSkipped('No channels found. Pls check your vdr config. Skip test');
        }

        foreach ( $channelsList as $id => $parts ) {
            $current = $this->_object->channelGet($id);

            $this->assertEquals($current, $parts);
        }

        $chanSearch = $this->_object->channelsSearch('sat');
        if ( count($chanSearch) <= 0 ) {
            $this->markTestSkipped('No channels found. Pls check the search option in ' . __METHOD__ . '');
        }

        foreach ( $chanSearch as $id => $parts ) {
            $this->assertEquals($channelsList[$id], $parts);
        }
    }


    /**
     * @covers Mumsys_Service_Vdr::timersGet
     * @todo   Implement testTimersGet().
     */
    public function testTimersGet()
    {

    }


    /**
     * @covers Mumsys_Service_Vdr::timerRecordGet
     * @todo   Implement testTimerRecordGet().
     */
    public function testTimerRecordGet()
    {

    }


    /**
     * @covers Mumsys_Service_Vdr::timerStringGet
     * @todo   Implement testTimerStringGet().
     */
    public function testTimerStringGet()
    {

    }


    /**
     * @covers Mumsys_Service_Vdr::timerString2RecordGet
     * @todo   Implement testTimerString2RecordGet().
     */
    public function testTimerString2RecordGet()
    {

    }


    /**
     * @covers Mumsys_Service_Vdr::timerRecord2StringGet
     * @todo   Implement testTimerRecord2StringGet().
     */
    public function testTimerRecord2StringGet()
    {

    }


    /**
     * @covers Mumsys_Service_Vdr::recordingsGet
     * @todo   Implement testRecordingsGet().
     */
    public function testRecordingsGet()
    {

    }


    /**
     * @covers Mumsys_Service_Vdr::isOpen
     * @todo   Implement testIsOpen().
     */
    public function testIsOpen()
    {

    }

}
