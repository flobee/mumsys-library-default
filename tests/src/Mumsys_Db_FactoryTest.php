<?php


/**
 * Mumsys_Db_Factory Test
 */
class Mumsys_Db_FactoryTest extends MumsysTestHelper
{
    /**
     * @var Mumsys_Db
     */
    protected $_object;
    /**
     * @var Mumsys_Context
     */
    protected $_context;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_context = new Mumsys_Context();
        $this->_configs = MumsysTestHelper::getConfigs();
        $this->_configs['database']['type'] = 'mysql:mysqli';

        $this->_object = new Mumsys_Db_Factory($this->_context, $this->_configs['database']);
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
     * @covers Mumsys_Db_Factory::getInstance
     */
    public function testGetInstance()
    {
        $actual = $this->_object->getInstance($this->_context, $this->_configs['database']);
        $this->assertInstanceOf('Mumsys_Db_Driver_Interface', $actual);
    }

    /**
     * @covers Mumsys_Db_Factory::getInstance
     */
    public function testGetInstanceException1()
    {
        $options = $this->_configs['database'];
        $options['type'] = 'xxx';

        $this->setExpectedException('Mumsys_Db_Exception', 'Invalid Db driver. Can not create instance');
        $actual = $this->_object->getInstance($this->_context, $options);
    }

}
