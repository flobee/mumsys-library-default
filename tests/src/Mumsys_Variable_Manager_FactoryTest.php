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
        $this->_version = '1.2.1';
    }


    protected function tearDown()
    {

    }


    /**
     * @covers Mumsys_Variable_Manager_Factory::createManager
     */
    public function testCreateManager()
    {
        $object = Mumsys_Variable_Manager_Factory::createManager('Default');
        $this->assertInstanceOf('Mumsys_Variable_Manager_Interface', $object);
    }


    /**
     * @covers Mumsys_Variable_Manager_Factory::createManager
     */
    public function testCreateManagerException1()
    {
        $this->expectExceptionMessageRegExp('/(Invalid manager name: "1 - \$5DefaultExit")/i');
        $this->expectException('Mumsys_Variable_Manager_Exception');
        Mumsys_Variable_Manager_Factory::createManager('1 - $5DefaultExit');
    }


    /**
     * @covers Mumsys_Variable_Manager_Factory::createManager
     */
    public function testCreateManagerException2()
    {
        $this->expectExceptionMessageRegExp('/(Initialisation of "Mumsys_Variable_Manager_Xxx" failed. Not found\/ exists)/i');
        $this->expectException( 'Mumsys_Variable_Manager_Exception');
        Mumsys_Variable_Manager_Factory::createManager('xxx');
    }


    /**
     * Version check
     */
    public function testCheckVersion()
    {
        $message = 'On error a new Version exists. You should have a look at '
            . 'the code coverage to verify all code was tested and not only '
            . 'the tests!';
        $this->assertEquals($this->_version, Mumsys_Variable_Item_Abstract::VERSION, $message);
    }

}