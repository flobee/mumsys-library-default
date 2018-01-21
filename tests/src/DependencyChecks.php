<?php


/**
 * Test class for Mumsys_Array2Xml.
 * $Id: Mumsys_Array2XmlTest.php 3254 2016-02-09 20:57:53Z flobee $
 */
class DependencyChecks
    extends Mumsys_Unittest_Testcase
{
    /**
     * List of required extensions.
     * @var array
     */
    private $_requiredExtensions = array();


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $production = 'production';
        $testing = 'testing/ unit tests';

        $this->_requiredExtensions = array(
            'ctype' => $production,
            'curl' => $production,
            'date' => $production,
            'exif' => $production,
            'fileinfo' => $production,
            'filter' => $production,
            'gd' => $production,
            'pdo' => $production,
            'pdo_mysql' => $production,
            'pdo_sqlite' => $production,
            'sqlite3' => $production,
            'json' => $production,
            'iconv' => $production,
            'mbstring' => $testing,
            'session' => $production,
            'SPL' => $production,
        );
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }


    public function test_CheckPhpExtensionsLoeaded()
    {
        foreach ( $this->_requiredExtensions as $ext => $usage) {
            $mesg = sprintf(
                '"%1$s" extension not installed/ found for: %2$s',
                $ext,
                $usage
            );
            $this->assertTrue( extension_loaded( $ext ), $mesg );
        }
    }

}
