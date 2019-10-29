<?php

/**
 * Test class for Mumsys_Lock.
 */
class Mumsys_LockTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Lock
     */
    protected $_object;
    protected $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_version = '3.1.0';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_Lock' => $this->_version,
        );
        $this->_object = new Mumsys_Lock();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        if ( is_object( $this->_object ) ) {
            $this->_object->unlock();
        }
        $this->_object = null;
    }


    public function testLock()
    {
        $this->assertTrue( $this->_object->lock() );
    }


    public function testLockException1()
    {
        $this->_object->lock();
        $regex = '/(Can not lock! Lock "\/tmp\/Mumsys_Lock.php_default.lock" '
            . 'exists)/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_Exception' );
        $this->_object->lock();
    }


    // test file with different owner rights
    public function testLockException2()
    {
        $this->_object = new Mumsys_Lock( '/root/nix.tmp' );
        $regex = '/(Locking failt for file "\/root\/nix.tmp")/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_Exception' );

        $this->_object->lock();
    }


    public function testUnlock()
    {
        $this->_object->lock();
        $this->assertTrue( $this->_object->unlock() );
    }


    public function testUnlockException()
    {
        $tmpFile = '/tmp/.ICE-unix';
        $o = new Mumsys_Lock( $tmpFile ); //file with different owner
        $regex = '/(Unlock failt for: "'
            . str_replace( '/', '\/', $tmpFile )
            . '")/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_Exception' );
        $o->unlock();
    }


    public function testIsLocked()
    {
        $this->_object->lock();
        $this->assertTrue( $this->_object->isLocked() );
        $this->_object->unlock();
        $this->assertFalse( $this->_object->isLocked() );
    }


    public function testIsLocked2()
    {
        $tmpFile = '/tmp/where/the/hell/are/you';
        $o = new Mumsys_Lock( $tmpFile );
        $regex = '#(Lock directory "/tmp/where/the/hell/are/you" not exists)#i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_Exception' );
        $o->isLocked();
    }

    // test abstracts


    /**
     * @covers Mumsys_Lock::getVersion
     */
    public function testGetVersion()
    {
        $this->assertEquals(
            'Mumsys_Lock ' . $this->_version, $this->_object->getVersion()
        );
    }


    /**
     * @covers Mumsys_Lock::getVersionID
     */
    public function testgetVersionID()
    {
        $this->assertEquals( $this->_version, $this->_object->getVersionID() );
    }


    /**
     * @covers Mumsys_Lock::getVersions
     */
    public function testGetVersions()
    {
        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $this->assertTrue( isset( $possible[$must] ) );
            $this->assertTrue( ( $possible[$must] == $value ) );
        }
    }

}
