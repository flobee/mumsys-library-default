<?php


/**
 * Unittest test case class phpunit >= phpunit 3.4 and 3.6
 */
class Mumsys_Unittest_Testcase
    extends PHPUnit_Framework_TestCase
    // extends PHPUnit\Framework\TestCase
{
    private static $_methodExists = array();


    /**
     * Checks if given method exists in current version of PHPUnit.
     *
     * @param string $method Method to check
     * @return boolean
     */
    private static function _checkMethod( $method )
    {
        if ( !isset(self::$_methodExists[$method]) ) {
            self::$_methodExists[$method] = method_exists('PHPUnit_Framework_TestCase', $method);
        }

        return self::$_methodExists[$method];
    }

    /**
     * Calls setExpectedException() if available.
     *
     * @deprecated since version 5.2.0
     *
     * @param string $exceptionName
     * @param string $exceptionMessage
     * @param integer|string $exceptionCode
     * @throws Exception
     */
    public function setExpectedException( $exceptionName, $exceptionMessage = '', $exceptionCode = null )
    {
        if ( self::_checkMethod('setExpectedException') ) {
            parent::setExpectedException($exceptionName, $exceptionMessage, $exceptionCode);
        } else {
            // no fallback at the moment!
            throw new Exception(
                'setExpectedException() is removed since phpunit >= 5.9*?. Use setExpectedExceptionRegExp()'
            );
        }
    }


    /**
     * Calls assertType() of parent class if available.
     * Available from PHPUnit <= 3.5
     *
     * @param mixed $expected Expected value
     * @param mixed $actual Actual value
     * @param string $message Message to print if assertion is wrong
     * @throws Exception If assertType() method is not available
     */
    public static function assertType( $expected, $actual, $message = '' )
    {
        if ( self::_checkMethod('assertType') ) {
            parent::assertType($expected, $actual, $message);
        } else {
            throw new Exception('assertType() is removed since phpunit >= 3.5');
        }
    }


    /**
     * Calls assertType() or assertInternalType() depending on the PHPUnit version.
     * Available from PHPUnit >= 3.5
     *
     * @param string $expected Expected value
     * @param mixed $actual Actual value
     * @param string $message Message to print if assertion is wrong
     */
    public static function assertInternalType( $expected, $actual, $message = '' )
    {
        if ( self::_checkMethod('assertInternalType') ) {
            parent::assertInternalType($expected, $actual, $message);
        } else {
            parent::assertType($expected, $actual, $message);
        }
    }


    /**
     * Calls assertType() or assertInstanceOf() depending on the PHPUnit version.
     * Available from PHPUnit >= 3.5
     *
     * @param string $expected Expected value
     * @param mixed $actual Actual value
     * @param string $message Message to print if assertion is wrong
     */
    public static function assertInstanceOf( $expected, $actual, $message = '' )
    {
        if ( self::_checkMethod('assertInstanceOf') ) {
            parent::assertInstanceOf($expected, $actual, $message);
        } else {
            parent::assertType($expected, $actual, $message);
        }
    }


    /**
     * Calls assertEmpty() or assertThat() depending on the PHPUnit version.
     * Available from PHPUnit >= 3.5
     *
     * @param mixed $actual Actual value
     * @param string $message Message to print if assertion is wrong
     */
    public static function assertEmpty( $actual, $message = '' )
    {
        if ( self::_checkMethod('assertEmpty') ) {
            parent::assertEmpty($actual, $message);
        } else {
            parent::assertThat($actual, parent::isEmpty(), $message);
        }
    }

}