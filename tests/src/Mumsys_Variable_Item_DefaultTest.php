<?php

/**
 * Mumsys_Variable_Item_Default Test
 */
class Mumsys_Variable_Item_DefaultTest
    extends Mumsys_Unittest_Testcase
{
     /**
     * @var Mumsys_Variable_Item_Default
     */
    protected $_object;

    /**
     * @var array
     */
    protected $_config;

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
            'name' => 'some name',
            'value' => 'flobee@mumsys.local',
            'default' => 'def',
            'desc' => 'some desc',
            'info' => 'some info',
            // disabled here: 'label' => 'some label',
            'maxlen' => 10,
            'minlen' => 1,
            'type' => 'email',
            // disabled here: 'regex' => '/(\w+)/i',
            'allowEmpty'=>true,
            'required' =>true,
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
     * Just 4 code coverage.
     *
     * @covers Mumsys_Variable_Item_Default::__construct
     */
    public function test_constructor()
    {
        $object = new Mumsys_Variable_Item_Default($this->_config);
    }

    /**
     * @covers Mumsys_Variable_Item_Default::getType
     * @covers Mumsys_Variable_Item_Default::setType
     */
    public function testGetSetType()
    {
        $this->assertNull( $this->_object->setType( $this->_object->getType() ) );
        $this->_object->setType('integer');
        $this->assertEquals('integer', $this->_object->getType());

        $this->setExpectedExceptionRegExp('Mumsys_Variable_Item_Exception', '/Type "xxx" not implemented/i');
        $this->_object->setType('xxx');
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getMinLength
     * @covers Mumsys_Variable_Item_Default::setMinLength
     */
    public function testGetSetMinLength()
    {
        $this->_object->setMinLength(3);
        $this->assertEquals(3, $this->_object->getMinLength());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getMaxLength
     * @covers Mumsys_Variable_Item_Default::setMaxLength
     */
    public function testGetSetMaxLength()
    {
        $this->_object->setMaxLength(3);
        $this->assertEquals(3, $this->_object->getMaxLength());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getRegex
     * @covers Mumsys_Variable_Item_Default::setRegex
     * @covers Mumsys_Variable_Item_Default::addRegex
     */
    public function testGetSetAddRegex()
    {
        $expected = array('/\w*/i','/\d*/i');
        $this->assertTrue( (array() === $this->_object->getRegex() ) );
        $this->_object->setRegex('/\w*/i');
        $this->assertEquals(array($expected[0]), $this->_object->getRegex());

        $this->_object->addRegex('/\d*/i');
        $this->assertEquals($expected, $this->_object->getRegex());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getAllowEmpty
     * @covers Mumsys_Variable_Item_Default::setAllowEmpty
     * @todo   Implement testGetAllowEmpty().
     */
    public function testGetSetAllowEmpty()
    {
        $this->_object->setAllowEmpty(true);
        $this->assertTrue($this->_object->getAllowEmpty());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getRequired
     * @covers Mumsys_Variable_Item_Default::setRequired
     */
    public function testGetSetRequired()
    {
        $this->_object->setRequired(true);
        $this->assertTrue($this->_object->getRequired());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getLabel
     * @covers Mumsys_Variable_Item_Default::setLabel
     */
    public function testGetSetLabel()
    {
        $this->assertEquals('some name', $this->_object->getLabel());

        $this->_object->setLabel('some label');
        $this->assertEquals('some label', $this->_object->getLabel());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getDescription
     * @covers Mumsys_Variable_Item_Default::setDescription
     */
    public function testGetSetDescription()
    {
        $this->_object->setDescription('some desc');
        $this->assertEquals('some desc', $this->_object->getDescription());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getInformation
     * @covers Mumsys_Variable_Item_Default::setInformation
     */
    public function testGetSetInformation()
    {
        $this->_object->setInformation('some info');
        $this->assertEquals('some info', $this->_object->getInformation());
    }


    /**
     * @covers Mumsys_Variable_Item_Default::getDefault
     * @covers Mumsys_Variable_Item_Default::setDefault
     */
    public function testGetSetDefault()
    {
        $this->_object->setDefault('some default');
        $this->assertEquals('some default', $this->_object->getDefault());
    }

    /**
     * Version check
     */
    public function testCheckVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Variable_Item_Default::VERSION);
    }
}
