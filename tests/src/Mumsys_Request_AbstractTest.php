<?php

/**
 * Mumsys_Request_Abstract test
 */
class Mumsys_Request_AbstractTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Mumsys_Request_Abstract
     */
    protected $_object;
    /**
     * @var array
     */
    protected $_input;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $opts = array(
            'programKey' => 'programTest',
            'controllerKey' => 'controllerTest',
            'actionKey' => 'actionTest'
        );

        $_GET['programTest'] = 'prg';
        $_GET['controllerTest'] = 'ctrl';
        $_GET['actionTest'] = 'act';

        $this->_input = $_GET;
        $_COOKIE = array('HIDDEN' => 'COOK');

        $this->_object = new Mumsys_Request_Default($opts);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $_COOKIE = array();
        $this->_object = null;
    }

    /**
     * @covers Mumsys_Request_Abstract::getProgramName
     * @covers Mumsys_Request_Abstract::setProgramName
     */
    public function testGetSetProgramName()
    {
        $actual1 = $this->_object->getProgramName();
        $actual2 = $this->_object->setProgramName('test');
        $actual3 = $this->_object->getProgramName();

        $this->assertEquals('prg', $actual1);
        $this->assertInstanceOf('Mumsys_Request_Default', $actual2);
        $this->assertEquals('Test', $actual3);
    }

    /**
     * @covers Mumsys_Request_Abstract::setControllerName
     * @covers Mumsys_Request_Abstract::getControllerName
     */
    public function testGetSetControllerName()
    {
        $actual1 = $this->_object->getControllerName();
        $actual2 = $this->_object->setControllerName('lower');
        $actual3 = $this->_object->getControllerName();

        $this->assertEquals('ctrl', $actual1);
        $this->assertInstanceOf('Mumsys_Request_Default', $actual2);
        $this->assertEquals('Lower', $actual3);
    }

    /**
     * @covers Mumsys_Request_Abstract::getActionName
     * @covers Mumsys_Request_Abstract::setActionName
     */
    public function testGetSetActionName()
    {
        $actual1 = $this->_object->getActionName();
        $actual2 = $this->_object->setActionName('AcTiOnNaMe');
        $actual3 = $this->_object->getActionName();
        $actual4 = $this->_object->setActionName(null);

        $this->assertEquals('act', $actual1);
        $this->assertInstanceOf('Mumsys_Request_Default', $actual2);
        $this->assertEquals('actionname', $actual3);
        $this->assertInstanceOf('Mumsys_Request_Default', $actual4);
    }

    /**
     * @covers Mumsys_Request_Abstract::getProgramKey
     * @covers Mumsys_Request_Abstract::setProgramKey
     */
    public function testGetSetProgramKey()
    {
        $actual1 = $this->_object->getProgramKey();
        $this->assertEquals('programTest', $actual1);
    }

    /**
     * @covers Mumsys_Request_Abstract::setControllerKey
     * @covers Mumsys_Request_Abstract::getControllerKey
     */
    public function testGetSetControllerKey()
    {
        $actual1 = $this->_object->setControllerKey('cntl');
        $actual2 = $this->_object->getControllerKey();

        $this->assertInstanceOf('Mumsys_Request_Default', $actual1);
        $this->assertEquals('cntl', $actual2);
    }

    /**
     * @covers Mumsys_Request_Abstract::getActionKey
     * @covers Mumsys_Request_Abstract::setActionKey
     */
    public function testGetSetActionKey()
    {
        $actual = $this->_object->getActionKey();
        $this->_object->setActionKey('index');

        $this->assertEquals('actionTest', $actual);
        $this->assertEquals('index', $this->_object->getActionKey());
    }

    /**
     * @covers Mumsys_Request_Abstract::getParam
     * @covers Mumsys_Request_Abstract::setParam
     */
    public function testGetSetParam()
    {
        $actual1 = $this->_object->setParam('a', '111');
        $actual2 = $this->_object->setParam('a', null);
        $actual3 = $this->_object->getParams();
        $actual4 = $this->_object->getParam('programTest', false);
        $actual5 = $this->_object->getParam('notSet', false);
        $expected3 = $this->_input;

        $this->assertInstanceOf('Mumsys_Request_Default', $actual1);
        $this->assertEquals($expected3, $actual3);
        $this->assertEquals('prg', $actual4);
        $this->assertFalse($actual5);
    }

    /**
     * Plural version.
     * @covers Mumsys_Request_Abstract::getParams
     * @covers Mumsys_Request_Abstract::setParams
     */
    public function testGetSetParams()
    {
        $params = array('abc' => 1, 'def' => 2, 'ghi' => 3, 'null' => null);
        $actual1 = $this->_object->setParams($params);
        $actual2 = $this->_object->getParams();
        $expected = $params + array(
            'programTest' => 'prg', 'controllerTest' => 'ctrl', 'actionTest' => 'act'
        );
        unset($expected['null']);

        $this->assertInstanceOf('Mumsys_Request_Default', $actual1);
        $this->assertEquals($expected, $actual2);
    }

    /**
     * @covers Mumsys_Request_Abstract::clearParams
     */
    public function testClearParams()
    {
        $this->_object->clearParams();
        $this->assertEquals(array(), $this->_object->getParams());
    }

    /**
     * @covers Mumsys_Request_Abstract::getCookie
     */
    public function testGetCookie()
    {
        $actual1 = $this->_object->getCookie('HIDDEN', false);
        $actual2 = $this->_object->getCookie(null);
        $actual3 = $this->_object->getCookie('notSet', false);

        $this->assertEquals('COOK', $actual1);
        $this->assertEquals($_COOKIE, $actual2);
        $this->assertFalse($actual3);
    }

}
