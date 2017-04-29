<?php


/**
 * Mumsys_Context Test
 */
class Mumsys_Context_ItemTest
    extends Mumsys_Unittest_Testcase
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
        $this->_version = '3.0.0';
        $this->_versions = array(
            'Mumsys_Abstract' => '3.0.2',
            'Mumsys_Context_Item' => $this->_version,
        );
        $this->_logfile = '/tmp/' . basename(__FILE__) . '.log';
        $this->_object = new Mumsys_Context_Item();
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
     * @covers Mumsys_Context_Item::_get
     */
    public function test_get()
    {
        $this->setExpectedExceptionRegExp('Mumsys_Context_Exception', '/("Mumsys_Config_Interface" not set)/i');
        $this->_object->getConfig();
    }


    /**
     * @covers Mumsys_Context_Item::getConfig
     * @covers Mumsys_Context_Item::registerConfig
     */
    public function testGetSetConfig()
    {
        $config = new Mumsys_Config(array('x' => 'y'));
        $this->_object->registerConfig($config);

        $this->assertInstanceOf('Mumsys_Config_Interface', $this->_object->getConfig());
    }


    /**
     * @covers Mumsys_Context_Item::getPermissions
     * @covers Mumsys_Context_Item::registerPermissions
     */
//    public function testGetSetPermissions()
//    {
//        $oPerms = new Mumsys_Permissions_Console($this->_object, array('x' => 'y'));
//        $this->_object->registerPermissions($oPerms);
//
//        $this->assertInstanceOf('Mumsys_Permissions_Console', $this->_object->getPermissions());
//        $this->assertInstanceOf('Mumsys_Permissions_Interface', $this->_object->getPermissions());
//    }


    /**
     * @covers Mumsys_Context_Item::getSession
     * @covers Mumsys_Context_Item::registerSession
     * @covers Mumsys_Context_Item::_get
     * @covers Mumsys_Context_Item::_register
     * @runInSeparateProcess
     */
    public function testGetSetSession()
    {
        $session = new Mumsys_Session_Default();
        $this->_object->registerSession($session);

        $this->assertInstanceOf('Mumsys_Session_Interface', $this->_object->getSession());

        $this->setExpectedExceptionRegExp('Mumsys_Context_Exception', '/("Mumsys_Session_Interface" already set)/i');
        $this->_object->registerSession($session);
    }


    /**
     * @covers Mumsys_Context_Item::getDisplay
     * @covers Mumsys_Context_Item::registerDisplay
     * @covers Mumsys_Context_Item::replaceDisplay
     * @covers Mumsys_Context_Item::_replace
     */
//    public function testGetSetDisplay()
//    {
//        $display1 = new Mumsys_Mvc_Templates_Text_Default($this->_object);
//        $this->_object->registerDisplay($display1);
//        $actual1 = $this->_object->getDisplay();
//
//        $factory = new Mumsys_Mvc_Display_Factory($this->_object);
//        $display2 = $factory->load(array(), 'Text', 'Default');
//        $this->_object->replaceDisplay($display2);
//        $actual2 = $this->_object->getDisplay();
//
//        $this->assertInstanceOf('Mumsys_Mvc_Templates_Text_Default', $actual1);
//        $this->assertInstanceOf('Mumsys_Mvc_Display_Control_Abstract', $actual1);
//        $this->assertInstanceOf('Mumsys_Mvc_Templates_Text_Default', $actual2);
//        $this->assertInstanceOf('Mumsys_Mvc_Display_Control_Abstract', $actual2);
//
//
//        $this->setExpectedExceptionRegExp('Mumsys_Context_Exception', '/("Mumsys_Mvc_Display_Control_Interface" already set)/i');
//        $this->_object->registerDisplay($display2);
//    }


    /**
     * @covers Mumsys_Context_Item::getControllerFrontend
     * @covers Mumsys_Context_Item::registerControllerFrontend
     */
//    public function testGetSetControllerFrontend()
//    {
//        $obj = new Mumsys_Mvc_Templates_Text_Default($this->_object);
//        $this->_object->registerControllerFrontend($obj);
//        $actual1 = $this->_object->getControllerFrontend();
//
//        $this->assertEquals($obj, $actual1);
//    }


    /**
     * @covers Mumsys_Context_Item::getTranslation
     * @covers Mumsys_Context_Item::registerTranslation
     */
    public function testGetSetTranslation()
    {
        $expected1 = new Mumsys_I18n_Default('de');
        $this->_object->registerTranslation($expected1);
        $actual1 = $this->_object->getTranslation();

        $this->assertEquals($expected1, $actual1);
    }


    /**
     * @covers Mumsys_Context_Item::getLogger
     * @covers Mumsys_Context_Item::registerLogger
     * @covers Mumsys_Context_Item::_get
     * @covers Mumsys_Context_Item::_register
     */
    public function testGetSetLogger()
    {
        $logger = new Mumsys_Logger(array('logfile' => $this->_logfile));
        $this->_object->registerLogger($logger);

        $this->assertInstanceOf('Mumsys_Logger_Interface', $this->_object->getLogger());

        $this->setExpectedExceptionRegExp('Mumsys_Context_Exception', '/("Mumsys_Logger_Interface" already set)/');
        $this->_object->registerLogger($logger);
    }


    /**
     * @covers Mumsys_Context_Item::getGeneric
     * @covers Mumsys_Context_Item::registerGeneric
     */
    public function testGetSetGeneric()
    {
        $interface = 'stdClass';
        $value = new stdClass();
        $this->_object->registerGeneric($interface, $value);

        $actual1 = $this->_object->getGeneric($interface, false);
        $actual2 = $this->_object->getGeneric('notExists', false);

        $this->assertEquals($value, $actual1);
        $this->assertFalse($actual2);

        $message = '/(Generic interface "imNotSetInterface" not found)/i';
        $this->setExpectedExceptionRegExp('Mumsys_Context_Exception', $message);
        $this->_object->getGeneric('imNotSetInterface', null);
    }


    /**
     * @covers Mumsys_Context_Item::getGeneric
     * @covers Mumsys_Context_Item::registerGeneric
     */
    public function testRegisterGerneicException()
    {
        $interface = 'badInterface';
        $value = new stdClass();

        $this->setExpectedExceptionRegExp('Mumsys_Context_Exception', '/(Value does not implement the interface "badInterface")/i');
        $this->_object->registerGeneric($interface, $value);
    }


    /**
     * Test abstract class
     *
     * @covers Mumsys_Context_Item::getVersion
     * @covers Mumsys_Context_Item::getVersionID
     * @covers Mumsys_Context_Item::getVersions
     */
    public function testGetVersion()
    {
        $message = 'A new version exists. You should have a look at '
            . 'the code coverage to verify all code was tested and not only '
            . 'all existing tests where checked!';
        $this->assertEquals($this->_version, Mumsys_Config_File::VERSION, $message);


        $this->assertEquals('Mumsys_Context_Item ' . $this->_version, $this->_object->getVersion());
        $this->assertEquals($this->_version, $this->_object->getVersionID());

        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $message = 'Invalid: ' . $must . '::' . $value;
            $this->assertTrue(isset($possible[$must]), $message);
            $this->assertTrue(($possible[$must] == $value), $message);
        }

    }

}
