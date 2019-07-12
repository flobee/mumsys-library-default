<?php

/**
 * Mumsys_Service_Spss_AbstractTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 * @version     1.0.0
 * Created: 2017-11-30
 */

class Mumsys_Service_Spss_AbstractTestClass
    extends Mumsys_Service_Spss_Abstract
{

}


/**
 * Mumsys_Service_Spss_Abstract Test
 */
class Mumsys_Service_Spss_AbstractTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Service_Spss_Abstract
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $spssFile = __DIR__ . '/../testfiles/Service/Spss/pspp.sav';
        $parser = \SPSS\Sav\Reader::fromString( file_get_contents( $spssFile ) );
        $this->_object = new Mumsys_Service_Spss_AbstractTestClass( $parser );
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        $this->_object = null;
    }


    /**
     * @covers Mumsys_Service_Spss_Abstract::__construct
     */
    public function test__construct()
    {
        $spssFile = __DIR__ . '/../testfiles/Service/Spss/pspp.sav';
        $parser = \SPSS\Sav\Reader::fromString( file_get_contents( $spssFile ) );
        $this->_object = new Mumsys_Service_Spss_AbstractTestClass( $parser );

        $this->expectException( 'Mumsys_Service_Spss_Exception' );
        $this->expectExceptionMessage( 'Invalid Reader/Writer instance' );
        new Mumsys_Service_Spss_AbstractTestClass( '' );
    }


    /**
     * @covers Mumsys_Service_Spss_Abstract::getAdapter
     */
    public function testGetInterface()
    {
        $actual = $this->_object->getAdapter();
        $this->assertInstanceOf( 'SPSS\Sav\Reader', $actual );

    }

}
