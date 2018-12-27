<?php

/**
 * Mumsys_Service_Ssh_Config_Generator_DefaultTest
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2018 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Abstract
 * created: 2018-02-10
 */


/**
 * @deprecated is the first but old version! use Mumsys_Service_SshTool_Default/Tests !!!
 */


/**
 * Mumsys_Service_Ssh_Config_Generator_Default Test
 * Generated on 2018-02-10 at 19:07:54.
 */
class Mumsys_Service_Ssh_Config_Generator_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Service_Ssh_Config_Generator_Default
     */
    protected $_object;

    private $_version;
    private $_versions;
    private $_sshFile;
    private $_pathConfigs;
    private $_testConfigFile;
    private $_pathEmptyDir;
    private $_dynTestFile;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '1.0.0';
        $this->_versions = array(
            'Mumsys_Service_Ssh_Config_Generator_Default' => $this->_version,
            'Mumsys_Abstract' => '3.0.2',
        );

        $basePath = MumsysTestHelper::getTestsBaseDir();
        $this->_sshFile = $basePath . '/tmp/ssh-config-generated';
        $this->_pathConfigs = $basePath . '/testfiles/Service/Ssh/Config/conffiles';
        $this->_testConfigFile = $this->_pathConfigs . '/localhost.php';
        $this->_pathEmptyDir = $basePath . '/testfiles/Service/Ssh/Config/empty';
        $this->_dynTestFile = '';

        $this->_object = new Mumsys_Service_Ssh_Config_Generator_Default();
        $this->_object->setFile( $this->_sshFile );
        $this->_object->setConfigsPath( $this->_pathConfigs );
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if ( file_exists( $this->_sshFile ) ) {
            unlink( $this->_sshFile );
        }
        if ( file_exists( $this->_dynTestFile ) ) {
            unlink( $this->_dynTestFile );
        }

        $this->_object = null;
    }


    public function testVersions()
    {
        $this->assertEquals(
            $this->_version,
            Mumsys_Service_Ssh_Config_Generator_Default::VERSION
        );

        $this->_checkVersionList(
            $this->_object->getVersions(), $this->_versions
        );
    }


    /**
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::__construct
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::_checkPath
     */
    public function test_construct()
    {
        $object = new Mumsys_Service_Ssh_Config_Generator_Default();
        $this->assertInstanceOf(
            'Mumsys_Service_Ssh_Config_Generator_Default', $object
        );
        $this->assertInstanceOf( 'Mumsys_Abstract', $object );
    }


    /**
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::setConfigsPath
     */
    public function testSetConfigsPath()
    {
        $this->_object->setConfigsPath( $this->_pathConfigs );
        // in run() we will test deeply
        $this->assertTrue( file_exists( $this->_testConfigFile ) );
    }


    /**
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::setFile
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::_checkPath
     */
    public function testSetFile()
    {
        $this->_object->setFile( $this->_sshFile );

        $this->_object->run();
        // in run() we will test deeply
        $this->assertTrue( file_exists( $this->_sshFile ) );

        // test exception 1
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Path does not exists)/' );
        $this->_object->setFile( $this->_sshFile . '/not/exists' );
        $this->_object->run();
    }


    /**
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::setFile
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::_checkPath
     */
    public function testSetFileCheckPathException()
    {
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Path not writable)/' );
        $this->_object->setFile( '/root/testsshfile' );
        $this->_object->run();
    }


    /**
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::setMode
     */
    public function testSetMode()
    {
        $this->_object->setMode( 0666 );
        $this->_object->run();
        // in run() we will test deeply
        $this->assertTrue( file_exists( $this->_sshFile ) );

        $expected = 100666;
        $actual = decoct( fileperms( $this->_sshFile ) );
        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::run
     * @covers Mumsys_Service_Ssh_Config_Generator_Default::_configToString
     */
    public function testRun()
    {
        $this->_object->setFile( $this->_sshFile );

        $this->_object->run();
        // in run() we will test deeply
        $this->assertTrue( file_exists( $this->_sshFile ) );

        $expected = '# localhost' . "\n"
            . 'Host localhost' . "\n"
            . 'HostName localhost' . "\n"
            . 'Port 22' . "\n"
            . 'IdentityFile ~/.ssh/id_rsa' . "\n"
            . 'PreferredAuthentications publickey' . "\n"
            . 'Protocol 2' . "\n"
            . "\n";
        $actual = file_get_contents( $this->_sshFile );
        $this->assertEquals( $expected, $actual );

        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Config file not found)/' );
        $this->_dynTestFile = $this->_pathEmptyDir . '/test.php';
        touch($this->_dynTestFile);
        chmod($this->_dynTestFile, 0222);
        $this->_object->setConfigsPath( $this->_pathEmptyDir);
        $this->_object->run();
    }

}
