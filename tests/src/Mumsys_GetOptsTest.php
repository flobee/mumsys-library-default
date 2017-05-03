<?php

/**
 * Test class for Mumsys_GetOpts.
 */
class Mumsys_GetOptsTest extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_GetOpts
     */
    protected $_object;
    /**
     * Initial options/configuration
     * @var array
     */
    private $opts;

    /**
     * Input parameters to work with.
     * @var array
     */
    private $_input;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
//        $this->markTestSkipped();
//        return false;
        $this->_version = '3.4.0';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_GetOpts' => $this->_version,
        );

        $this->opts = array(
            '-v|--verbose', // v or verbose flag
            '-i|--input:',  // i or input parameter with reguired value. e.g.: --input /tmp/file.txt
            '-b|--bits:',   // b or bits parameter with required value
            '-f:',          // f with input
            '--help|-h' => 'help option and this value is the help info place',
        );
        $input = $this->_input = array(
            'programToCall', // program to call
            '--verbose',
            '-i',
            'tmp/file.txt',
            '--bits',
            'bits',
            '-f:',
            'f',
            '--help',
        );

//        /** @todo to test the features from feedcache program */
////        $options = array(
////            '--action:' => 'Action to call: finalize|cron|import',
////            'cron' => 'Task: Process listed jobs',
////            'finalize' => 'Task: Bring cache to struct an drop cache after a successful execution',
////            'import' => 'Task: Create new jobs (see this code and csv demo file for a howto)',
////            '--file:' => 'csv file location to/for import',
////        );
        $this->_object = new Mumsys_GetOpts($this->opts, $input);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


    /**
     * for 100% code coverage
     * @covers Mumsys_GetOpts::__construct
     */
    public function testConstruct1()
    {
        // use server vars, not input parameters
        $x = new Mumsys_GetOpts($this->opts);

        $this->assertInstanceOf('Mumsys_GetOpts', $x);
    }

//
//    // for 100% code coverage
//    // first come first serves not in this kind of setters: --no- options
//    public function testConstructWithNoFlag()
//    {
//        $inp[] = 'programToCall';
//        $inp[] = '--help';
//        $inp[] = '--no-help';
//        $x = new Mumsys_GetOpts($this->opts, $inp);
//
//        $this->assertEquals('--no-help', $x->getCmd());
//    }
//
//
//    // for 100% code coverage
//    public function testConstructExceptionWithNoFlag()
//    {
//        $inp = $this->_input;
//        $inp[] = '--no-unknown';
//
//        $this->setExpectedException('Mumsys_GetOpts_Exception',
//            'Option "--no-unknown" not found in option list/configuration');
//        $x = new Mumsys_GetOpts($this->opts, $inp);
//    }
//
//    public function testConstructException()
//    {
//        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Empty options detected. Can not parse shell arguments');
//        $x = new Mumsys_GetOpts(array(), array());
//    }
//
//
//    public function testConstructException2()
//    {
//        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Missing value for parameter "--host"' . PHP_EOL);
//        $options = array(
//            '-h|--host:',
//        );
//
//        new Mumsys_GetOpts($options, array('cmd', '--host'));
//    }
//
//
//    public function testGetResult()
//    {
//        $actual1 = $this->_object->getResult();
//        $actual2 = $this->_object->getResult();
//        $expected = array(
//            0 => 'programToCall',
//            'verbose' => true,
//            'input' => 'tmp/file.txt',
//            'bits' => 'bits',
//            'f' => 'f',
//            'help' => true
//        );
//
//        $this->assertEquals($actual1, $actual2);
//        $this->assertEquals($expected, $actual1);
//    }



    // for 100% code coverage
    public function testConstructWithNoFlag()
    {
        $inp[] = 'programToCall';
        $inp[] = '--help';
        $inp[] = '--no-help';
        $x = new Mumsys_GetOpts($this->opts, $inp);
    }


    // for 100% code coverage
    public function testConstructExceptionWithNoFlag()
    {
        $inp = $this->_input;
        $inp[] = '--no-unknown';

        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception',
            '/(Option "--no-unknown" not found in option list\/configuration)/');
        $x = new Mumsys_GetOpts($this->opts, $inp);
    }

    public function testConstructException()
    {
        $this->setExpectedExceptionRegExp(
            'Mumsys_GetOpts_Exception',
            '/(Empty options detected. Can not parse shell arguments)/'
        );
        $x = new Mumsys_GetOpts(array(), $input = array());
    }


    public function testConstructException2()
    {
        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', '/(Missing value for parameter "-h"' . PHP_EOL .')/m');
        $options = array(
            '-h:',
            '--action:' => 'Action to call: finalize, cron, import',
            'cron' => 'Process listed jobs',
            'import' => 'Create new jobs',
            '--file:' => 'csv file location to/for import',
            'finalize' => 'Bring cache to storage an drop cache after a successful execution',
        );

        $o = new Mumsys_GetOpts($options, array('cmd', '-h'));
    }


    public function testGetResult()
    {
        $input = array('script.php', 'action1', '--verbose', '--input', "file1.txt", '--flag', 'action2', '--xbc', 'file2.txt');
//        $simpleConfig = array(
//            '--verbose' => true,
//            '--input:' => 'desc for input in action1',
//            '--flag' => true,
//        );
//        $this->_object = new Mumsys_GetOpts($simpleConfig, $input);
//
//        $simpleConfig = array(
//            '--verbose',
//            '--input:',
//            '--flag',
//        );
//        $this->_object = new Mumsys_GetOpts($simpleConfig, $input);

        // a fixed cofiguration with two actions
        $advConfig = array(
            'action1' => array(
                '--verbose' => true,
                '--input:' => 'desc for input in action1',
                '--flag' => true,
            ),
            'action2' => array(
                '--verbose' => true,
                '--xbc' => 'desc for input in action2',
                '--set' => true,
            ),
        );

        $this->_object = new Mumsys_GetOpts($advConfig, $input);
print_r($this->_object);
        // a flexible config, same options for the program but several actions
        $flexConfig = array(
            '--verbose' => true,
            '--input:' => 'desc for input in action1',
            '--flag' => true,
            '--verbose' => true,
            '--xbc' => 'desc for input in action2',
            '--set' => true,
            'actions' => array('run','setconfig', 'showconfig'),
        );

        $this->assertEquals($expected, $actual);
    }


    public function testGetCmd()
    {
        $actual = $this->_object->getCmd();
        $expected = '--verbose --input i_input --bits b_input -f f_param --help';
        $this->assertEquals($expected, $actual);

        $input = array('program', '--verbose', '--input', "true", '--bits', 'false', '-f', 'f_param', '--no-f');
        $this->_object = new Mumsys_GetOpts($this->opts, $input);
        $actual = $this->_object->getCmd();
        $expected = '--verbose --input true --bits false --no-f';
        $this->assertEquals($expected, $actual);
    }


    public function testGetCmd2()
    {
        $o = new Mumsys_GetOpts(array('-y:'), array('cmd', '-y', 'yes'));
        $actual = $o->getCmd();
        $expected = '-y yes';
        $this->assertEquals($expected, $actual);

        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', '/(Missing value for parameter "-x")/');
        $o = new Mumsys_GetOpts(array('-x:'), array('cmd', '-x'));
        $actual = $o->getCmd();
    }

