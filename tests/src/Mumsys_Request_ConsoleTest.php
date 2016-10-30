<?php


/**
 * Mumsys_Request_Console Test
 */
class Mumsys_Request_ConsoleTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Request_Console
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_options['programKey'] = 'prg';
        $this->_options['controllerKey'] = 'cnt';
        $this->_options['actionKey'] = 'act';

        $this->_object = new Mumsys_Request_Console($this->_options);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


    /**
     * @covers Mumsys_Request_Console::__construct
     */
    public function test_Construct()
    {
        $_SERVER['argv']['unit'] = 'test';
        $_SERVER['argv'][] = 'unit=test';

        $this->_object = new Mumsys_Request_Console($this->_options);
        $actual = $this->_object->getParams();

        $this->assertTrue((count($actual) >= 2));
    }

}