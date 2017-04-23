<?php

/**
 * Mumsys_Logger_Decorator_Messages Test
 */
class Mumsys_Logger_Decorator_MessagesTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Logger_Decorator_Messages
     */
    protected $_object;


    protected function setUp()
    {
        $this->_testsDir = realpath(dirname(__FILE__) . '/../');
        $this->_logfile = $this->_testsDir . '/tmp/' . basename(__FILE__) .'.test';

        $this->_opts = $opts = array(
            'logfile' => $this->_logfile,
            'way' => 'a',
            'logLevel' => 7,
            'msglogLevel' => 999,
            'maxfilesize' => 1024 * 2,
            'msgLineFormat' => '%5$s',
        );
        $this->_logger = new Mumsys_Logger_File($this->_opts);
        $this->_object = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);
    }

    protected function tearDown()
    {
        //@unlink($this->_logfile);
        $this->_logger = $this->_object = null;
    }


    /**
     * Just for code coverge.
     * @covers Mumsys_Logger_Decorator_Messages::__construct
     */
    public function test_construct()
    {
        $object1 = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);

        $this->_opts['username'] = 'flobeeunit';
        $this->_opts['msgDatetimeFormat'] = 'H:i:s';
        $this->_opts['msgLineFormat'] = '[%3$s] %5$s';
        $this->_opts['debug'] = true;
        $this->_opts['verbose'] = true;
        $this->_opts['lf'] = " end\n";

        $object2 = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Messages::__clone
     */
    public function test__clone()
    {
        $obj = clone $this->_object;
        $this->assertInstanceOf('Mumsys_Logger_Decorator_Interface', $obj);
        $this->assertInstanceOf('Mumsys_Logger_Decorator_Interface', $this->_object);
        $this->assertNotSame($obj, $this->_object);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Messages::log
     * @covers Mumsys_Logger_Decorator_Messages::getMessageColored
     */
    public function testLog()
    {
        $this->_opts['username'] = 'flobeeunit';
        $this->_opts['msgDatetimeFormat'] = 'H:i';
        $this->_opts['msgLineFormat'] = '%1$s %2$s [%3$s] %5$s';
        $this->_opts['lf'] = " end\n";
        $this->_opts['msgColors'] = true;
        $object = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);

        $object->setMessageLoglevel(1);
        ob_start();
        $baseExpected = $object->log('bam', Mumsys_Logger_Abstract::ALERT);
        $actual = ob_get_clean();
        $expected = chr(27) . '[41m'
            . date('H:i', time()) . ' flobeeunit [ALERT] bam'
            . chr(27) . '[0m'
            .' end' . "\n";

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Messages::log
     */
    public function testLogException()
    {
        $this->_opts['username'] = 'flobeeunit';
        $this->_opts['msgDatetimeFormat'] = 'H:i';
        $this->_opts['msgLineFormat'] = '%1$s %2$s [%3$s] %5$s';
        $this->_opts['lf'] = " end\n";

        $object = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);
        ob_start();
        $baseExpected = $object->log(new stdClass(array(0 => 1)), Mumsys_Logger_Abstract::ALERT);
        $actual = ob_get_clean();

        $expected = date('H:i', time()) . ' flobeeunit [ALERT] {} end' . "\n";

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Messages::setMessageLoglevel
     */
    public function testSetMessageLoglevel()
    {
        $this->_opts['username'] = 'flobeeunit';
        $this->_opts['msgDatetimeFormat'] = 'H:i';
        $this->_opts['msgLineFormat'] = '%1$s %2$s [%3$s] %5$s';
        $this->_opts['lf'] = " end\n";
        $object = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);

        $object->setMessageLoglevel(1);
        ob_start();
        $baseExpected = $object->log('bam', Mumsys_Logger_Abstract::ALERT);
        $actual = ob_get_clean();
        $expected = date('H:i', time()) . ' flobeeunit [ALERT] bam end' . "\n";

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Messages::setMessageLoglevel
     */
    public function testSetMessageLoglevelException()
    {
        $object = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);

        $regex = '/(Level "99" unknown to set the message log level)/i';
        $this->setExpectedExceptionRegExp('Mumsys_Logger_Exception', $regex);
        $object->setMessageLoglevel(99);
    }


    /**
     * @covers Mumsys_Logger_Decorator_Messages::setMessageLoglevel
     * @covers Mumsys_Logger_Decorator_Messages::__construct
     */
    public function testabc()
    {
        $this->_opts['username'] = 'flobeeunit';
        $this->_opts['msgColors'] = true;
        $decorator = new Mumsys_Logger_Decorator_Messages($this->_logger, $this->_opts);
        //$decoratorB = new Mumsys_Logger_Decorator_ConsoleColor($decoratorA, $this->_opts);


//        ob_start();
        echo PHP_EOL;
        echo $decorator->log('level 0', 0);
        echo $decorator->log('level 1 ', 1);
        echo $decorator->log('level 2 ', 2);
        echo $decorator->log('level 3 ', 3);
        echo $decorator->log('level 4 ', 4);
        echo $decorator->log('level 5 ', 5);
        echo $decorator->log('level 6 ', 6);
        echo $decorator->log('level 7 ', 7);
        echo $decorator->log('level 99 ', 99);

//        $actual = ob_get_clean();


    }

}