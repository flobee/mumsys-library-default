<?php

/**
 * Mumsys_Context Test
 */
class Mumsys_ContextTest extends MumsysTestHelper
{
    /**
     * @var Mumsys_Context
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.0.2';
        $this->_versions = array(
            'Mumsys_Abstract' => '3.0.2',
            'Mumsys_Context' => $this->_version,
        );
        $this->_logfile = '/tmp/'.basename(__FILE__) . '.log';
        $this->_object = new Mumsys_Context();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        @unlink($this->_logfile);
    }

    /**
     * @covers Mumsys_Context::_get
     */
    public function test_get()
    {
        $this->setExpectedException('Mumsys_Exception', '"Mumsys_Config" not set');
        $this->_object->getConfig();
    }

    /**
     * @covers Mumsys_Context::getConfig
     * @covers Mumsys_Context::registerConfig
     */
    public function testGetSetConfig()
    {
        $config = new Mumsys_Config($this->_object, array('x' => 'y'));
        $this->_object->registerConfig($config);

        $this->assertInstanceOf('Mumsys_Config', $this->_object->getConfig());
    }

    /**
     * @covers Mumsys_Context::getPermissions
     * @covers Mumsys_Context::registerPermissions
     */
    public function testGetSetPermissions()
    {
        $oPerms = new Mumsys_Permissions_Shell($this->_object, array('x' => 'y'));
        $this->_object->registerPermissions($oPerms);

        $this->assertInstanceOf('Mumsys_Permissions_Shell', $this->_object->getPermissions());
        $this->assertInstanceOf('Mumsys_Permissions_Interface', $this->_object->getPermissions());
    }

    /**
     * @covers Mumsys_Context::getSession
     * @covers Mumsys_Context::registerSession
     * @covers Mumsys_Context::_get
     * @covers Mumsys_Context::_register
     */
    public function testGetSetSession()
    {
        $session = new Mumsys_Session();
        $this->_object->registerSession($session);

        $this->assertInstanceOf('Mumsys_Session', $this->_object->getSession());

        $this->setExpectedException('Mumsys_Exception', '"Mumsys_Session_Interface" already set');
        $this->_object->registerSession($session);
    }


    /**
     * @covers Mumsys_Context::getDisplay
     * @covers Mumsys_Context::registerDisplay
     * @covers Mumsys_Context::replaceDisplay
     * @covers Mumsys_Context::_replace
     */
    public function testGetSetDisplay()
    {
        $display1 = new Mumsys_Mvc_Templates_Text_Default($this->_object);
        $this->_object->registerDisplay($display1);
        $actual1 = $this->_object->getDisplay();

        $factory = new Mumsys_Mvc_Display_Factory($this->_object);
        $display2 = $factory->load(array(), 'Text','Default');
        $this->_object->replaceDisplay($display2);
        $actual2 = $this->_object->getDisplay();

        $this->assertInstanceOf('Mumsys_Mvc_Templates_Text_Default', $actual1);
        $this->assertInstanceOf('Mumsys_Mvc_Display_Control_Abstract', $actual1);
        $this->assertInstanceOf('Mumsys_Mvc_Templates_Text_Default', $actual2);
        $this->assertInstanceOf('Mumsys_Mvc_Display_Control_Abstract', $actual2);


        $this->setExpectedException('Mumsys_Exception', '"Mumsys_Mvc_Display_Control_Interface" already set');
        $this->_object->registerDisplay($display2);
    }

    /**
     * @covers Mumsys_Context::getControllerFrontend
     * @covers Mumsys_Context::registerControllerFrontend
     */
    public function testGetSetControllerFrontend()
    {
        $obj = new Mumsys_Mvc_Templates_Text_Default($this->_object);
        $this->_object->registerControllerFrontend($obj);
        $actual1 = $this->_object->getControllerFrontend();

        $this->assertEquals($obj, $actual1);
    }

    /**
     * @covers Mumsys_Context::getTranslation
     * @covers Mumsys_Context::registerTranslation
     */
    public function testGetTranslation()
    {
        $expected1 = new Mumsys_I18n_Default('de');
        $this->_object->registerTranslation($expected1);
        $actual1 = $this->_object->getTranslation();

        $this->assertEquals($expected1, $actual1);
    }

    /**
     * @covers Mumsys_Context::getLogger
     * @covers Mumsys_Context::registerLogger
     * @covers Mumsys_Context::_get
     * @covers Mumsys_Context::_register
     */
    public function testGetSetLogger()
    {
        $logger = new Mumsys_Logger(array('logfile' => $this->_logfile));
        $this->_object->registerLogger($logger);

        $this->assertInstanceOf('Mumsys_Logger_Interface', $this->_object->getLogger());

        $this->setExpectedException('Mumsys_Exception', '"Mumsys_Logger_Interface" already set');
        $this->_object->registerLogger($logger);
    }

    /**
     * Test abstract class
     *
     * @covers Mumsys_Context::getVersion
     * @covers Mumsys_Context::getVersionID
     * @covers Mumsys_Context::getVersions
     */
    public function testGetVersion()
    {
        $this->assertEquals('Mumsys_Context ' . $this->_version, $this->_object->getVersion());
        $this->assertEquals($this->_version, $this->_object->getVersionID());

        $possible = $this->_object->getVersions();

        foreach ($this->_versions as $must => $value) {
            $this->assertTrue(isset($possible[$must]));
            $this->assertTrue(($possible[$must] == $value));
        }
    }

}
