<?php


/**
 * Mumsys_Variable_Manager_Default Test
 */
class Mumsys_Variable_Manager_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Variable_Manager_Default
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
     * @var array
     */
    protected $_values;


    protected function setUp()
    {
        $this->_version = '1.1.1';

        $this->_config = array(
            'username' => array(
                'type' => 'string',
                'minlen' => 1,
                'maxlen' => 45,
                'label' => 'Username label',
            ),
            'password' => array(
                'type' => 'string',
                'minlen' => 1,
                'maxlen' => 64,
                'regex' => '/\w+{1,64}',
                'label' => 'Password label',
            )
        );
        $this->_values = array(
            'username' => 'mumsys',
            'password' => 'some secret',
        );

        $this->_object = new Mumsys_Variable_Manager_Default($this->_config, $this->_values);
    }


    protected function tearDown()
    {
        $this->_object = NULL;
    }


    /**
     * Just for code coverage
     * @covers Mumsys_Variable_Manager_Default::__construct
     */
    public function test_construct()
    {
        $object = new Mumsys_Variable_Manager_Default($this->_config, $this->_values);
        $this->assertInstanceOf('Mumsys_Variable_Manager_Interface', $object);
        $this->assertInstanceOf('Mumsys_Variable_Item_Interface', $object->getItem('username'));
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validate
     */
    public function testValidate()
    {
        $this->assertTrue($this->_object->validate());

        $this->_values['username'] = '';
        $this->_object = new Mumsys_Variable_Manager_Default($this->_config, $this->_values);
        $this->assertFalse($this->_object->validate());
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateType
     */
    public function testValidateType()
    {
        $item = $this->_object->getItem('username');
        $item->setType('string');
        $this->assertTrue($this->_object->validateType($item));
        $item->setType('char');
        $this->assertTrue($this->_object->validateType($item));
        $item->setType('varchar');
        $this->assertTrue($this->_object->validateType($item));
        $item->setType('text');
        $this->assertTrue($this->_object->validateType($item));
        $item->setType('tinytext');
        $this->assertTrue($this->_object->validateType($item));
        $item->setType('longtext');
        $this->assertTrue($this->_object->validateType($item));

        $item->setValue(array('bam'));
        $this->assertFalse($this->_object->validateType($item));

        $item->setType('array');
        $this->assertTrue($this->_object->validateType($item));

        $item->setValue('valid@email.com');
        $this->assertFalse($this->_object->validateType($item));

        $item->setType('email');
        $this->assertTrue($this->_object->validateType($item));
        $item->setValue('in - valid@email.com');
        $this->assertFalse($this->_object->validateType($item));

        $item->setType('numeric');
        $this->assertFalse($this->_object->validateType($item));
        $item->setValue('123');
        $this->assertTrue($this->_object->validateType($item));

        $item->setType('float');
        $this->assertTrue($this->_object->validateType($item));
        $item->setType('double');
        $this->assertTrue($this->_object->validateType($item));
        $item->setValue('1.A');
        $this->assertFalse($this->_object->validateType($item));

        $item->setType('int');
        $this->assertFalse($this->_object->validateType($item));
        $item->setType('integer');
        $this->assertFalse($this->_object->validateType($item));
        $item->setType('smallint');
        $this->assertFalse($this->_object->validateType($item));
        $item->setValue(2001);
        $this->assertTrue($this->_object->validateType($item));

        $item->setType('date');
        $this->assertFalse($this->_object->validateType($item));
        $item->setValue('2016-12-31');
        $this->assertTrue($this->_object->validateType($item));

        $item->setType('datetime');
        $this->assertFalse($this->_object->validateType($item));
        $item->setType('timestamp');
        $this->assertFalse($this->_object->validateType($item));
        $item->setValue('2016-12-31 23:58:59');
        $this->assertTrue($this->_object->validateType($item));

        $item->setType('ipv4');
        $this->assertFalse($this->_object->validateType($item));
        $item->setValue('127.0.0.1');
        $this->assertTrue($this->_object->validateType($item));

        $item->setType('ipv6');
        $this->assertFalse($this->_object->validateType($item));
        $item->setValue('::1');
        $this->assertTrue($this->_object->validateType($item));

        $this->setExpectedExceptionRegExp('Mumsys_Variable_Manager_Exception', '/(Type "unittest" not implemented)/i');
        $item->setType('unittest');
        $this->_object->validateType($item);
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateMinMax
     */
    public function testValidateMinMax()
    {
        $item = new Mumsys_Variable_Item_Default();
        $this->assertTrue($this->_object->validateMinMax($item));

        $item->setValue('a string');
        $item->setMinLength(9);
        $item->setMaxLength(7);

        foreach ( Mumsys_Variable_Abstract::getTypes() as $type ) {
            $item->setType($type);
            $this->assertFalse($this->_object->validateMinMax($item));
        }

        $item->setValue(8);
        $item->setMinLength(9);
        $item->setMaxLength(7);
        foreach ( Mumsys_Variable_Abstract::getTypes() as $type ) {
            $item->setType($type);
            $this->assertFalse($this->_object->validateMinMax($item));
        }
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateRegex
     */
    public function testValidateRegex()
    {
        $item = $this->_object->getItem('username');
        $this->assertTrue($this->_object->validateRegex($item));

        $item->setRegex('/(\w+)/i');
        $this->assertTrue($this->_object->validateRegex($item));

        $item->setRegex('/(\d+)/i');
        $this->assertFalse($this->_object->validateRegex($item));

        $a = ini_get('error_reporting');
        $b = ini_get('display_errors');
        ini_set('error_reporting', 0);
        ini_set('display_errors', false);
        $item->setRegex('/(+)/i');
        $this->assertFalse($this->_object->validateRegex($item));
        ini_set('error_reporting', $a);
        ini_set('display_errors', $b);
    }

    /**
     * @covers Mumsys_Variable_Manager_Default::validateIPv4
     */
    public function testValidateIPv4()
    {
        $item = $this->_object->getItem('username');
        $item->setType('ipv4');
        $item->setValue('112.110.110.112');

        $item2 = clone $item;
        $item2->setValue('localhost');

        $this->assertTrue($this->_object->validateIPv4($item));
        $this->assertFalse($this->_object->validateIPv4($item2));

        $this->assertEquals(
            array('TYPE_INVALID_IPV4' => 'Value (json):"localhost" is not an "ipv4" address'),
            $item2->getErrorMessages()
        );
    }

    /**
     * @covers Mumsys_Variable_Manager_Default::validateIPv6
     */
    public function testValidateIPv6()
    {
        $item = $this->_object->getItem('username');
        $item->setType('ipv6');
        $item->setValue('::1');

        $item2 = clone $item;
        $item2->setValue('localhost');

        $this->assertTrue($this->_object->validateIPv6($item));
        $this->assertFalse($this->_object->validateIPv6($item2));

        $this->assertEquals(
            array('TYPE_INVALID_IPV6' => 'Value (json):"localhost" is not an "ipv6" address'),
            $item2->getErrorMessages()
        );
    }

    /**
     * @covers Mumsys_Variable_Manager_Default::isValid
     */
    public function testIsValid()
    {
        $item = $this->_object->getItem('username');
        $actual = $this->_object->isValid($item);

        $this->assertTrue($actual);

        $item->setRequired(true);
        $item->setAllowEmpty(true);
        $item->setValue('');
        $this->assertTrue($this->_object->isValid($item));

        $item->setValue(null);
        $this->assertFalse($this->_object->isValid($item));

        $item->setRequired(false);
        $item->setAllowEmpty(false);
        $this->assertFalse($this->_object->isValid($item));

        $item->setValue('null');
        $item->setRegex('/(\d+)/i');
        $this->assertFalse($this->_object->isValid($item));
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getItems
     */
    public function testGetItems()
    {
        $this->assertEquals(2, count($this->_object->getItems()));
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getItem
     */
    public function testGetItem()
    {
        $item = $this->_object->getItem('username');
        $this->assertInstanceOf('Mumsys_Variable_Item_Interface', $item);

        $this->assertFalse($this->_object->getItem('notIn'));
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::registerItem
     */
    public function testRegisterItem()
    {
        $item = $this->_object->getItem('username');
        $this->_object->registerItem('test', $item);

        $this->setExpectedExceptionRegExp('Mumsys_Variable_Manager_Exception', '/(Item "test" already set)/i');
        $this->_object->registerItem('test', $item);
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::createItem
     */
    public function testCreateItem()
    {
        $item = $this->_object->getItem('username');

        $actual = $this->_object->createItem($this->_config['username']);
        $actual->setName('username');
        $actual->setValue($this->_values['username']);

        $this->assertEquals($item, $actual);
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getErrorMessages
     */
    public function testGetErrorMessages()
    {
        $this->assertFalse($this->_object->getErrorMessages());
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getMessageTemplates
     */
    public function testGetMessageTemplates()
    {
        $this->assertInternalType('array', $this->_object->getMessageTemplates());
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::setMessageTemplates
     */
    public function testSetMessageTemplates()
    {
        $this->_object->setMessageTemplates(array());
        $this->assertInternalType('array', $this->_object->getMessageTemplates());
    }


    /**
     * Version check
     */
    public function testCheckVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Variable_Item_Abstract::VERSION);
    }
}