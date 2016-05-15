<?php


/**
 * Mumsys_Request_Shell Test
 */
class Mumsys_Request_ShellTest
    extends MumsysTestHelper
{
    /**
     * @var Mumsys_Request_Default
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $options = array(
            'programKey' => 'programTest',
            'controllerKey' => 'controllerTest',
            'actionKey' => 'actionTest'
        );

        $this->_object = new Mumsys_Request_Shell($options);
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
     * For code coverage
     */
    public function test_construct()
    {
        $options = array(
            'programKey' => 'programTest',
            'controllerKey' => 'controllerTest',
            'actionKey' => 'actionTest'
        );

        $_SERVER['argv']['programTest'] = 'prg';
        $_SERVER['argv']['controllerTest'] = 'ctrl';
        $_SERVER['argv']['actionTest'] = 'act';

        $this->_input = $_SERVER['argv'];

        $this->_object = new Mumsys_Request_Shell($options);
    }


    /**
     * Tests abstract class
     */
    public function testAbstractClass()
    {
        $actual1 = $this->_object->getVersionID();
        $actual2 = $this->_object->getVersion();
        $actual3 = $this->_object->getVersions();

        $this->assertEquals('1.1.1', $actual1);
        $this->assertEquals('Mumsys_Request_Shell 1.1.1', $actual2);
        $this->assertEquals('1.1.1', $actual3['Mumsys_Request_Shell']);
    }

}