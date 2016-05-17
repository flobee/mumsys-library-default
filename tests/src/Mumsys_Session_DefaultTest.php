<?php


/**
 * Mumsys_Session_Default test
 */
class Mumsys_Session_DefaultTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Session_Default
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Session_Default();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = NULL;
    }


    /**
     * @covers Mumsys_Session_Default::__construct
     * @runInSeparateProcess
     */
    public function test_construct()
    {
        $this->_object = new Mumsys_Session_Default();
        // for code coverage
        $this->_object = new Mumsys_Session_Default();

        session_write_close();
        @session_destroy();
        @session_regenerate_id();
        $this->_object = new Mumsys_Session_Default();
    }


    /**
     *
     * @covers Mumsys_Session_Default::get
     * @covers Mumsys_Session_Default::register
     * @covers Mumsys_Session_Default::replace
     * @covers Mumsys_Session_Default::getCurrent
     * @covers Mumsys_Session_Default::getAll
     * @covers Mumsys_Session_Default::remove
     * @covers Mumsys_Session_Default::clear
     */
    public function testGetReplaceRegister()
    {
        $this->_object->replace('key1', 'value1');
        $this->_object->replace('key2', 'value2');
        $this->_object->register('key3', 'value3');

        $actual1 = $this->_object->get('key1');
        $actual2 = $this->_object->get('key2');
        $actual3 = $this->_object->get('key3');
        $actual4 = $this->_object->get('key4');

        $this->assertEquals('value1', $actual1);
        $this->assertEquals('value2', $actual2);
        $this->assertEquals('value3', $actual3);
        $this->assertNull($actual4);

        $expected1 = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        );
        $expected2 = array($this->_object->getID() => $expected1);

        $this->assertEquals($expected1, $this->_object->getCurrent());
        $this->assertEquals($expected2, $this->_object->getAll());

        $this->assertTrue($this->_object->remove('key3'));
        $this->assertFalse($this->_object->remove('key4'));

        $this->_object->clear();
        $this->assertEquals(array(), $this->_object->getAll());
        $this->assertEquals(array(), $this->_object->getCurrent());
    }


    /**
     * @covers Mumsys_Session_Default::register
     */
    public function testRegisterException()
    {
        $this->_object->replace('key1', 'value1');

        $this->setExpectedExceptionRegExp('Mumsys_Session_Exception', '/(Session key "key1" exists)/');
        $this->_object->register('key1', 'value1');
    }


    /**
     * @covers Mumsys_Session_Default::__destruct
     */
    public function test_destruct()
    {
        $this->assertEquals(array(), $this->_object->getAll());
        $this->assertEquals(array(), $this->_object->getCurrent());
    }


    /**
     * @covers Mumsys_Session_Default::remove
     */
    public function testRemove()
    {
        $this->assertFalse($this->_object->remove('key4'));
    }


    /**
     * @covers Mumsys_Session_Default::getID
     */
    public function testGetID()
    {
        $this->assertEquals($this->_object->getID(), $this->_object->getID());
    }

}