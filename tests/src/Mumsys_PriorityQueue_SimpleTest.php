<?php


/**
 * Mumsys_PriorityQueue_Simple Test
 */
class Mumsys_PriorityQueue_SimpleTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_PriorityQueue_Simple
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_PriorityQueue_Simple();
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
     * @covers Mumsys_PriorityQueue_Simple::__construct
     * @todo   Implement testAdd().
     */
    public function testConstruct()
    {
        $options = array(
            'default' => 'default values',
            'second' => '2nd values',
        );
        $this->_object = new Mumsys_PriorityQueue_Simple($options);

        $actual = $this->_object->getQueue();
        $expected = array(
            'default' => 'default values',
            'second' => '2nd values',
        );
        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_PriorityQueue_Simple::add
     * @covers Mumsys_PriorityQueue_Simple::getQueue
     * @covers Mumsys_PriorityQueue_Simple::_getPos
     */
    public function testAddGetQueue()
    {
        $this->_object->add('3rd', '3rd value', 'before', 'second');
        $this->_object->add('4th', '4th value', 'before', '3rd');
        $this->_object->add('5th', '5th value', 'after', '3rd');

        $actual = $this->_object->getQueue();
        $expected = array(
            '4th' => '4th value',
            '3rd' => '3rd value',
            '5th' => '5th value',
        );

        $this->assertEquals($expected, $actual);

        $regex = '/(Identifier "5th" already set)/i';
        $this->setExpectedExceptionRegExp('Mumsys_PriorityQueue_Exception', $regex);
        $this->_object->add('5th', '5th value', 'after', '3rd');
    }


    /**
     * @covers Mumsys_PriorityQueue_Simple::_getPos
     */
    public function test_GetPosException()
    {
        $this->_object->add('3rd', '3rd value', 'before', 'second');

        $regex = '/(Position way "somewhere" not implemented)/i';
        $this->setExpectedExceptionRegExp('Mumsys_PriorityQueue_Exception', $regex);
        $this->_object->add('5th', '5th value', 'somewhere', '3rd');
    }

}