<?php


/**
 * Test class for Mumsys_Multirename.
 */
class Mumsys_MultirenameTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Mumsys_Multirename
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_testsDir = realpath(dirname(__FILE__) . '/../');

        $_SERVER['HOME'] = $this->_testsDir . '/tmp';
        @touch($this->_testsDir . '/tmp/multirenametestfile');
        $this->_config = array(
            'program',
            'path' => $this->_testsDir . '/tmp',
            'fileextensions' => '*',
            'substitutions' => 'doNotFind=doNotReplace;regex:/doNotFind/i',
            'loglevel' => 7,
        );
        $this->_oFiles = new Mumsys_FileSystem();
        $opts = array('logfile' => $this->_testsDir . '/tmp/test_' . basename(__FILE__) . '.log');
        $this->_logger = new Mumsys_Logger($opts);
        $this->_object = new Mumsys_Multirename($this->_config, $this->_oFiles, $this->_logger);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        @unlink($this->_config['path'].'/.multirename/config');
        @unlink($this->_config['path'].'/.multirename/collection');
        @unlink($this->_config['path'].'/.multirename/lastactions');
        @unlink($this->_config['path'].'/multirenametestfile');
        @rmdir($this->_config['path'].'/.multirename/');
    }


    /**
     * Test and also fill data for the code coverage.
     *
     * @covers Mumsys_Multirename::__construct
     * @covers Mumsys_Multirename::setSetup
     * @covers Mumsys_Multirename::_buildSubstitutions
     * @covers Mumsys_Multirename::showConfig
     * @covers Mumsys_Multirename::_mkConfigDir
     * @covers Mumsys_Multirename::_trackConfigDir
     * @covers Mumsys_Multirename::_getCollection
     * @covers Mumsys_Multirename::_setCollection
     */
    public function testConstructor()
    {
        $this->_config['allowRoot'] = false;
        $this->_config['undo'] = true;
        $this->_config['del-config'] = true;
        $this->_config['set-config'] = true;
        $this->_config['show-config'] = true;
        $this->_object = new Mumsys_Multirename($this->_config, $this->_oFiles, $this->_logger);
        $this->assertInstanceOf('Mumsys_Multirename', $this->_object);

        $this->_config['allowRoot'] = true;
        $message = 'Something which belongs to "root" is forbidden until enough '
            . 'tests are present and this program is no alpha version anymore! '
            . 'Sorry! Use a different user!' . PHP_EOL;
        $this->setExpectedException('Mumsys_Multirename_Exception', $message);
        $this->_object = new Mumsys_Multirename($this->_config, $this->_oFiles, $this->_logger);
    }

    /**
     * @covers Mumsys_Multirename::__construct
     * @covers Mumsys_Multirename::getVersion
     * @covers Mumsys_Multirename::showVersion
     * @covers Mumsys_Abstract
     * @covers Mumsys_Multirename::getVersionLong
     * @covers Mumsys_Multirename::getVersionShort
     */
    public function testConstructorGetShowVersion()
    {
        ob_start();
        $this->_config['version'] = true;
        $this->_object = new Mumsys_Multirename($this->_config, $this->_oFiles, $this->_logger);
        $current = ob_get_clean();
        // this needs in the single test
        $expected = array(
            'multirename ' . Mumsys_Multirename::VERSION . ' by Florian Blasel' . PHP_EOL. PHP_EOL,
            'Mumsys_Abstract                     ' . Mumsys_Abstract::VERSION . PHP_EOL,
            'Mumsys_FileSystem_Common_Abstract   ' . Mumsys_FileSystem_Common_Abstract::VERSION . PHP_EOL,
            'Mumsys_FileSystem                   ' . Mumsys_FileSystem::VERSION . PHP_EOL,
            'Mumsys_Logger                       ' . Mumsys_Logger::VERSION . PHP_EOL,
            'Mumsys_File                         ' . Mumsys_File::VERSION . PHP_EOL,
            'Mumsys_Multirename                  ' . Mumsys_Multirename::VERSION . PHP_EOL,
        );

        $current2 = $this->_object->getVersionID();
        $expected2 = Mumsys_Multirename::VERSION;

        $current3 = $this->_object->getVersion();
        $expected3 = 'Mumsys_Multirename ' . Mumsys_Multirename::VERSION;

        foreach ($expected as $toCheck) {
            $this->assertTrue( (preg_match('/'.$toCheck.'/im', $current) ? true : false) );
        }
        $this->assertEquals($expected2, $current2);
        $this->assertEquals($expected3, $current3);
        $this->assertInstanceOf('Mumsys_Multirename', $this->_object);
    }


    /**
     * @covers Mumsys_Multirename::setSetup
     * @covers Mumsys_Multirename::setConfig
     * @covers Mumsys_Multirename::getConfig
     * @covers Mumsys_Multirename::_mkConfigDir
     */
    public function testSetSetup()
    {
        $config = $this->_config;
        $config += array(
            'keepcopy' => true,
            'hidden' => false,
            'test' => false,
            'link' => 'soft;rel',
            'linkway' => 'rel',
            'recursive' => true,
            'sub-paths' => true,
            'find' => 'a;c;t',
            'history' => true,
        );
        $actual1 = $this->_object->setSetup($config);
        $expected1 = $config;
        $expected1['fileextensions'] = array('*');
        $expected1['link'] = 'soft';
        $expected1['linkway'] = 'rel';
        $expected1['find'] = array('a','c','t');

        // from config test + hidden=true
        $this->_object->setConfig($this->_config['path']);
        $config['from-config'] = $this->_config['path'];
        $config['hidden'] = true;
        $actual2 = $this->_object->setSetup($config);
        $expected2 = $expected1;
        $expected2['from-config'] = $config['from-config'];
        $expected2['hidden'] = $config['hidden'];

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);

        // config dir error
        $msg = 'Invalid --from-config <your value> parameter. Path not found';
        $this->setExpectedException('Mumsys_Multirename_Exception', $msg);
        $config['from-config'] = $this->_testsDir . '/tmp/dirNotExists';
        $this->_object->setSetup($config);
    }

    /**
     * @covers Mumsys_Multirename::setSetup
     * @covers Mumsys_Multirename::delConfig
     */
    public function testSetSetupException1()
    {
        // config not exists error
        $config = $this->_config;
        $config['from-config'] = $this->_config['path'];
        $this->_object->delConfig($this->_config['path']);

        $msg = 'Could not read from-config in path: "'.$this->_testsDir . '/tmp"';
        $this->setExpectedException('Mumsys_Multirename_Exception', $msg);
        $this->_object->setSetup($config);
    }

    /**
     * @covers Mumsys_Multirename::setSetup
     */
    public function testSetSetupException2()
    {
        $config = $this->_config;
        unset($config['path']);
        $msg = 'Invalid --path <your value>';
        $this->setExpectedException('Mumsys_Multirename_Exception', $msg);
        $this->_object->setSetup($config);
    }

    /**
     * @covers Mumsys_Multirename::setSetup
     */
    public function testSetSetupException3()
    {
        $config = $this->_config;
        $config['test'] = 'yes';
        $msg = 'Invalid --test value';
        $this->setExpectedException('Mumsys_Multirename_Exception', $msg);
        $this->_object->setSetup($config);
    }

    /**
     * @covers Mumsys_Multirename::setSetup
     */
    public function testSetSetupException4()
    {
        $config = $this->_config;
        unset($config['fileextensions'],$config['undo']);
        $msg = 'Missing --fileextensions "<your value/s>"';
        $this->setExpectedException('Mumsys_Multirename_Exception', $msg);
        $this->_object->setSetup($config);
    }

    /**
     * @covers Mumsys_Multirename::setSetup
     */
    public function testSetSetupException5()
    {
        $config = $this->_config;
        unset($config['substitutions']);
        $msg = 'Missing --substitutions "<your value/s>"';
        $this->setExpectedException('Mumsys_Multirename_Exception', $msg);
        $this->_object->setSetup($config);
    }



    /**
     * @covers Mumsys_Multirename::run
     * @covers Mumsys_Multirename::_getRelevantFiles
     * @covers Mumsys_Multirename::_buildPathBreadcrumbs
     * @covers Mumsys_Multirename::_substitutePaths
     * @covers Mumsys_Multirename::setActionHistory
     * @covers Mumsys_Multirename::undo
     * @covers Mumsys_Multirename::_undoRename
     * @covers Mumsys_Multirename::_undoTest
     */
    public function testRunAndUndo()
    {
        // test mode, mostly run through everything with is possible for max. code coverage
        $config = array(
            'test' => true,
            'fileextensions' => ';log;*',
            'keepcopy' => false,
            'hidden' => true,
            'recursive' => true,
            'sub-paths' => true,
            // @covers Mumsys_Multirename::_substitutePaths for 100% code coverage
            'substitutions' => 'm=XX;XX=X%path1%X;regex:/%path1%/i=xTMPx;regex:/xTMPx/i=%path1%;%path1%=xTMPx',
            'find' => 'm;regex:/m/i',
            'history' => true,
        );
        $config += $this->_config;

        $this->_object->setSetup($config);
        $this->_object->run();

        // -- test rename the same (walk through the code for code coverage)
        $config['substitutions'] = 'multirenametestfile=multirenametestfile';
        $this->_object->setSetup($config);
        $this->_object->run();

        // do rename now
        $config['substitutions'] = 'multirenametestfile=unittest_testfile';
        $config['find'] = 'multirenametestfile';
        $config['test'] = false;
        $this->_object->setSetup($config);
        $this->_object->run();

        $this->assertTrue(file_exists($this->_testsDir . '/tmp/unittest_testfile'));

        // do test undo rename
        $config['test'] = true;
        $this->_object->setSetup($config);
        $this->_object->undo($this->_config['path']);


        // do undo rename but target already exists
        @copy($this->_testsDir . '/tmp/unittest_testfile', $this->_testsDir . '/tmp/multirenametestfile');
        $config['test'] = false;
        $this->_object->setSetup($config);
        $this->_object->undo($this->_config['path'], true);

        $expected1 = !file_exists($this->_testsDir . '/tmp/unittest_testfile');
        $expected2 = file_exists($this->_testsDir . '/tmp/multirenametestfile.1');


        $this->_object->undo($this->_config['path'], false);

        $expected3 = !file_exists($this->_testsDir . '/tmp/unittest_testfile');
        $expected4 = file_exists($this->_testsDir . '/tmp/multirenametestfile');

        @unlink($this->_testsDir . '/tmp/multirenametestfile.1');

        $this->assertTrue($expected1);
        $this->assertTrue($expected2);
        $this->assertTrue($expected3);
        $this->assertTrue($expected4);
    }


    /**
     * @covers Mumsys_Multirename::run
     * @covers Mumsys_Multirename::_getRelevantFiles
     * @covers Mumsys_Multirename::_buildPathBreadcrumbs
     * @covers Mumsys_Multirename::_substitutePaths
     * @covers Mumsys_Multirename::setActionHistory
     * @covers Mumsys_Multirename::undo
     * @covers Mumsys_Multirename::_undoRename
     * @covers Mumsys_Multirename::_undoTest
     * @covers Mumsys_Multirename::_undoLink
     */
    public function testRunAndUndoForLinks()
    {
        $config = array(
            'test' => false,
            'link' => 'soft;abs',
            'fileextensions' => ';log',
            'keepcopy' => true,
            'recursive' => true,
            'substitutions' => 'multirenametestfile=unittest_testfile',
            'find' => 'multirenametestfile',
            'history' => true,
        );
        $config += $this->_config;

        // do rename now
        $this->_object->setSetup($config);
        $this->_object->run();
        //again, target exists and keepcopy
        // -- test
        $config['test'] = true;
        $this->_object->setSetup($config);
        $this->_object->run();
        // -- run
        $config['test'] = false;
        $this->_object->setSetup($config);
        $this->_object->run();
        $this->assertTrue(is_link($this->_testsDir . '/tmp/unittest_testfile'));
        $this->assertTrue(is_link($this->_testsDir . '/tmp/unittest_testfile.lnk'));
        @unlink($this->_testsDir . '/tmp/unittest_testfile'); //twice ->run() dublicate

        // undo link
        // -- test link undo
        $config['test'] = true;
        $this->_object->setSetup($config);
        $this->_object->undo($this->_config['path']);
        // -- do link undo
        $config['test'] = false;
        $this->_object->setSetup($config);
        // ---- error deleting link
        @chmod($this->_config['path'].'/', 0500);
        $this->_object->undo($this->_config['path']);
        // ---- deletes the link
        @chmod($this->_config['path'].'/', 0700);
        $this->_object->undo($this->_config['path']);

        $this->assertFalse(file_exists($this->_testsDir . '/tmp/unittest_testfile.lnk'));
        $this->assertTrue(file_exists($this->_testsDir . '/tmp/multirenametestfile'));

        // rename/ link exception for code coverage
        $config['substitutions'] = 'multirenametestfile=../home/unittest_testfile';
        $config['keepcopy'] = false;
        $this->_object->setSetup($config);
        $this->_object->run();
    }


    /**
     * Walk through the code for code coverage
     */
    public function testUndoHackInvalidMode()
    {
        // create config path
        $this->_object->setConfig($this->_config['path']);

        // no history
        $this->_object->undo($this->_config['path']);

        // invalid history
        $file = $this->_config['path'] . '/.multirename/lastactions';
        $history['mode'][$this->_testsDir . '/tmp/multirenametestfile'] = $this->_testsDir . '/tmp/unittest_testfile';
        $data = json_encode($history);
        $result = file_put_contents($file, $data);
        $this->_object->undo($this->_config['path']);
    }


    /**
     * Detais see at testSetSetup().
     *
     * @covers Mumsys_Multirename::getConfig
     */
    public function testGetConfig()
    {
        $actual1 = $this->_object->getConfig($this->_config['path']);
        $this->assertFalse($actual1);
    }


    /**
     * See at testSetSetup() for more tests.
     *
     * @covers Mumsys_Multirename::setConfig
     * @covers Mumsys_Multirename::_mkConfigDir
     * @covers Mumsys_Multirename::_trackConfigDir
     * @covers Mumsys_Multirename::_getCollection
     * @covers Mumsys_Multirename::_setCollection
     */
    public function testSetConfig()
    {
        $actual = $this->_object->setConfig($this->_config['path']);

        $this->assertTrue( (is_numeric($actual) && $actual>0) );

        $this->assertFalse( $this->_object->setConfig('/root/') );
    }


    /**
     * @covers Mumsys_Multirename::delConfig
     */
    public function testDelConfig()
    {
        $this->_object->setConfig($this->_config['path']);

        @chmod($this->_config['path'].'/.multirename/', 0500);
        $actual1 = $this->_object->delConfig($this->_config['path']);

        @chmod($this->_config['path'].'/.multirename/', 0700);
        $actual2 = $this->_object->delConfig($this->_config['path']);

        $this->assertFalse($actual1);
        $this->assertTrue($actual2);
    }


    /**
     * @covers Mumsys_Multirename::showConfig
     */
    public function testShowConfig()
    {
        $this->_object->logger->msgEcho = true;

        ob_start();
        $this->_object->showConfig();
        $output = ob_get_clean();

        $results = explode("\n", $output);
        $actual = $results[ count($results)-2 ];
        $expected = "multirename --path '".$this->_testsDir . "/tmp' --fileextensions '*' "
            . "--substitutions 'doNotFind=doNotReplace;regex:/doNotFind/i' "
            . "--loglevel '7'";

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Multirename::install
     */
    public function testInstall()
    {
        $this->_object = new Mumsys_Multirename($this->_config, $this->_oFiles, $this->_logger);
        $this->_object->install();

        $this->assertTrue(file_exists($this->_config['path']));

        $_SERVER['HOME'] = '/root/';
        $this->_object = new Mumsys_Multirename($this->_config, $this->_oFiles, $this->_logger);
        $message = 'Can not create dir: "/root/.multirename" mode: "755". Message: mkdir(): Permission denied';
        $this->setExpectedException('Mumsys_FileSystem_Exception', $message);
        $this->_object->install();
    }


    /**
     * @covers Mumsys_Multirename::getSetup
     */
    public function testGetSetup()
    {
        $actual = $this->_object->getSetup(true);
        $expected = $this->_object->getSetup(false);

        $this->assertEquals(count($expected), count($actual));
    }

}
