<?php


/**
 * Mumsys_Variable_Item_Abstract Test
 */
class Mumsys_Variable_Item_AbstractTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Variable_Item_Default
     */
    protected $_object;

    /**
     * Version ID
     * @var string
     */
    private $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.1.1';

        $this->_config = array(
            'name' => 'somevariable',
            'value' => 'init value'
        );
        $this->_object = new Mumsys_Variable_Item_Default($this->_config);
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
     * @covers Mumsys_Variable_Item_Abstract::getName
     * @covers Mumsys_Variable_Item_Abstract::setName
     */
    public function testGetSetName()
    {
        $this->_object->setName('somevariable2');
        $this->assertEquals('somevariable2', $this->_object->getName());
    }


    /**
     * @covers Mumsys_Variable_Item_Abstract::getValue
     * @covers Mumsys_Variable_Item_Abstract::setValue
     */
    public function testGetSetValue()
    {
        $this->_object->setValue('somevariable2');
        $this->assertEquals('somevariable2', $this->_object->getValue());
    }


    /**
     * @covers Mumsys_Variable_Item_Abstract::setErrorMessage
     * @covers Mumsys_Variable_Item_Abstract::getErrorMessages
     */
    public function testGetSetErrorMessages()
    {
        $expected = array('testKey' => 'testMessage');
        $this->_object->setErrorMessage('testKey', 'testMessage');
        $this->assertEquals($expected, $this->_object->getErrorMessages());
    }

    /**
     * Version check
     */
    public function testCheckVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Variable_Item_Abstract::VERSION);
    }

}