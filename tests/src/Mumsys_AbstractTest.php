<?php


/** Test class A for the tests */
class Mumsys_AbstractTestClass
    extends Mumsys_Abstract
{
    public const VERSION = '0.0.1';

    public function checkKey( $s )
    {
        parent::_checkKey( $s );
    }
}


/**
 * Mumsys_Abstract Tests
 */
class Mumsys_AbstractTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_AbstractTestClass
     */
    private $_object;

    /**
     * @var string
     */
    private $_version;

    /**
     * @var array
     */
    protected $_versions;


//    public function __construct( $name = null, $data = array(), $dataName = '' )
//    {
//        parent::__construct( $name, $data, $dataName );
//    }


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->_version = '3.0.3';
        $this->_versions = array(
            'Mumsys_AbstractTestClass' => '0.0.1',
            'Mumsys_Abstract' => $this->_version,
        );
        $this->_object = new Mumsys_AbstractTestClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        unset( $this->_object );
    }


    /**
     * @covers Mumsys_Abstract::getVersionID
     */
    public function testGetVersionID()
    {
        $actual = $this->_object->getVersionID();
        $expected = '0.0.1';

        $this->assertingEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Abstract::getVersion
     */
    public function testGetVersion()
    {
        $actualA = $this->_object->getVersion();
        $expectedA = 'Mumsys_AbstractTestClass 0.0.1';

        $actualB = Mumsys_Abstract::getVersion();
        $expectedB = 'Mumsys_Abstract ' . $this->_version;

        $this->assertingEquals( $expectedA, $actualA );
        $this->assertingEquals( $expectedB, $actualB );
    }


    /**
     * @covers Mumsys_Abstract::_checkKey
     */
    public function test_checkKey()
    {
        $this->_object->checkKey( 'validkey' );

        $this->expectingException( 'Mumsys_Exception' );
        $regex = '/(Invalid initialisation key for a setter. '
            . 'A string is required)/';
        $this->expectingExceptionMessageRegex( $regex );

        $this->_object->checkKey( array('somekey') );
    }


    /**
     * @covers Mumsys_Abstract::getVersions
     */
    public function testVersions()
    {
        $this->assertingEquals( $this->_version, Mumsys_Abstract::VERSION );
        $this->checkVersionList( $this->_object->getVersions(), $this->_versions );
    }

}
