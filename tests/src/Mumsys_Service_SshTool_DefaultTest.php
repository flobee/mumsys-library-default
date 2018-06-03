<?php

/**
 * Mumsys_Service_SshTool_DefaultTest
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
 * Mumsys_Service_SshTool_Default Test
 * Generated on 2018-02-10 at 19:07:54.
 */
class Mumsys_Service_SshTool_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Service_SshTool_Default
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
            'Mumsys_Service_SshTool_Default' => $this->_version,
            'Mumsys_Abstract' => '3.0.2',
        );

        $basePath = MumsysTestHelper::getTestsBaseDir();
        $this->_sshFile = $basePath . '/testfiles/Service/Ssh/ssh-config-generated';
        $this->_pathConfigs = $basePath . '/testfiles/Service/Ssh/Config/conffiles';
        $this->_testConfigFile = $this->_pathConfigs . '/localhost.php';
        $this->_pathEmptyDir = $basePath . '/testfiles/Service/Ssh/Config/empty';
        $this->_dynTestFile = '';

        $this->_object = new Mumsys_Service_SshTool_Default(
            $this->_pathConfigs, $this->_sshFile
        );
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

        if ( $this->_dynTestFile && file_exists( $this->_dynTestFile ) ) {
            unlink( $this->_dynTestFile );
        }

        $this->_object = null;
    }


    public function testVersions()
    {
        $this->assertEquals(
            $this->_version,
            Mumsys_Service_SshTool_Default::VERSION
        );

        $this->_checkVersionList(
            $this->_object->getVersions(), $this->_versions
        );
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::__construct
     * @covers Mumsys_Service_SshTool_Default::_checkPath
     */
    public function test_construct()
    {

        $objectA = new Mumsys_Service_SshTool_Default(
            $this->_pathConfigs, $this->_sshFile
        );
        $objectB = new Mumsys_Service_SshTool_Default( $this->_pathConfigs );

        $this->assertInstanceOf( 'Mumsys_Service_SshTool_Default', $objectA );
        $this->assertInstanceOf( 'Mumsys_Service_SshTool_Default', $objectB );
        $this->assertInstanceOf( 'Mumsys_Abstract', $objectA );

        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Given config file path not found)/i' );
        $objectA = new Mumsys_Service_SshTool_Default(
            $this->_pathConfigs, $this->_sshFile . '/not/exists'
        );
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::init
     * @covers Mumsys_Service_SshTool_Default::_loadConfigs
     */
    public function testInit()
    {
        $this->assertNull( $this->_object->init() );
        $this->assertNull( $this->_object->init() ); // 4CC
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::init
     * @covers Mumsys_Service_SshTool_Default::_loadConfigs
     */
    public function testInitException1()
    {
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Config file not found)/' );
        $this->_dynTestFile = $this->_pathEmptyDir . '/test.php';
        touch( $this->_dynTestFile );
        chmod( $this->_dynTestFile, 0222 );
        $this->_object->setConfigsPath( $this->_pathEmptyDir );
        $this->_object->init();
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::setConfigsPath
     */
    public function testSetConfigsPath()
    {
        $this->_object->setConfigsPath( $this->_pathConfigs );
        // in create() we will test deeply
        $this->assertTrue( file_exists( $this->_testConfigFile ) );

        // test exception
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Configs paths not found)/' );
        $this->_object->setConfigsPath( '~/.ssh/conffiles' );
    }


    /**
     * Exception and 4CC
     * @covers Mumsys_Service_SshTool_Default::setConfigsPath
     */
    public function testSetConfigsPathException()
    {
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Configs paths not found)/' );
        $this->_object->setConfigsPath();
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::setConfigFile
     * @covers Mumsys_Service_SshTool_Default::_checkPath
     */
    public function testSetConfigFile()
    {
        $this->_object->setConfigFile( $this->_sshFile );

        $this->_object->create();
        // in create() we will test deeply
        $this->assertTrue( file_exists( $this->_sshFile ) );

        // test exception 1
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Path does not exists)/' );
        $this->_object->setConfigFile( $this->_sshFile . '/not/exists' );
        $this->_object->create();
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::setConfigFile
     * @covers Mumsys_Service_SshTool_Default::_checkPath
     */
    public function testSetFileCheckPathException()
    {
        $this->expectException( 'Mumsys_Service_Exception' );
        $this->expectExceptionMessageRegExp( '/(Path not writable)/' );
        $this->_object->setConfigFile( '/root/testsshfile' );
        $this->_object->create();
    }


    /**
     * @covers Mumsys_Service_SshTool_Default::setMode
     */
    public function testSetMode()
    {
        $this->_object->setMode( 0666 );
        $this->_object->create();
        // in create() we will test deeply
        $this->assertTrue( file_exists( $this->_sshFile ) );

        $expected = 100666;
        $actual = decoct( fileperms( $this->_sshFile ) );
        $this->assertEquals( $expected, $actual );
    }


    /**
     * Test to create a ssh config file.
     *
     * @covers Mumsys_Service_SshTool_Default::create
     * @covers Mumsys_Service_SshTool_Default::_configToString
     * @covers Mumsys_Service_SshTool_Default::_getIdentityLocation
     */
    public function testCreateAction()
    {
        $this->_object->init();
        $this->_object->create();

        $this->assertTrue( file_exists( $this->_sshFile ) );

        $expectedA = '# localhost' . "\n"
            . 'Host localhost' . "\n"
            . 'HostName localhost' . "\n"
            . 'Port 22' . "\n"
            . 'IdentityFile ~/.ssh/id_rsa' . "\n"
            . 'PreferredAuthentications publickey' . "\n"
            . 'Protocol 2' . "\n"
            . "\n"
            . "# otherhost\n"
            . "Host otherhost\n"
            . "# HostName otherhost.com\n"
            . "Port 22\n"
            . "User otheruser\n"
            . "IdentityFile ./path/to/my/global/id/file\n"
            . "PreferredAuthentications publickey\n"
            . "Protocol 2\n"
            . "\n"
            . "# secondhost\n"
            . "Host secondhost\n"
            . "Port 22\n"
            . "IdentityFile /goes/there/id_key\n"
            . "PreferredAuthentications publickey\n"
            . "Protocol 2\n"
            . "\n"
        ;
        $actualA = file_get_contents( $this->_sshFile );

        ob_start();
        $this->_object->create( true );
        $actualB = ob_get_clean();
        $expectedB = '# output for: ' . $this->_sshFile . PHP_EOL
            . $expectedA . PHP_EOL;

        $this->assertEquals( $expectedA, $actualA );
        $this->assertEquals( $expectedB, $actualB );
    }

     /**
     * Test to create a ssh config file.
     *
     * @covers Mumsys_Service_SshTool_Default::deploy
     * @covers Mumsys_Service_SshTool_Default::_deployExecute
     */
    public function testDeployAction()
    {
        $this->_object->init();

        ob_start();
        $this->_object->deploy();
        $actualA = ob_get_clean();
        $expectedA = ''
            . "scp ~/.ssh/id_rsa otheruser@otherhost:~/.ssh/id_rsa\n"
            . "scp ~/.ssh/id_rsa.pub otheruser@otherhost:~/.ssh/id_rsa.pub\n"
            . "scp /simple/test/copy/this/file otheruser@otherhost:/simple/test/copy/this/file\n"
            . "scp ~/.ssh/* otheruser@otherhost:~/.ssh\n"
            . "scp /this/keyfile otheruser@otherhost:~/.ssh/id_rsa\n"
            . "scp /this/keyfile.pub otheruser@otherhost:~/.ssh/id_rsa.pub\n"
            . "scp ~/.ssh/id_rsa flobee@secondhost:/goes/here\n"
            . "scp ~/.ssh/id_rsa.pub flobee@secondhost:/goes/here.pub\n"
            . "scp /this/id_rsa flobee@secondhost:/goes/there/id_key\n"
            . "scp /this/id_rsa.pub flobee@secondhost:/goes/there/id_key.pub\n"
            . "scp ~/.ssh/* flobee@secondhost:~/.ssh/keys/from/localhost\n"
        ;

        $this->assertEquals( $expectedA, $actualA );
    }

}