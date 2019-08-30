<?php

/**
 * Test class for Mumsys_GetOpts.
 */
class Mumsys_GetOptsTest
    extends Mumsys_Unittest_Testcase
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
    protected function setUp(): void
    {
        $this->_version = '3.6.1';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_GetOpts' => $this->_version,
        );

        $this->_opts = array(
            '-v|--verbose', // v or verbose flag
            '-i|--input:',  // i or input parameter with reguired value. e.g.:
                            // --input /tmp/file.txt
            '-b|--bits:',   // b or bits parameter with required value
            '-f:', // f with input
            '--help|-h' => 'help option and this value is the help info place',
        );

        $this->_input = array(
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

        $this->_object = new Mumsys_GetOpts( $this->_opts, $this->_input );
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
     * @covers Mumsys_GetOpts::__construct
     */
    public function testConstruct1()
    {
        $_SERVER['argv'] = array();
        $_SERVER['argc'] = 0;
        // use server vars, not input parameters
        $x = new Mumsys_GetOpts( $this->_opts );

        $this->assertInstanceOf( 'Mumsys_GetOpts', $x );

        $regex = '/(Empty options detected. Can not parse shell arguments)/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_GetOpts_Exception' );
        new Mumsys_GetOpts();
    }


    /**
     * @covers Mumsys_GetOpts::__construct
     * @covers Mumsys_GetOpts::parse
     */
    public function testConstructException2()
    {
        $regex = '/(Missing value for parameter "-h" in action "_default_")/m';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_GetOpts_Exception' );

        $options = array(
            '-h:', // a value required
            '--action:' => 'Action to call: finalize, cron, import',
            'cron' => 'Process listed jobs',
            'import' => 'Create new jobs',
            '--file:' => 'csv file location to/for import',
            'finalize' => 'Bring cache to storage an drop cache after a successful execution',
        );
        new Mumsys_GetOpts( $options, array('cmd', '-h') );
    }


    /**
     * @covers Mumsys_GetOpts::__construct
     * @covers Mumsys_GetOpts::parse
     */
    public function testConstructExceptionWithNoFlag()
    {
        $inp = $this->_input;
        $inp[] = '--no-unknown';

        $this->expectException( 'Mumsys_GetOpts_Exception' );
        $regex = '/(Option "--no-unknown" not found in option list\/configuration)/';
        $this->expectExceptionMessageRegExp( $regex );
        new Mumsys_GetOpts( $this->_opts, $inp );
    }


    /**
     * @covers Mumsys_GetOpts::__construct
     * @covers Mumsys_GetOpts::verifyOptions
     * @covers Mumsys_GetOpts::setMappingOptions
     * @covers Mumsys_GetOpts::getMapping
     */
    public function testConstructVerifyOptionsAndMapping()
    {
        $input[] = 'programToCall';
        $input[] = '--help';
        $options = $this->_opts;
        $newOpts = array('_default_' => $options);

        $object = new Mumsys_GetOpts( $options, $input );

        $incomming1 = $object->verifyOptions( $options );
        $object->setMappingOptions( $incomming1 );
        $mapping1 = $object->getMapping();

        $incomming2 = $object->verifyOptions( $newOpts );
        $object->setMappingOptions( $incomming2 );
        $mapping2 = $object->getMapping();

        $this->assertEquals( $newOpts, $incomming1 );
        $this->assertEquals( $newOpts, $incomming2 );
        $this->assertEquals( $mapping1, $mapping2 );
    }


    /**
     * @covers Mumsys_GetOpts::__construct
     * @covers Mumsys_GetOpts::verifyOptions
     */
    public function testConstructVerifyOptionsException1()
    {
        $regex = '/(Invalid input config found)/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_GetOpts_Exception' );

        $opts = array(0, 1);
        $this->_object->verifyOptions( $opts );
    }


    /**
     * @covers Mumsys_GetOpts::__construct
     * @covers Mumsys_GetOpts::verifyOptions
     */
    public function testConstructVerifyOptionsException2()
    {
        $regex = '/(Invalid input config found)/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_GetOpts_Exception' );

        $optsError = array('somevalue', '-v|--verbose');
        $this->_object->verifyOptions( $optsError );
    }


    /**
     * @covers Mumsys_GetOpts::parse
     * @covers Mumsys_GetOpts::getResult
     */
    public function testParseGetResultSimple()
    {
        $config = array(
            '--verbose|-v',
            '--input:' => 'desc for input',
            '--flag',
            '-h'
        );
        $input = array('script.php', '--verbose', '--input', "file1.txt", '--flag', '-h');
        $this->_object = new Mumsys_GetOpts( $config, $input );

        $actual1 = $this->_object->getResult();
        $expected1 = array(
            'verbose' => true,
            'input' => 'file1.txt',
            'flag' => true,
            'h' => true,
        );

        // test using --no-<option> removal which result in boolean false
        $input = array(
            'script.php', '--verbose', '--input', "file1.txt", '--flag',
            '--no-flag', '--no-input', '--no-v', '-h', '--no-h'
        );
        $this->_object = new Mumsys_GetOpts( $config, $input );
        $actual2 = $this->_object->getResult();
        $expected2 = $expected1;
        $expected2['flag'] = false;
        $expected2['input'] = false;
        $expected2['verbose'] = false;
        $expected2['h'] = false;

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
    }


    /**
     * Too many options/ not registered in config.
     * @covers Mumsys_GetOpts::parse
     */
    public function testParseException1()
    {
        $config = array(
            '--input:' => 'desc for input',
            '--verbose',
        );
        $input = array('script.php', '--verbose', '--input', 'file.txt', '--others');

        $regex = '/(Option "--others" not found in option list\/configuration for action "_default_")/i';
        $this->expectException( 'Mumsys_GetOpts_Exception' );
        $this->expectExceptionMessageRegExp( $regex );
        new Mumsys_GetOpts( $config, $input );
    }


    /**
     * Missing reqired input
     * @covers Mumsys_GetOpts::parse
     */
    public function testParseException2()
    {
        $config = array(
            '--input:' => 'desc for input',
            '--verbose',
        );
        $input = array('script.php', '--verbose', '--input');

        $regex = '/(Missing value for parameter "--input" in action "_default_")/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_GetOpts_Exception' );
        new Mumsys_GetOpts( $config, $input );
    }


    /**
     * @covers Mumsys_GetOpts::parse
     * @covers Mumsys_GetOpts::getResult
     */
    public function testParseGetResultAdvanced()
    {
        // all in: all opts fits to an action
        $opts = array(
            '--verbose|-v' => true,
            '--input:' => 'desc for input in action1',
            '--flag' => true,
        );
        $allInConfig = array(
            'action1' => $opts,
            'action2' => $opts,
        );
        // eg: script.php action1 --verbose --input file1.txt --flag action2 --input file2.txt
        $input = array(
            'script.php',
            'action1', '--verbose', '--input', "file1.txt", '--flag',
            'action2', '--input', 'file2.txt', '-v', '--no-v',
        );

        $this->_object = new Mumsys_GetOpts( $allInConfig, $input );
        $actual1 = $this->_object->getResult();
        $expected1 = array(
            'action1' => array('verbose' => true, 'input' => 'file1.txt', 'flag' => true),
            'action2' => array('input' => 'file2.txt', 'verbose' => false),
        );

        $this->assertEquals( $expected1, $actual1 );

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


    /**
     * Minimal test, for complete CC see parse() tests.
     * @covers Mumsys_GetOpts::getResult
     */
    public function testGetResult()
    {
        $actual1 = $this->_object->getResult();
        $actual2 = $this->_object->getResult();
        $expected = array(
            'verbose' => true,
            'input' => 'tmp/file.txt',
            'bits' => 'bits',
            'f' => 'f',
            'help' => true,
        );

        $this->assertEquals( $expected, $actual1 );
        $this->assertEquals( $expected, $actual2 );
    }


    /**
     * @covers Mumsys_GetOpts::getCmd
     */
    public function testGetCmd()
    {
        $actual = $this->_object->getCmd();
        $expected = '--verbose --input tmp/file.txt --bits bits -f f --help';
        $this->assertEquals( $expected, $actual );

        $input = array('program', '--verbose', '--input', "true", '--bits', 'false', '-f', 'f_param', '--no-f');
        $this->_object = new Mumsys_GetOpts( $this->_opts, $input );

        $actual = $this->_object->getCmd();
        $expected = '--verbose --input true --bits false --no-f';
        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_GetOpts::getCmd
     */
    public function testGetCmd2()
    {
        $o = new Mumsys_GetOpts( array('-y:'), array('cmd', '-y', 'yes') );
        $actual = $o->getCmd();
        $expected = '-y yes';
        $this->assertEquals( $expected, $actual );

        $this->expectException( 'Mumsys_GetOpts_Exception' );
        $this->expectExceptionMessageRegExp( '/(Missing value for parameter "-x")/' );
        $o = new Mumsys_GetOpts( array('-x:'), array('cmd', '-x') );
        $actual = $o->getCmd();
    }


    /**
     * @covers Mumsys_GetOpts::getCmd
     */
    public function testGetCmdAdv()
    {
        // all in: all opts fits to an action
        $opts = array(
            '--verbose|-v' => true,
            '--input:' => 'desc for input in action',
            '--flag' => true,
        );
        $allInConfig = array(
            'action1' => $opts,
            'action2' => $opts,
        );
        // eg: script.php action1 --verbose --input file1.txt --flag action2 --input file2.txt
        $input = array(
            'script.php',
            'action1', '--verbose', '--input', "file1.txt", '--flag',
            'action2', '--input', 'file2.txt', '-v', '--no-v',
        );

        $object = new Mumsys_GetOpts( $allInConfig, $input );

        $actual = $object->getCmd();
        $expected = 'action1 --verbose --input file1.txt --flag action2 '
            . '--input file2.txt --no-verbose';
        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_GetOpts::getMapping
     */
    public function testGetMapping()
    {
        $actual = $this->_object->getMapping();
        $expected = array(
            '_default_' => array(
                '-v' => '--verbose',
                '--verbose' => '--verbose',
                '-i' => '--input',
                '--input' => '--input',
                '-b' => '--bits',
                '--bits' => '--bits',
                '-f' => '-f',
                '-h' => '--help',
                '--help' => '--help',
            )
        );

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_GetOpts::getHelp
     * @covers Mumsys_GetOpts::__toString
     */
    public function testGetHelp()
    {
        $actual = $this->_object->getHelp();
        $expected = 'Actions/ options/ information:' . PHP_EOL
            . '-v|--verbose' . PHP_EOL
            . '-i|--input <yourValue/s>' . PHP_EOL
            . '-b|--bits <yourValue/s>' . PHP_EOL
            . '-f <yourValue/s>' . PHP_EOL
            . '--help|-h' . PHP_EOL
            . "    help option and this value is the help info place"
            . PHP_EOL
            . PHP_EOL
        ;

        $this->assertEquals( $expected, $actual );
        $this->assertEquals( $expected, $this->_object );
    }


    /**
     * @covers Mumsys_GetOpts::getHelp
     * @covers Mumsys_GetOpts::__toString
     */
    public function testGetHelpAdv()
    {
        // all in: all opts fits to an action
        $opts = array(
            '--verbose|-v' => true,
            '--input:' => 'desc for input in action',
            '--flag' => true,
        );
        $allInConfig = array(
            'action1' => $opts,
            'action2' => $opts,
        );
        // eg: script.php action1 --verbose --input file1.txt --flag action2 --input file2.txt
        $input = array(
            'script.php',
            'action1', '--verbose', '--input', "file1.txt", '--flag',
            'action2', '--input', 'file2.txt', '-v', '--no-v',
        );

        $object = new Mumsys_GetOpts( $allInConfig, $input );

        $actual = $object->getHelp();
        $expected = 'action1' . PHP_EOL
            . "    --verbose|-v" . PHP_EOL
            . "    --input <yourValue/s>" . PHP_EOL
            . "        desc for input in action" . PHP_EOL . PHP_EOL
            . "    --flag" . PHP_EOL
            . PHP_EOL
            . "action2" . PHP_EOL
            . "    --verbose|-v" . PHP_EOL
            . "    --input <yourValue/s>" . PHP_EOL
            . "        desc for input in action" . PHP_EOL . PHP_EOL
            . "    --flag" . PHP_EOL
            . PHP_EOL
        ;

        $this->assertEquals( $expected, $actual );
        $this->assertEquals( $expected, $object );
    }


    /**
     * @covers Mumsys_GetOpts::getHelpLong
     * @covers Mumsys_GetOpts::getHelp
     */
    public function testGetHelpLong()
    {

        $actual = $this->_object->getHelpLong();

        $expected = <<<TEXT
Class to handle/ pipe shell arguments in php context.

Shell arguments will be parsed and an array list of key/value pairs will be
created.
When using long and shot options the long options will be used and the short
one will map to it.
Short options always have a single character. Dublicate options can't be
handled. First comes first serves will take affect (fifo).
Flags will be handled as boolean true if set.
The un-flag option take affect when: Input args begin with a "--no-" string.
E.g. --no-history. It will check if the option --history was set and will
unset it like it wasn't set in the cmd line. This is usefule when working
with different options. One from a config file and the cmd line adds or
replace some options. But this must be handled in your buissness logic. E.g. see
Mumsys_Multirename class.
The un-flag option will always disable/ remove a value.

Your options:

Actions/ options/ information:

TEXT;
        $expected .= ''
            . '-v|--verbose' . PHP_EOL
            . '-i|--input <yourValue/s>' . PHP_EOL
            . '-b|--bits <yourValue/s>' . PHP_EOL
            . '-f <yourValue/s>' . PHP_EOL
            . '--help|-h' . PHP_EOL
            . "    help option and this value is the help info place"
            . PHP_EOL
            . PHP_EOL
        ;
        ;

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_GetOpts::getRawData
     */
    public function testGetRawData()
    {
        $actual = $this->_object->getRawData();
        $expected = array(
            '_default_' => array(
                '--verbose' => true,
                '--input' => 'tmp/file.txt',
                '--bits' => 'bits',
                '-f' => 'f',
                '--help' => true
            )
        );

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_GetOpts::getRawInput
     */
    public function testGetRawInput()
    {
        $actual = $this->_object->getRawInput();
        $this->assertEquals( $this->_input, $actual );
    }


    /**
     * @covers Mumsys_GetOpts::resetResults
     */
    public function testResetResult()
    {
        $this->_object->resetResults();

        $this->assertEquals( array(), $this->_object->getMapping() );
        $this->assertEquals( array(), $this->_object->getResult() );
    }


    /**
     * test abstract and versions
     * @covers Mumsys_GetOpts::__construct
     */
    public function testgetVersions()
    {
        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $this->assertTrue( isset( $possible[$must] ) );
            $this->assertEquals( $possible[$must], $value );
        }
    }

}
