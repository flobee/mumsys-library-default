<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Lock
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright (c) 2006 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Lock
 * @version     3.0.0
 * 0.1 - Created: 2006-04-28
 * @filesource
 * -----------------------------------------------------------------------
 */
/*}}}*/


/**
 * Creates an individual lock file or removes it.
 * This is not a *nix lock file. Just a file and if the file exists e.g. other
 * processes should not go on to avoid conflicts.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Lock
 */
class Mumsys_Lock extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '3.0.0';

    /**
     * Location to the lock file. Default in "/tmp/Mumsys_Lock_default.lock"
     *
     * @var string
     */
    private $_file = '/tmp/Mumsys_Lock.php_default.lock';


    /**
     * Initialize the object.
     *
     * @param string $file Location to lock file
     */
    public function __construct( $file='' )
    {
        if ($file) {
            $this->_file = (string)$file;
        }
    }

    /**
     * Lock a situation.
     *
     * @return boolean Returns true if the lock was set
     * @throws Mumsys_Exception Throws exception if creation of lock file failt.
     */
    public function lock()
    {
        if ( file_exists($this->_file) ) {
            $msg = sprintf('Can not lock! Lock "%1$s" exists', $this->_file);
            throw new Mumsys_Exception($msg);
        }

        if ( !@touch($this->_file) ) {
            $message = sprintf('Locking failt for file "%1$s"', $this->_file);
            throw new Mumsys_Exception($message);
        }

        return true;
    }


    /**
     * Unlock a locked situation.
     *
     * @return boolean Returns true on success
     * @throws Mumsys_Exception Throws exception in unlock fails
     */
    public function unlock()
    {
        if ( file_exists($this->_file) ) {
            if ( !@unlink($this->_file) ) {
                $message = sprintf('Unlock failt for: "%1$s"', $this->_file);
                throw new Mumsys_Exception($message);
            }
        }

        return true;
    }


    /**
     * Returns the status if a lock exists or not.
     *
     * @return boolean Returns true if lock exists or false for no lock.
     */
    public function isLocked()
    {
        if ( file_exists($this->_file) ) {
            return true;
        }
        return false;
    }

}
