<?php

/**
 * Test class for Mumsys_GetOpts.
 */
class Mumsys_GetOptsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Mumsys_GetOpts
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->opts = array(
            '-v|--verbose', // v or verbose flag
            '-i|--input:', // i or input parameter with reguired value. e.g.: --input /tmp/file.txt
            '-b|--bits:', // l or list parameter with required value
            '-f:', // f with input
            '--help|-h' => 'help option and this is the help info place',
        );
        $input = $this->_input= array(
            'programToCall', // program to call
            '-v',
            '--verbose',
            '-i',
            'i_input',
            '--input',
            '/tmp/file.txt',
            '-b',
            'b_input',
            '--bits',
            'bits_input',
            '-f:',
            'f_param',
            '--help',
            //'--unknown:'
        );

        /** @todo to test the features from feedcache program */
        $options = array(
            '--action:' => 'Action to call: finalize|cron|import',
            'cron' => 'Task: Process listed jobs',
            'finalize' => 'Task: Bring cache to struct an drop cache after a successful execution',
            'import' => 'Task: Create new jobs (see this code and csv demo file for a howto)',
            '--file:' => 'csv file location to/for import',
        );

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


    // for 100% code coverage
    public function testConstruct1()
    {
        // use server vars, not input parameters
        $x = new Mumsys_GetOpts($this->opts);
    }

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

        $this->setExpectedException('Mumsys_GetOpts_Exception',
            'Option "--no-unknown" not found in option list/configuration');
        $x = new Mumsys_GetOpts($this->opts, $inp);
    }

    public function testConstructException()
    {
        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Empty options detected. Can not parse shell arguments');
        $x = new Mumsys_GetOpts(array(), $input = array());
    }


    public function testConstructException2()
    {
        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Missing value for parameter "-h"' . PHP_EOL);
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
        $actual1 = $this->_object->getResult();
        $actual2 = $this->_object->getResult();
        $expected = array(
            0 => 'programToCall',
            'verbose' => true,
            'input' => 'i_input',
            'bits' => 'b_input',
            'f' => 'f_param',
            'help' => true
        );

        $this->assertEquals($expected, $actual1);
        $this->assertEquals($expected, $actual2);
    }

    public function testGetMapping()
    {
        //$this->markTestIncomplete();
        $actual = $this->_object->getMapping();
        $expected = array(
            '-v' => '--verbose',
            '--verbose' => '--verbose',
            '-i' => '--input',
            '--input' => '--input',
            '-b' => '--bits',
            '--bits' => '--bits',
            '-f' => '-f',
            '-h' => '--help',
            '--help' => '--help',
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

        $this->setExpectedException('Mumsys_GetOpts_Exception', 'Missing value for parameter "-x"');
        $o = new Mumsys_GetOpts(array('-x:'), array('cmd', '-x'));
        $actual = $o->getCmd();
    }


    public function testGetHelp()
    {
        $actual = $this->_object->getHelp(76, "    ");
        $expected = '-v|--verbose' . PHP_EOL
            . '-i|--input <yourValue/s>' . PHP_EOL
            . '-b|--bits <yourValue/s>' . PHP_EOL
            . '-f <yourValue/s>' . PHP_EOL
            . '--help|-h' . PHP_EOL
            . "    help option and this is the help info place";

        $this->assertEquals($expected, $actual);
    }

}
