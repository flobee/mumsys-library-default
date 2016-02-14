<?php


/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-31 at 15:13:28.
 */
class Mumsys_RegistryTest extends PHPUnit_Framework_TestCase
{


    /**
     * @var Mumsys_Registry
     */
    protected $_object;
    protected $_version;
    protected $_key;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.0.0';
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
        $this->setExpectedException('Mumsys_Exception','Invalid registry key. It\'s not a string');
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
        $this->setExpectedException('Mumsys_Registry_Exception','Registry key "unittest" exists');
        Mumsys_Registry::register($this->_key, 'new value');
    }


    /**
     * @covers Mumsys_Registry::set
     */
    public function testSet()
    {
        $this->setExpectedException(
            'Mumsys_Registry_Exception',
            'Unknown meaning for set(). Use register() or replace() methodes'
        );
        Mumsys_Registry::set($this->_key, 'new value');
    }


    /**
     * @covers Mumsys_Registry::get
     */
    public function testGet()
    {
        Mumsys_Registry::register($this->_key, 'new value');
        $actual = Mumsys_Registry::get('unittest');

        $this->assertEquals('new value', $actual);

        $this->setExpectedException('Mumsys_Registry_Exception','Registry key "was not set" not found');
        Mumsys_Registry::get('was not set');
    }

    /**
     * @covers Mumsys_Registry::remove
     */
    public function testRemove()
    {
        Mumsys_Registry::register($this->_key, 'new value');
        $actual1 = Mumsys_Registry::remove('unittest');
        $actual2 = Mumsys_Registry::remove('unittest');
        $this->assertTrue($actual1);
        $this->assertFalse($actual2);
    }


    // test abstracts


    /**
     * @covers Mumsys_Registry::getVersion
     */
    public function testGetVersion()
    {
        $this->assertEquals('Mumsys_Registry ' . $this->_version, Mumsys_Registry::getVersion());
    }


    /**
     * @covers Mumsys_Registry::getVersionID
     */
    public function testgetVersionID()
    {
        $this->assertEquals($this->_version, Mumsys_Registry::getVersionID());
    }


    /**
     * @covers Mumsys_Registry::getVersions
     */
    public function testgetVersions()
    {
        $expected = array(
            'Mumsys_Abstract' => '3.0.1',
            'Mumsys_Registry' => $this->_version,
        );

        $possible = Mumsys_Registry::getVersions();

        foreach ($expected as $must => $value) {
            $this->assertTrue(isset($possible[$must]));
            $this->assertTrue(($possible[$must] == $value));
        }
    }

}