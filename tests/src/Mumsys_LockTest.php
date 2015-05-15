<?php

/**
 * Test class for Mumsys_Lock.
 */
class Mumsys_LockTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Lock
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Lock();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if ( is_object($this->_object) ) {
            $this->_object->unlock();
        }
        $this->_object = null;
    }


    public function testLock()
    {
        $this->assertTrue($this->_object->lock());
    }


    public function testLockException1()
    {
        $this->_object->lock();
        $this->setExpectedException(
            'Mumsys_Exception', 'Can not lock! Lock "/tmp/Mumsys_Lock.php_default.lock" exists'
        );
        $this->_object->lock();
    }


    // test file with different owner rights
    public function testLockException2()
    {
        $this->_object = new Mumsys_Lock('/root/nix.tmp');
        $this->setExpectedException(
            'Mumsys_Exception', 'Locking failt for file "/root/nix.tmp"'
        );

        $this->_object->lock();
    }


    public function testUnlock()
    {
        $this->_object->lock();
        $this->assertTrue($this->_object->unlock());
    }


    public function testUnlockException()
    {
        $tmpFile = '/tmp/.ICE-unix';
        $o = new Mumsys_Lock($tmpFile); //file with different owner
        $this->setExpectedException(
            'Mumsys_Exception', 'Unlock failt for: "' . $tmpFile . '"'
        );
        $o->unlock();
    }


    public function testIsLocked()
    {
        $this->_object->lock();
        $this->assertTrue($this->_object->isLocked());
        $this->_object->unlock();
        $this->assertFalse($this->_object->isLocked());
    }


}
