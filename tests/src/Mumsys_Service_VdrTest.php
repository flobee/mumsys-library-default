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
            $this->_object = new Mumsys_Service_Vdr($this->_context, 'localhost');
        }
        catch ( Exception $ex ) {
            $message = 'Service error or not available, skip test. Message: ' . $ex->getMessage();
            $this->markTestSkipped($message);
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
        $this->_object = new Mumsys_Service_Vdr($this->_context);

        $this->assertTrue($actual2);
        $this->assertInstanceOf('Mumsys_Service_Vdr', $this->_object);
    }

//
//    /**
//     * @covers Mumsys_Service_Vdr::__destruct
//     */
//    public function test__destruct()
//    {
//        $actual2 = $this->_object->__destruct();
//        $actual3 = $this->_object->isOpen();
//
//        $this->assertTrue($actual2);
//        $this->assertFalse($actual3);
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::connect
//     */
//    public function testConnect()
//    {
//        $actual1 = $this->_object->connect();
//        $actual2 = $this->_object->disconnect();
//        $actual3 = $this->_object->connect();
//
//        $this->assertTrue($actual1);
//        $this->assertTrue($actual2);
//        $this->assertTrue($actual3);
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::connect
//     */
//    public function testConnectException1()
//    {
//        $regex = '/(Connection to server "nohostexist" failt)/i';
//        $this->setExpectedExceptionRegExp('Mumsys_Service_Exception', $regex);
//        $this->_object = new Mumsys_Service_Vdr($this->_context, 'nohostexist', 666, 5);
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::disconnect
//     */
//    public function testDisconnect()
//    {
//        $actual1 = $this->_object->connect();
//        $actual2 = $this->_object->disconnect();
//
//        $this->assertTrue($actual1);
//        $this->assertTrue($actual2);
//    }
//
//
//    /**
//     * Expect one or more existing channels.
//     *
//     * @covers Mumsys_Service_Vdr::execute
//     */
//    public function testExecute()
//    {
//        $actual1 = $this->_object->execute('SCAN');
//        $expected1 = array('EPG scan triggered');
//
//        // epg data channel 2
//        $actual2 = $this->_object->execute('LSTE', 2);
//
//        // list some recordings. this can end up in a tomeout first because vdr
//        // caches the results which can be a huge list
//        $actual3 = $this->_object->execute('LSTR', 1);
//
//        $this->assertEquals($expected1, $actual1);
//        $this->assertTrue( (count($actual2) > 2 ));
//        $this->assertTrue( (count($actual3) == 1 ));
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::execute
//     */
//    public function testExecuteException1()
//    {
//        $this->_object->disconnect();
//        $regex = '/(Not connected)/i';
//        $this->setExpectedExceptionRegExp('Mumsys_Service_Exception', $regex);
//
//        $this->_object->execute('SCAN');
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::execute
//     */
//    public function testExecuteException2()
//    {
//        $regex = '/(Command unknown or not implemented yet. Exiting)/i';
//        $this->setExpectedExceptionRegExp('Mumsys_Service_Exception', $regex);
//
//        $this->_object->execute('ImACommandThatNotExists');
//    }

    /**
     * @covers Mumsys_Service_Vdr::channelAdd
     */
    public function testChannelAdd()
    {
        // $str = 'TestChannel;NDR:000000001:TESTCHANNEL:T:0:1=2:3=deu@3,4=mis@4:5:6:7:8:9:0';
        $actual = $this->_object->channelAdd('TestChannel', 'NDR', '000000001', 'TESTCHANNEL', 'T', 0, '1=2', '3=deu@3,4=mis@4', '5', '6', '7', '8', '9', '0');

        $expected = array();

        $this->assertEquals($expected, $actual);
xxx here we are
        //$this->_object->channelDelete($actual['id']);
    }

    /**
     * @covers Mumsys_Service_Vdr::channelsGet
     * @covers Mumsys_Service_Vdr::channelGet
     * @covers Mumsys_Service_Vdr::channelSearch
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

        $chanSearch = $this->_object->channelSearch('sat');
        if ( count($chanSearch) <= 0 ) {
            $this->markTestSkipped('No channels found. Pls check the search option in ' . __METHOD__ . '');
        }

        foreach ( $chanSearch as $id => $parts ) {
            $this->assertEquals($channelsList[$id], $parts);
        }

        $regex = '/(Invalid channel parameter)/i';
        $this->setExpectedExceptionRegExp('Mumsys_Service_Exception', $regex);
        $this->_object->channelSearch(0);
    }





    /**
     * @covers Mumsys_Service_Vdr::recordingGet
     */
    public function testRecordingGet()
    {
        $expectedKeys = array(
            'channel_key'
            ,'channel_name'
            ,'event_id'
            ,'timestamp'
            ,'duration'
            ,'e_tableid'
            ,'e_version'
            ,'description'
            ,'subtitle'
            ,'title'
            //optional ,'advisory'
            //optional ,'vps'
            ,'genre'
            //optional ,'extras'
            ,'framerate'
            ,'priority'
            ,'lifetime'
            //optional ,'notes'
        );

        $actual1 = $this->_object->recordingGet(1, false);

        foreach ( $expectedKeys as $key ) {
            if ( $key == 'extras' ) {
                $extras = array('stream_kind', 'stream_type', 'stream_lang', 'stream_desc');
                foreach ( $extras as $extr ) {
                    $acEx = reset($actual1['extras']);
                    $this->assertTrue( isset($acEx[$extr]));
                }
            }

            $this->assertTrue( isset($actual1[$key]), 'Error: "'. $key . '" not set' );
        }

        $actual2 = $this->_object->recordingGet(1, true);
        $this->assertTrue(is_dir($actual2 . '/'), 'Directory "' . $actual2 . '" not found');

        $regex = '/(Invalid recording ID)/i';
        $this->setExpectedExceptionRegExp('Mumsys_Service_Exception', $regex);
        $this->_object->recordingGet(0);
    }

    /**
     * @covers Mumsys_Service_Vdr::recordingsGet
     */
    public function testRecordingsGet()
    {

    }



//
//    /**
//     * @covers Mumsys_Service_Vdr::timersGet
//     */
//    public function testTimersGet()
//    {
//
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::timerRecordGet
//     */
//    public function testTimerRecordGet()
//    {
//
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::_timerStringGet
//     */
//    public function testTimerStringGet()
//    {
//
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::timerString2RecordGet
//     */
//    public function testTimerString2RecordGet()
//    {
//
//    }
//
//
//    /**
//     * @covers Mumsys_Service_Vdr::timerArray2StringGet
//     */
//    public function testTimerArray2StringGet()
//    {
//
//    }


    /**
     * @covers Mumsys_Service_Vdr::isOpen
     * @todo   Implement testIsOpen().
     */
    public function testIsOpen()
    {

    }

}
