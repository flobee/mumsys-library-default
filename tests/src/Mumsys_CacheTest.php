<?php

/**
 * Mumsys_Cache Tests
 */
class Mumsys_CacheTest
    extends Mumsys_Unittest_Testcase
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
        $this->_version = '1.1.1';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_Cache' => $this->_version,
        );
        $this->_object = new Mumsys_Cache( 'group', 'id' );
        $this->_object->setPath( '/tmp/' );
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object = null;
        unset( $this->object );
    }


    /**
     * @covers Mumsys_Cache::__construct
     * @covers Mumsys_Cache::write
     * @covers Mumsys_Cache::isCached
     * @covers Mumsys_Cache::_getFilename
     */
    public function testWrite()
    {
        $this->_object = new Mumsys_Cache( 'group', 'id' );
        $this->_object->setPath( '/tmp/' );

        $this->_object->isCached();

        $this->_object->write( 2, 'data to cache' );
        $actual = $this->_object->read();
        $this->assertingEquals( 'data to cache', $actual );
    }


    /**
     * @covers Mumsys_Cache::read
     */
    public function testRead()
    {
        $actual = $this->_object->read();
        $this->assertingEquals( 'data to cache', $actual );
    }


    /**
     * @covers Mumsys_Cache::isCached
     * @covers Mumsys_Cache::_getFilename
     */
    public function testIsCached()
    {
        $this->assertingTrue( $this->_object->isCached() );
    }


    /**
     * @covers Mumsys_Cache::removeCache
     * @covers Mumsys_Cache::_getFilename
     */
    public function testRemoveCache()
    {
        $actual1 = $this->_object->isCached();
        $this->_object->removeCache();
        $actual2 = $this->_object->isCached();
        $this->assertingTrue( $actual1 );
        $this->assertingFalse( $actual2 );
    }


    /**
     * @covers Mumsys_Cache::setPrefix
     * @covers Mumsys_Cache::getPrefix
     */
    public function testSetPrefix()
    {
        $this->_object->setPrefix( 'fx' );
        $this->assertingEquals( 'fx', $this->_object->getPrefix() );
    }


    /**
     * @covers Mumsys_Cache::setPath
     * @covers Mumsys_Cache::getPath
     */
    public function testSetPath()
    {
        $this->_object->setPath( '/tmp//' );
        $this->assertingEquals( '/tmp/', $this->_object->getPath() );
    }


    /**
     * @covers Mumsys_Cache::setEnable
     * @todo   Implement testSetEnable().
     */
    public function testSetEnable()
    {
        $this->_object->setEnable( false );
        $this->assertingFalse( $this->_object->isCached() );
    }

    // test abstracts


    /**
     * @covers Mumsys_Cache::getVersion
     */
    public function testGetVersion()
    {
        $this->assertingEquals(
            'Mumsys_Cache ' . $this->_version, $this->_object->getVersion()
        );
    }


    /**
     * @covers Mumsys_Cache::getVersionID
     */
    public function testgetVersionID()
    {
        $this->assertingEquals( $this->_version, $this->_object->getVersionID() );
    }


    /**
     * @covers Mumsys_Cache::getVersions
     */
    public function testgetVersions()
    {
        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $this->assertingTrue( isset( $possible[$must] ) );
            $this->assertingTrue( ( $possible[$must] == $value ) );
        }
    }

}
