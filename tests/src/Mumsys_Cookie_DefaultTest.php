<?php


/**
 * Mumsys_Cookie_Default Test
 */
class Mumsys_Cookie_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Cookie_Default
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Cookie_Default();
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
     * @covers Mumsys_Cookie_Default::__construct
     */
    public function test__construct()
    {
        $object = new Mumsys_Cookie_Default();

        $this->assertInstanceOf('Mumsys_Cookie_Interface', $object);
    }


    /**
     * @covers Mumsys_Cookie_Default::getCookie
     * @covers Mumsys_Cookie_Default::setCookie
     * @covers Mumsys_Cookie_Default::setRawCookie
     * @runInSeparateProcess
     */
    public function testGetSetCookie()
    {
        $_COOKIE = array('unittest' => 'unit value');
        $actual1 = $this->_object->getCookie();
        $actual2 = $this->_object->getCookie('unittest');
        $actual3 = $this->_object->setCookie("a", 'b');
        $actual4 = $this->_object->setRawCookie("raw", 'raw');

        $this->assertEquals($_COOKIE, $actual1);
        $this->assertEquals($_COOKIE['unittest'], $actual2);
        $this->assertTrue($actual3);
        $this->assertTrue($actual4);
    }


    /**
     * @covers Mumsys_Cookie_Default::clear
     * @covers Mumsys_Cookie_Default::unsetCookie
     * @runInSeparateProcess
     */
    public function testClearUnset()
    {
        $_COOKIE = array('a', 'b');
        $actual1 = $this->_object->clear();

        $this->assertTrue($actual1);
    }

}