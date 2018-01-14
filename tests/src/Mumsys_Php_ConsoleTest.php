<?php

/**
 * Test class for php class.
 */
class Mumsys_Php_ConsoleTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Php
     */
    protected $object;

    protected $_testsDir;
    /**
     * Test are made vor version: ...
     * @var string
     */
    protected $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '3.0.0';

        $this->_testsDir = MumsysTestHelper::getTestsBaseDir();
        $this->object = new Mumsys_Php_Console();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Mumsys_Php_Console::check_disk_free_space
     */
    public function testCheck_disk_free_space()
    {
        $logfile = $this->_testsDir . '/logs/' . __FUNCTION__ . '.log';
        $logOpts = array('logfile'=> $logfile);
        $logger = new Mumsys_Logger_File($logOpts);

        $cmdLine = 'df -a %1$s';
        if (Mumsys_Php_Console::$os == 'WIN') {
            $cmdLine = 'c:/cygwin/bin/df.exe -a %1$s';
        }

        // basic call
        $basicCall = Mumsys_Php_Console::check_disk_free_space($this->_testsDir . '/tmp', $secCmp=2, $maxSize=92, $logger, $cmdLine);

        // check cache return inside secCmp=60sec.
        $chkCache = Mumsys_Php_Console::check_disk_free_space($this->_testsDir . '/tmp', $secCmp=60, $maxSize=92, $logger, $cmdLine);

        //disk space overflow in cache if disk usage < 1%
        $overflow = Mumsys_Php_Console::check_disk_free_space($this->_testsDir . '/tmp', $secCmp=1, $maxSize=1, $logger, $cmdLine);

        // diskOverflowFirstRun
        $tmp = Mumsys_Php_Console::check_disk_free_space($path='/var', $secCmp=60, $maxSize=2, $logger, $cmdLine);

        // wrong path
        $tmp = Mumsys_Php_Console::check_disk_free_space($path='/123', $secCmp=60, $maxSize=2, $logger, $cmdLine);

        // error accessing a path
        $err = Mumsys_Php_Console::check_disk_free_space($path='/root', $secCmp=60, $maxSize=2, $logger, 'test %1$s');

        @unlink($logfile);

        $this->assertFalse($basicCall);
        $this->assertFalse($chkCache);
        $this->assertTrue($overflow);
        $this->assertTrue($err);
    }


    public function testVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Php_Console::VERSION);
        $this->assertEquals('3.1.1', Mumsys_Php::VERSION);
    }
}
