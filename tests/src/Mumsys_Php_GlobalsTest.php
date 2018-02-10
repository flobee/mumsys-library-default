<?php


/**
 * Mumsys_Php_Globals Test
 */
class Mumsys_Php_GlobalsTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Php_Globals
     */
    protected $_object;

    /**
     * one! $_FILES upload
     * @var array
     */
    protected $_file;

    /**
     * serveral! $_FILES upload
     * @var array
     */
    protected $_files;
    /**
     * Version ID
     * @var string
     */
    private $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '2.0.0';
        // in
        $this->_file = $_FILES = array(
            'test' => array(
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'size' => 542,
                'tmp_name' => __DIR__ . '/../tmp/source-test.jpg',
                'error' => 0
            )
        );

        $this->_files = array();

        $this->_object = new Mumsys_Php_Globals;
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = NULL;
    }


    /**
     * @covers Mumsys_Php_Globals::getServerVar
     * @covers Mumsys_Php_Globals::_getEnvVar
     */
    public function testGetServerVar()
    {
        $expected1 = 'no address';
        $actual1 = $this->_object->getServerVar('REMOTE_ADDR', 'no address');

        $expected2 = 'phpunit';
        $actual2 = $this->_object->getServerVar('PHP_SELF', 'PHP_SELF');

        $this->assertEquals($expected1, $actual1);
        $this->assertRegExp('/(' . $expected2 . ')/i', $actual2);
    }


    /**
     * @covers Mumsys_Php_Globals::getEnvVar
     * @covers Mumsys_Php_Globals::_getEnvVar
     */
    public function testGetEnvVar()
    {
        $expected1 = 'no addr';
        $actual1 = $this->_object->getEnvVar('REMOTE_ADDR', 'no addr');

        $expected2 = $_SERVER['HOME'];
        $actual2 = $this->_object->getEnvVar('HOME', 'no home');

        $expected3 = $_ENV['LANGX'] = getenv('LANG');
        $actual3 = $this->_object->getEnvVar('LANGX', 'no lang');

        $expected4 = 'hello';
        putenv("HELLO=hello");
        $actual4 = $this->_object->getEnvVar('HELLO', getenv('HELLO'));

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
        $this->assertEquals($expected4, $actual4);
    }


    /**
     * @covers Mumsys_Php_Globals::getPostVar
     *
     * @runInSeparateProcess
     */
    public function testGetPostVar()
    {
        $expected1 = array();
        $actual1 = $this->_object->getPostVar();

        $expected2 = $_POST['HOME'] = 'unittest';
        $actual2 = $this->_object->getPostVar('HOME', 'no home');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @covers Mumsys_Php_Globals::getGetVar
     *
     * @runInSeparateProcess
     */
    public function testGetGetVar()
    {
        $expected1 = array();
        $actual1 = $this->_object->getGetVar();

        $expected2 = $_GET['HOME'] = 'unittest';
        $actual2 = $this->_object->getGetVar('HOME', 'no home');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @covers Mumsys_Php_Globals::getCookieVar
     *
     * @runInSeparateProcess
     */
    public function testGetCookieVar()
    {
        $expected1 = array();
        $actual1 = $this->_object->getCookieVar();

        $expected2 = $_COOKIE['HOME'] = 'unittest';
        $actual2 = $this->_object->getCookieVar('HOME', 'no home');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @covers Mumsys_Php_Globals::getFilesVar
     * @runInSeparateProcess
     */
    public function testGetFilesVar()
    {
        $expected1 = array($this->_file['test']);
        $actual1 = $this->_object->getFilesVar('test', false);

        $expected2 = 'noFile';
        $actual2 = $this->_object->getFilesVar('noFile', $expected2);

        $expected3 = array('test'=> array($this->_file['test']));
        $actual3 = $this->_object->getFilesVar(null, false);

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
    }

    /**
     * @covers Mumsys_Php_Globals::getFilesVar
     * @runInSeparateProcess
     */
    public function testGetFilesVar2()
    {
        // some more files as "test[]" upload
        // in php standard style
        $_FILES = array(
            'test' => array(
                'name' => array(
                    'test.jpg',
                    'test2.jpg'
                ),
                'type' => array(
                    'image/jpeg',
                    'image/jpeg'
                ),
                'size' => array(
                    542,
                    543
                ),
                'tmp_name' => array(
                    __DIR__ . '/../tmp/source-test.jpg',
                    __DIR__ . '/../tmp/source-test2.jpg'
                ),
                'error' => array(0, 0)
            )
        );
        $expected4 = array(
            'test' => array(
                array(
                    'name' => 'test.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => __DIR__ . '/../tmp/source-test.jpg',
                    'error' => 0,
                    'size' => 542,
                ),
                array(
                    'name' => 'test2.jpg',
                    'type' => 'image/jpeg',
                    'tmp_name' => __DIR__ . '/../tmp/source-test2.jpg',
                    'error' => 0,
                    'size' => 543,
                ),
            )
        );
        $actual4 = $this->_object->getFilesVar(null, false);

        $this->assertEquals($expected4, $actual4);
    }


    /**
     * @covers Mumsys_Php_Globals::getGlobalVar
     */
    public function testGetGlobalVar()
    {
        $expected0 = $GLOBALS;
        $actual0 = $this->_object->getGlobalVar();

        $expected1 = 'no addr';
        $actual1 = $this->_object->getGlobalVar('REMOTE_ADDR', 'no addr');

        $expected2 = 'no home';
        $actual2 = $this->_object->getGlobalVar('HOME', 'no home');

        $expected3 = $GLOBALS['LANGUAGE'] = 'no lang';
        $actual3 = $this->_object->getGlobalVar('LANGUAGE', 'no lang');

        $this->assertEquals($expected0, $actual0);
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
    }


    /**
     * @covers Mumsys_Php_Globals::get
     * @runInSeparateProcess
     */
    public function testGet()
    {
        $expected1 = 'no addr';
        $actual1 = $this->_object->get('REMOTE_ADDR', 'no addr');

        $expected2 = $_SESSION['unittest-session'] = 'test';
        $actual2 = $this->_object->get('unittest-session', 'no test');

        $expected3 = 'files tests not implemented';
        $actual3 = $this->_object->get('unittest-file', $expected3);

        $expected4 = $_COOKIE['unittest-cookie'] = 'test';
        $actual4 = $this->_object->get('unittest-cookie', 'no cookie');

        $expected5 = $_REQUEST['unittest-request'] = 'test';
        $actual5 = $this->_object->get('unittest-request', 'no request');

        $expected6 = 'no get';
        $_GET['unittest-get'] = 'test';
        $actual6 = $this->_object->get('unittest-get', 'no get');

        $expected7 = $GLOBALS['unittest-global'] = 'test';
        $actual7 = $this->_object->get('unittest-global', 'no global');

        if (!isset($_SERVER['argv'][0]) ) {
            $_SERVER['argv'][0] = 'test get';
        }
        $expected8 = (is_array($_SERVER['argv']) && isset($_SERVER['argv'][0]) )  ? $_SERVER['argv'][0] : null;
        $actual8 = $this->_object->get(0, 'no argv');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
        $this->assertEquals($expected4, $actual4);
        $this->assertEquals($expected5, $actual5);
        $this->assertEquals($expected6, $actual6);
        $this->assertEquals($expected7, $actual7);
        $this->assertEquals($expected8, $actual8);
    }

    /**
     * @covers Mumsys_Php_Globals::getRemoteUser
     * @runInSeparateProcess
     */
    public function testGetRemoteUser()
    {
        $list = array('LOGNAME', 'USER', 'REMOTE_USER', 'PHP_AUTH_USER');

        foreach ( $list as $param ) {
            $_SERVER[$param] = null;
        }

        $this->assertEquals('unknown', $this->_object->getRemoteUser());
        $this->assertEquals('unknown', $this->_object->getRemoteUser()); // for 100% cc
    }


    /**
     * @covers Mumsys_Php_Globals::getRemoteUser
     * @runInSeparateProcess
     */
    public function testGetRemoteUserPHP_AUTH_USER()
    {
        $_SERVER['PHP_AUTH_USER'] = 'unittest';
        $this->assertEquals('unittest', $this->_object->getRemoteUser());
    }


    /**
     * @covers Mumsys_Php_Globals::getRemoteUser
     * @runInSeparateProcess
     */
    public function testGetRemoteUserREMOTE_USER()
    {
        $_SERVER['REMOTE_USER'] = 'unittest';
        $this->assertEquals('unittest', $this->_object->getRemoteUser());
    }


    /**
     * @covers Mumsys_Php_Globals::getRemoteUser
     * @runInSeparateProcess
     */
    public function testGetRemoteUserUSER()
    {
        $_SERVER['USER'] = 'unittest';
        $this->assertEquals('unittest', $this->_object->getRemoteUser());
    }


    /**
     * @covers Mumsys_Php_Globals::getRemoteUser
     * @runInSeparateProcess
     */
    public function testGetRemoteUserLOGNAME()
    {
        $_SERVER['USER'] = null;
        $_SERVER['LOGNAME'] = 'unittest';
        $this->assertEquals('unittest', $this->_object->getRemoteUser());
    }

    /**
     * Version check
     */
    public function testCheckVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Php_Globals::VERSION);
    }
}