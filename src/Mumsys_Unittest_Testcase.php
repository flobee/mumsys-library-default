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
     * Checks PhpUnit setExpectedException() if available.
     *
     * @deprecated since version 5.2.0
     *
     * @param string $exceptionName
     * @param string $exceptionMessage
     * @param integer|string $exceptionCode
     *
     * @throws Exception
     */
    public function setExpectedException( $exceptionName, $exceptionMessage = '', $exceptionCode = null )
    {
        $message = 'setExpectedException() will be removed with phpunit > 5.9*.'
            . 'Please use expectException*()';

        self::_checkMethod('setExpectedException', $message);
    }


    /**
     * Checks for exceptions with regular expression message.
     *
     * @param string $exception Exception to be tested. Default "Exception"
     * @param string $regex Regular expression
     * @param string|integer $exCode Exception code
     *
     * @throws Exception If methode/s not exists
     */
    public function setExpectedExceptionRegExp( $exception = 'Exception',
        $regex = '/(.*)/i', $exCode = null )
    {
         $message = 'setExpectedExceptionRegExp() removed since phpunit '
            . '>= 5.6.0. Use expectException*() methodes';
        if( self::_checkMethod( 'setExpectedExceptionRegExp', $message ) )
        {
            if( self::_checkMethod( 'expectException', $message ) )
            {
                $this->expectException( $exception );
                $this->expectExceptionMessageRegExp( $regex );
                if ( isset( $exCode ) ) {
                    $this->expectExceptionCode( $exCode );
                }
            } else {
                $this->setExpectedExceptionRegExp( $exception, $regex, $exCode );
            }
		}
		else {
            throw new Exception( $message );
        }
    }


    /**
     * Checks PhpUnit assertType() if available.
     *
     * Available from PHPUnit <= 3.5
     *
     * @param mixed $expected Expected value
     * @param mixed $actual Actual value
     * @param string $message Message to print if assertion is wrong
     *
     * @throws Exception If assertType() is not available
     */
    public function assertType( $expected, $actual, $message = '' )
    {
        $message = 'assertType() was removed since phpunit 3.5*. You may check with assertInternalType()';
        self::_checkMethod('assertType', $message);
    }


    /**
     * Checks PhpUnit hasPerformedExpectationsOnOutput() if available.
     */
    public function hasPerformedExpectationsOnOutput()
    {
        $message = 'hasPerformedExpectationsOnOutput() will be removed in the future.'
            . 'Marked as deprecated since Release (found 5.4.*)';
        self::_checkMethod('hasPerformedExpectationsOnOutput', $message);
    }


    /**
     * Checks PhpUnit getMockWithoutInvokingTheOriginalConstructor() if available.
     *
     * @param string $originalClassName Class name
     */
    public function getMockWithoutInvokingTheOriginalConstructor( $originalClassName )
    {
        $message = 'getMockWithoutInvokingTheOriginalConstructor() will be
            removed in the future. Deprecated since Release 5.4.0';

        self::_checkMethod('getMockWithoutInvokingTheOriginalConstructor', $message);
    }


    /**
     * Checks if given method exists in current version of PHPUnit.
     *
     * @param string $method Method to check if exists
     */
    private static function _checkMethod( $method, $message )
    {
        if ( !isset(self::$_methods[$method]) ) {
            self::$_methods[$method] = method_exists('PHPUnit_Framework_TestCase', $method);
        }

        if ( self::$_methods[$method] ) {
            parent::markTestIncomplete($message);
        } else {
            throw new Exception($message);
        }

        return self::$_methods[$method];
    }


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
    protected function _checkVersionList($allList, $myList )
    {
        foreach($myList as $className => $version ) {
            $test = ($allList[$className] === $version);
            $message = 'Failure: ' . $className . ':' . $version . ' !== ' . $allList[$className];
            $this->assertTrue($test, $message);
        }

        return true;
    }

}