//    public function testGetMapping()
//    {
//        $actual = $this->_object->getMapping();
//        $expected = array(
//            '-v' => '--verbose',
//            '--verbose' => '--verbose',
//            '-i' => '--input',
//            '--input' => '--input',
//            '-b' => '--bits',
//            '--bits' => '--bits',
//            '-f' => '-f',
//            '-h' => '--help',
//            '--help' => '--help',
//        );
//
//        $this->assertEquals($expected, $actual);
//    }
//
//
//    public function testGetCmd()
//    {
//        $actual1 = $this->_object->getCmd();
//        $expected1 = '--verbose --input tmp/file.txt --bits bits -f f --help';
//
//
//        $input = array('program', '--verbose', '--input', "true", '--bits', 'false', '-f', 'f', '--no-f');
//        $this->_object = new Mumsys_GetOpts($this->opts, $input);
//        $actual2 = $this->_object->getCmd();
//        $expected2 = '--verbose --input true --bits false --no-f';
//
//        $this->assertEquals($expected1, $actual1);
//        $this->assertEquals($expected2, $actual2);
//    }
//
//
//    public function testGetCmd2()
//    {
//        $o = new Mumsys_GetOpts(array('-y:'), array('cmd', '-y', 'yes'));
//        $actual = $o->getCmd();
//        $expected = '-y yes';
//        $this->assertEquals($expected, $actual);
//
//        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Missing value for parameter "-x"');
//        $o = new Mumsys_GetOpts(array('-x:'), array('cmd', '-x'));
//        $actual = $o->getCmd();
//    }
//
//
//    public function testGetHelp()
//    {
//        $actual = $this->_object->getHelp();
//        $expected = '-v|--verbose' . PHP_EOL
//            . '-i|--input <yourValue/s>' . PHP_EOL
//            . '-b|--bits <yourValue/s>' . PHP_EOL
//            . '-f <yourValue/s>' . PHP_EOL
//            . '--help|-h' . PHP_EOL
//            . "\thelp option and this value is the help info place";
//
//        $this->assertEquals($expected, $actual);
//    }


    // --- test abstract and versions

    public function testgetVersions()
    {
        $possible = $this->_object->getVersions();

        foreach ($this->_versions as $must => $value) {
            $this->assertTrue( isset($possible[$must]) );
            $this->assertEquals($possible[$must], $value);
        }
    }


}
