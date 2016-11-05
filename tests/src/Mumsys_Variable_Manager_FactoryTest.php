<?php


/**
 * Mumsys_Variable_Manager_Factory Test
 */
class Mumsys_Variable_Manager_FactoryTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Variable_Manager_Factory
     */
    protected $_object;

    /**
     * Version ID
     * @var string
     */
    private $_version;


    protected function setUp()
    {
        $this->_version = '1.1.1';
    }


    protected function tearDown()
    {

    }


    /**
     * @covers Mumsys_Variable_Manager_Factory::createManager
     */
    public function testCreateManager()
    {
        $object = Mumsys_Variable_Manager_Factory::createManager(new Mumsys_Context(), 'Default');
        $this->assertInstanceOf('Mumsys_Variable_Manager_Interface', $object);
    }


    /**
     * @covers Mumsys_Variable_Manager_Factory::createManager
     */
    public function testCreateManagerException1()
    {
        $this->setExpectedExceptionRegExp(
            'Mumsys_Variable_Manager_Exception', '/(Invalid manager name: "1 - \$5DefaultExit")/i'
        );
        Mumsys_Variable_Manager_Factory::createManager(new Mumsys_Context(), '1 - $5DefaultExit');
    }


    /**
     * @covers Mumsys_Variable_Manager_Factory::createManager
     */
    public function testCreateManagerException2()
    {
        $this->setExpectedExceptionRegExp(
            'Mumsys_Variable_Manager_Exception',
            '/(Initialisation of "Mumsys_Variable_Manager_Xxx" failed. Not found\/ exists)/i'
        );
        Mumsys_Variable_Manager_Factory::createManager(new Mumsys_Context(), 'xxx');
    }


    /**
     * Version check
     */
    public function testCheckVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Variable_Item_Abstract::VERSION);
    }

}