<?php


/**
 * Test class for Mumsys_Config.
 */
class Mumsys_ConfigTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Config
     */
    protected $_object;
    protected $_config;
    protected $_context;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_context = new Mumsys_Context();

        $this->_config = MumsysTestHelper::getConfigs();

        $this->_object = new Mumsys_Config($this->_context, $this->_config);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


    public function test__construct()
    {
        $expected = MumsysTestHelper::getConfigs();
        $testvalues = $this->_object->getAll();
        $this->assertEquals($expected, $testvalues);
    }


    public function testGet()
    {
        $key = 'debug';
        $expected1 = true;
        $testvalue1 = $this->_object->get($key, 123);
        $expected2 = 123;
        $testvalue2 = $this->_object->get('notExists', 123);

        $this->assertEquals($expected1, $testvalue1[$key]);
        $this->assertEquals($expected2, 123);
    }


    public function testGetExceptionB()
    {
        $actual = $this->_object->get(array('mumsys_title', 'B'));
        $expected = array('mumsys_title' => null, 'B' => null);
        $this->assertEquals($expected, $actual);
    }


    public function testGetAll()
    {
        $expected = $this->_object->getAll();
        $testvalues = MumsysTestHelper::getConfigs();
        $this->assertEquals($expected, $testvalues);
        $this->assertTrue(is_array($expected));
    }


    public function testGetSubValues()
    {
        $actual1 = $this->_object->getSubValues(array('database'), array('type'));
        $expected1 = array('type' => 'mysql:mysqli');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals(false, $this->_object->getSubValues(array('noConfig'), array(), false));
    }


    public function testRegister()
    {
        // simple
        $this->_object->register('testkey', 'testvalue');
        $expected1 = ['testkey' => 'testvalue'];
        $testvalue1 = $this->_object->get('testkey');

        $this->assertEquals($expected1, $testvalue1);

        $this->setExpectedException(
            'Mumsys_Config_Exception', 'Config key "testkey" exists'
        );
        $this->_object->register('testkey', new stdClass());
    }


    public function testAdd()
    {
        $config = array('flo' => 'bee');
        $this->_object->register('flo', 'bee');

        $this->assertEquals($config, $this->_object->get('flo'));

        $this->setExpectedException(
            'Mumsys_Config_Exception', 'Config key "flo" exists'
        );
        $this->_object->register('flo', 'bee');
    }


    public function testReplace()
    {
        $this->_object->register('testkey2', 'testvalue2');
        $this->_object->replace('testkey2', 'testvalue3');

        $this->assertEquals(array('testkey2' => 'testvalue3'), $this->_object->get('testkey2'));
    }


    public function testLoad()
    {
        $this->setExpectedException('Mumsys_Config_Exception');
        $this->_object->load('mumsys2');
    }

}
