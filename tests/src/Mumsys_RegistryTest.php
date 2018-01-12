<?php


/**
 * Mumsys_Registry Test
 */
class Mumsys_RegistryTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Registry
     */
    protected $_object;
    private $_version;
    private $_versions;
    private $_key;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.1.0';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_Registry' => '1.1.0',
        );
        $this->_key = 'unittest';
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Mumsys_Registry::remove($this->_key);
    }


    /**
     * @covers Mumsys_Registry::replace
     * @covers Mumsys_Registry::_checkKey
     */
    public function testReplace()
    {
        Mumsys_Registry::replace($this->_key, 'new value');
        $actual = Mumsys_Registry::get($this->_key);

        $this->assertEquals('new value', $actual);

        // invalid key exception
        $regex = '/(Invalid initialisation key for a setter. A string is required!)/i';
        $this->setExpectedExceptionRegExp('Mumsys_Exception', $regex);
        Mumsys_Registry::replace(new stdClass, 'new value');
    }


    /**
     * @covers Mumsys_Registry::register
     * @covers Mumsys_Registry::_checkKey
     */
    public function testRegister()
    {
        Mumsys_Registry::register($this->_key, 'new value');
        $actual = Mumsys_Registry::get('unittest');

        $this->assertEquals('new value', $actual);

        // invalid key exception
        $this->setExpectedExceptionRegExp('Mumsys_Registry_Exception','/(Registry key "unittest" exists)/i');
        Mumsys_Registry::register($this->_key, 'new value');
    }


    /**
     * @covers Mumsys_Registry::get
     * @covers Mumsys_Registry::register
     */
    public function testGet()
    {
        Mumsys_Registry::register($this->_key, 'new value');
        $actual1 = Mumsys_Registry::get('unittest');

        $actual2 = Mumsys_Registry::get('notsetValue', false);

        $this->assertEquals('new value', $actual1);
        $this->assertFalse($actual2);
    }


    /**
     * @covers Mumsys_Registry::remove
     */
    public function testRemove()
    {
        Mumsys_Registry::register($this->_key, 'new value');
        Mumsys_Registry::remove('unittest');
        $actual1 = Mumsys_Registry::get('unittest', false);
        $this->assertFalse($actual1);
    }

    // test abstracts


    /**
     * @covers Mumsys_Registry::getVersion
     */
    public function testGetVersion()
    {
        $message = 'A new version exists. You should have a look at '
            . 'the code coverage to verify all code was tested and not only '
            . 'all existing tests where checked!';
        $this->assertEquals('Mumsys_Registry ' . $this->_version, Mumsys_Registry::getVersion(), $message);
    }


    /**
     * @covers Mumsys_Registry::getVersionID
     */
    public function testgetVersionID()
    {
        $message = 'A new version exists. You should have a look at '
            . 'the code coverage to verify all code was tested and not only '
            . 'all existing tests where checked!';
        $this->assertEquals($this->_version, Mumsys_Registry::VERSION, $message);
    }


    /**
     * @covers Mumsys_Registry::getVersions
     */
    public function testgetVersions()
    {
        $possible = Mumsys_Registry::getVersions();
        // echo '<pre>'; print_r($possible);
        foreach ( $this->_versions as $must => $value ) {
            $this->assertTrue(isset($possible[$must]));
            $this->assertTrue(($possible[$must] == $value));
        }
    }

}
