<?php


/**
 * Mumsys_Request_Abstract Test
 */
class Mumsys_Request_AbstractTest
    extends PHPUnit_Framework_TestCase
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
        $_GET['prg'] = 'prg';
        $_GET['cntr'] = 'cntl';
        $_GET['actn'] = 'action';

        $this->_input = $_GET;
        $_COOKIE = array('abc' => 123);

        $this->_object = new Mumsys_Request_Default(array());
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
     * @covers Mumsys_Request_Abstract::getParam
     */
    public function testGetParam()
    {
        $actual1 = $this->_object->getParam('actn', false);
        $actual2 = $this->_object->getParam('notSet', false);

        $this->assertEquals('action', $actual1);
        $this->assertFalse($actual2);
    }


    /**
     * @covers Mumsys_Request_Abstract::setParam
     */
    public function testSetParam()
    {
        $actual1 = $this->_object->setParam('a', '111');
        $actual2 = $this->_object->setParam('a', null);
        $actual3 = $this->_object->getParams();
        $expected3 = $this->_input;

        $this->assertInstanceOf('Mumsys_Request_Default', $actual1);
        $this->assertEquals($expected3, $actual3);
    }


    /**
     * @covers Mumsys_Request_Abstract::getParams
     */
    public function testGetParams()
    {
        $actual = $this->_object->getParams();
        $this->assertEquals($this->_input, $actual);
    }


    /**
     * @covers Mumsys_Request_Abstract::setParams
     */
    public function testSetParams()
    {
        $params = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => null);
        $actual1 = $this->_object->setParams($params);
        $actual2 = $this->_object->getParams();
        $expected = $params + array('prg' => 'prg', 'cntr' => 'cntl', 'actn' => 'action');
        unset($expected['d']);

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
        $actual1 = $this->_object->getCookie('abc', false);
        $actual2 = $this->_object->getCookie(null);
        $actual3 = $this->_object->getCookie('notSet', false);

        $this->assertEquals('123', $actual1);
        $this->assertEquals($_COOKIE, $actual2);
        $this->assertFalse($actual3);
    }

}