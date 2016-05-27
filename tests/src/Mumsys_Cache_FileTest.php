<?php


/**
 * Mumsys_Cache_File Tests
 */
class Mumsys_Cache_FileTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Cache
     */
    protected $_object;
    protected $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.1.2';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_Cache_File' => $this->_version,
        );
        $this->_object = new Mumsys_Cache_File('group', 'id');
        $this->_object->setPath('/tmp/');
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object = NULL;
        unset($this->object);
    }


    /**
     * @covers Mumsys_Cache_File::__construct
     * @covers Mumsys_Cache_File::write
     * @covers Mumsys_Cache_File::isCached
     * @covers Mumsys_Cache_File::_getFilename
     */
    public function testWrite()
    {
        $this->_object = new Mumsys_Cache_File('group', 'id');
        $this->_object->setPath('/tmp/');

        $this->_object->isCached();

        $this->_object->write(2, 'data to cache');
        $actual = $this->_object->read();
        $this->assertEquals('data to cache', $actual);
    }


    /**
     * @covers Mumsys_Cache_File::read
     */
    public function testRead()
    {
        $actual = $this->_object->read();
        $this->assertEquals('data to cache', $actual);
    }


    /**
     * @covers Mumsys_Cache_File::isCached
     * @covers Mumsys_Cache_File::_getFilename
     */
    public function testIsCached()
    {
        $this->assertTrue($this->_object->isCached());
    }


    /**
     * @covers Mumsys_Cache_File::removeCache
     * @covers Mumsys_Cache_File::_getFilename
     */
    public function testRemoveCache()
    {
        $actual1 = $this->_object->isCached();
        $this->_object->removeCache();
        $actual2 = $this->_object->isCached();
        $this->assertTrue($actual1);
        $this->assertFalse($actual2);
    }


    /**
     * @covers Mumsys_Cache_File::setPrefix
     * @covers Mumsys_Cache_File::getPrefix
     */
    public function testSetPrefix()
    {
        $this->_object->setPrefix('fx');
        $this->assertEquals('fx', $this->_object->getPrefix());
    }


    /**
     * @covers Mumsys_Cache_File::setPath
     * @covers Mumsys_Cache_File::getPath
     */
    public function testSetPath()
    {
        $this->_object->setPath('/tmp//');
        $this->assertEquals('/tmp/', $this->_object->getPath());
    }


    /**
     * @covers Mumsys_Cache_File::setEnable
     * @todo   Implement testSetEnable().
     */
    public function testSetEnable()
    {
        $this->_object->setEnable(false);
        $this->assertFalse($this->_object->isCached());
    }

    //
    // test abstract class


    /**
     * @covers Mumsys_Cache_File::getVersion
     */
    public function testGetVersion()
    {
        $this->assertEquals('Mumsys_Cache_File ' . $this->_version, $this->_object->getVersion());
    }


    /**
     * @covers Mumsys_Cache_File::getVersionID
     */
    public function testgetVersionID()
    {
        $this->assertEquals($this->_version, $this->_object->getVersionID());
    }


    /**
     * @covers Mumsys_Cache_File::getVersions
     */
    public function testgetVersions()
    {
        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $this->assertTrue(isset($possible[$must]));
            $this->assertTrue(($possible[$must] == $value));
        }
    }

}