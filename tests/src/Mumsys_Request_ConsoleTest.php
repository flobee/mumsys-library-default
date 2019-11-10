<?php


/**
 * Mumsys_Request_Console Test
 */
class Mumsys_Request_ConsoleTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Request_Console
     */
    protected $_object;

    /**
     * @var string
     */
    protected $_version;

    /**
     * @var array
     */
    protected $_versions;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_version = '1.1.2';
        $this->_versions = array(
            'Mumsys_Request_Console' => '1.1.2',
            'Mumsys_Request_Abstract' => '1.0.1',
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
        );

        $this->_options['programKey'] = 'prg';
        $this->_options['controllerKey'] = 'cnt';
        $this->_options['actionKey'] = 'act';

        $this->_object = new Mumsys_Request_Console( $this->_options );
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
     * @covers Mumsys_Request_Console::__construct
     *
     * @runInSeparateProcess
     */
    public function test_Construct()
    {
        $_SERVER['argv']['unit'] = 'test';
        $_SERVER['argv'][] = 'unit=test';

        $this->_object = new Mumsys_Request_Console( $this->_options );
        $actual = $this->_object->getParams();

        $this->assertTrue( ( $actual['unit'] === 'test' ) );
        $this->assertTrue( in_array( 'unit=test', $actual ) );
        $this->assertTrue( ( count( $actual ) >= 2 ) );
    }


    public function testVersions()
    {
         $this->assertEquals( $this->_version, Mumsys_Request_Console::VERSION );
         $this->_checkVersionList( $this->_object->getVersions(), $this->_versions );
    }
}
