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
    private $_opts;

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
        $this->_version = '3.5.0';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_GetOpts' => $this->_version,
        );

        $this->_opts = array(
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


        $this->_object = new Mumsys_GetOpts($this->_opts, $input);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


//    /**
//     * @covers Mumsys_GetOpts::__construct
//     */
//    public function testConstruct1()
//    {
//        $_SERVER['argv'] = array();
//        $_SERVER['argc'] = 0;
//        // use server vars, not input parameters
//        $x = new Mumsys_GetOpts($this->_opts);
//
//        $this->assertInstanceOf('Mumsys_GetOpts', $x);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::__construct
//     */
//    public function testConstruct1Exception()
//    {
//        $regex = '/(Empty options detected. Can not parse shell arguments)/i';
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', $regex);
//        new Mumsys_GetOpts();
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::__construct
//     */
//    public function testConstructException2()
//    {
//        $regex = '/(Missing value for parameter "-h" in action "_default_")/m';
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', $regex);
//
//        $options = array(
//            '-h:', // a value required
//            '--action:' => 'Action to call: finalize, cron, import',
//            'cron' => 'Process listed jobs',
//            'import' => 'Create new jobs',
//            '--file:' => 'csv file location to/for import',
//            'finalize' => 'Bring cache to storage an drop cache after a successful execution',
//        );
//        new Mumsys_GetOpts($options, array('cmd', '-h'));
//    }
//
//    /**
//     * @covers Mumsys_GetOpts::__construct
//     */
//    public function testConstructExceptionWithNoFlag()
//    {
//        $inp = $this->_input;
//        $inp[] = '--no-unknown';
//
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception',
//            '/(Option "--no-unknown" not found in option list\/configuration)/');
//        $x = new Mumsys_GetOpts($this->_opts, $inp);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::__construct
//     * @covers Mumsys_GetOpts::verifyOptions
//     * @covers Mumsys_GetOpts::setMappingOptions
//     * @covers Mumsys_GetOpts::getMapping
//     */
//    public function testConstructVerifyOptionsAndMapping()
//    {
//        $input[] = 'programToCall';
//        $input[] = '--help';
//        $options = $this->_opts;
//        $newOpts = array('_default_' => $options);
//
//        $object = new Mumsys_GetOpts($options, $input);
//
//        $incomming1 = $object->verifyOptions($options);
//        $object->setMappingOptions($incomming1);
//        $mapping1 = $object->getMapping();
//
//        $incomming2 = $object->verifyOptions($newOpts);
//        $object->setMappingOptions($incomming2);
//        $mapping2 = $object->getMapping();
//
//        $this->assertEquals($newOpts, $incomming1);
//        $this->assertEquals($newOpts, $incomming2);
//        $this->assertEquals($mapping1, $mapping2);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::__construct
//     * @covers Mumsys_GetOpts::verifyOptions
//     */
//    public function testConstructVerifyOptionsException1()
//    {
//        $regex = '/(Invalid input config found)/i';
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', $regex);
//
//        $optsError = array('somevalue', '-v|--verbose');
//        $this->_object->verifyOptions($optsError);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::__construct
//     * @covers Mumsys_GetOpts::verifyOptions
//     */
//    public function testConstructVerifyOptionsException2()
//    {
//        $regex = '/(Invalid input config found)/i';
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', $regex);
//
//        $opts = array(0, 1);
//        $this->_object->verifyOptions($opts);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::parse
//     * @covers Mumsys_GetOpts::getResult
//     */
//    public function testParseGetResultSimple()
//    {
//        $config = array(
//            '--verbose|-v',
//            '--input:' => 'desc for input',
//            '--flag',
//            '-h'
//        );
//        $input = array('script.php', '--verbose', '--input', "file1.txt", '--flag', '-h');
//        $this->_object = new Mumsys_GetOpts($config, $input);
//
//        $actual1 = $this->_object->getResult();
//        $expected1 = array(
//            'verbose' => true,
//            'input' => 'file1.txt',
//            'flag' => true,
//            'h' => true,
//        );
//
//        // test using --no-<option> removal which result in boolean false
//        $input = array(
//            'script.php', '--verbose', '--input', "file1.txt", '--flag',
//            '--no-flag', '--no-input', '--no-v', '-h','--no-h'
//        );
//        $this->_object = new Mumsys_GetOpts($config, $input);
//        $actual2 = $this->_object->getResult();
//        $expected2 = $expected1;
//        $expected2['flag'] = false;
//        $expected2['input'] = false;
//        $expected2['verbose'] = false;
//        $expected2['h'] = false;
//
//        $this->assertEquals($expected1, $actual1);
//        $this->assertEquals($expected2, $actual2);
//    }
//
//    /**
//     * Too many options/ not registered in config.
//     * @covers Mumsys_GetOpts::parse
//     */
//    public function testParseException1()
//    {
//        $config = array(
//            '--input:' => 'desc for input',
//            '--verbose',
//        );
//        $input = array('script.php', '--verbose', '--input', 'file.txt', '--others');
//
//        $regex = '/(Option "--others" not found in option list\/configuration for action "_default_")/i';
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', $regex);
//        new Mumsys_GetOpts($config, $input);
//    }
//
//
//    /**
//     * Missing reqired input
//     * @covers Mumsys_GetOpts::parse
//     */
//    public function testParseException2()
//    {
//        $config = array(
//            '--input:' => 'desc for input',
//            '--verbose',
//        );
//        $input = array('script.php', '--verbose', '--input');
//
//        $regex = '/(Missing value for parameter "--input" in action "_default_")/i';
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', $regex);
//        new Mumsys_GetOpts($config, $input);
//    }
//
//

    /**
     * @covers Mumsys_GetOpts::parse
     * @covers Mumsys_GetOpts::getResult
     */
    public function testParseGetResultAdvanced()
    {
        // all in: all opts fits to an action
        $opts = array(
            '--verbose' => true,
            '--input:' => 'desc for input in action1',
            '--flag' => true,
        );
        $allInConfig = array(
            'action1' => $opts,
            'action2' => $opts,
        );
        $input = array(
            'script.php',
            'action1', '--verbose', '--input', "file1.txt", '--flag',
            'action2', '--input', 'file2.txt'
        );

        $this->_object = new Mumsys_GetOpts($allInConfig, $input);
        $actual1 = $this->_object->getResult();
        $expected1 = array(
            'action1' => array('verbose' => true, 'input' => 'file1.txt', 'flag' => true),
            'action2' => array('input' => 'file2.txt', ),
        );
print_r($this->_object->getRawData());
        $this->assertEquals($expected1, $actual1);


        // current implementation does NOT support this:

        // a fixed configuration with two actions
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
        $input = array(
            'script.php',
            'action1', '--verbose', '--input', "file1.txt", '--flag',
            'action2', '--xbc', 'file2.txt'
        );



//        $this->_object = new Mumsys_GetOpts($advConfig, $input);
//print_r($this->_object);
//        // a flexible config, same options for the program but several actions
//        $flexConfig = array(
//            '--verbose' => true,
//            '--input:' => 'desc for input in action1',
//            '--flag' => true,
//            '--verbose' => true,
//            '--xbc' => 'desc for input in action2',
//            '--set' => true,
//            'actions' => array('run','setconfig', 'showconfig'),
//        );
//
//        $this->assertEquals($expected, $actual);
    }


//
//
//    public function testGetCmd()
//    {
//        $actual = $this->_object->getCmd();
//        $expected = '--verbose --input i_input --bits b_input -f f_param --help';
//        $this->assertEquals($expected, $actual);
//
//        $input = array('program', '--verbose', '--input', "true", '--bits', 'false', '-f', 'f_param', '--no-f');
//        $this->_object = new Mumsys_GetOpts($this->_opts, $input);
//        $actual = $this->_object->getCmd();
//        $expected = '--verbose --input true --bits false --no-f';
//        $this->assertEquals($expected, $actual);
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
//        $this->setExpectedExceptionRegExp('Mumsys_GetOpts_Exception', '/(Missing value for parameter "-x")/');
//        $o = new Mumsys_GetOpts(array('-x:'), array('cmd', '-x'));
//        $actual = $o->getCmd();
//    }
//
////    public function testGetMapping()
////    {
////        $actual = $this->_object->getMapping();
////        $expected = array(
////            '-v' => '--verbose',
////            '--verbose' => '--verbose',
////            '-i' => '--input',
////            '--input' => '--input',
////            '-b' => '--bits',
////            '--bits' => '--bits',
////            '-f' => '-f',
////            '-h' => '--help',
////            '--help' => '--help',
////        );
////
////        $this->assertEquals($expected, $actual);
////    }
////
////
////    public function testGetCmd()
////    {
////        $actual1 = $this->_object->getCmd();
////        $expected1 = '--verbose --input tmp/file.txt --bits bits -f f --help';
////
////
////        $input = array('program', '--verbose', '--input', "true", '--bits', 'false', '-f', 'f', '--no-f');
////        $this->_object = new Mumsys_GetOpts($this->opts, $input);
////        $actual2 = $this->_object->getCmd();
////        $expected2 = '--verbose --input true --bits false --no-f';
////
////        $this->assertEquals($expected1, $actual1);
////        $this->assertEquals($expected2, $actual2);
////    }
////
////
////    public function testGetCmd2()
////    {
////        $o = new Mumsys_GetOpts(array('-y:'), array('cmd', '-y', 'yes'));
////        $actual = $o->getCmd();
////        $expected = '-y yes';
////        $this->assertEquals($expected, $actual);
////
////        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Missing value for parameter "-x"');
////        $o = new Mumsys_GetOpts(array('-x:'), array('cmd', '-x'));
////        $actual = $o->getCmd();
////    }





    // OK...

//
//    /**
//     * @covers Mumsys_GetOpts::getHelp
//     * @covers Mumsys_GetOpts::__toString
//     */
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
//        $this->assertEquals($expected, $this->_object);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::getRawData
//     */
//    public function testGetRawData()
//    {
//        $actual = $this->_object->getRawData();
//        $expected = array(
//            '_default_' => array(
//                '--verbose' => true,
//                '--input' => 'tmp/file.txt',
//                '--bits' => 'bits',
//                '-f' => 'f',
//                '--help' => true
//            )
//        );
//
//        $this->assertEquals($expected, $actual);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::getRawInput
//     */
//    public function testGetRawInput()
//    {
//        $actual = $this->_object->getRawInput();
//        $this->assertEquals($this->_input, $actual);
//    }
//
//
//    /**
//     * @covers Mumsys_GetOpts::resetResults
//     */
//    public function testResetResult()
//    {
//        $this->_object->resetResults();
//
//        $this->assertEquals(array(), $this->_object->getMapping());
//        $this->assertEquals(array(), $this->_object->getResult());
//    }
//
//
//    /**
//     * test abstract and versions
//     * @covers Mumsys_GetOpts::__construct
//     */
//    public function testgetVersions()
//    {
//        $possible = $this->_object->getVersions();
//
//        foreach ( $this->_versions as $must => $value ) {
//            $this->assertTrue(isset($possible[$must]));
//            $this->assertEquals($possible[$must], $value);
//        }
//    }

}
