<?php

/**
 * Mumsys_Unittest_Testcase
 * for MUMSYS / Multi User Management System (MUMSYS)
 *
 * @license GPL Version 3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Unittest
 */


/**
 * PhpUnit test case class as wrapper for PHPUnit_Framework_TestCase.
 *
 * Helper for deprecated or removed methodes to keep you informed.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Unittest
 */
class Mumsys_Unittest_Testcase
    extends PHPUnit\Framework\TestCase
{
    /**
     * Version ID information.
     */
    const VERSION = '3.3.2';

    /**
     * Methods memory container
     * @var array
     */
    private static $_methods = array();


    /**
     * Checks for available class versions.
     *
     * Check for Mumsys_Abstract::getVersions()
     *
     * @param array $allList List of loaded class versions @see
     * Mumsys_Abstract::getVersions()
     * @param array $myList List of expected versions
     *
     * @return boolean Returns true on success
     */
    protected function _checkVersionList( $allList, $myList )
    {
        foreach ( $myList as $className => $version ) {
            $test = ( $allList[$className] === $version );
            $message = 'Failure: ' . $className . ':' . $version . ' !== ' . $allList[$className];
            $this->assertTrue( $test, $message );
        }

        return true;
    }


    /**
     * Checks for available classes.
     *
     * @param array $list List of classes, interfaces, abstract classes to be
     * checked
     * @param array $myList List of expected classes, interfaces...
     * @return boolean True on success
     */
    protected function _checkClassList( $list, $myList )
    {
        foreach ( $myList as $className ) {
            $this->assertTrue(
                isset( $list[$className] ), $className . ' not found/ exists'
            );
        }

        return true;
    }

}
