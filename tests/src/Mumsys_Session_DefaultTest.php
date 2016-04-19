<?php


/**
 * Mumsys_Session_Default Test
 */
class Mumsys_Session_DefaultTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Session
     */
    protected $_object;


    /**
     * needed to test the session.
     */
    public function __construct()
    {
        ob_start();
    }


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_Session();
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
     * Test nearly all methodes because of the problematic of php sessions
     * itselves to test them.
     */
    public function testAllMethodes()
    {
        $this->_object = new Mumsys_Session();

        // for code coverage
        $this->_object = new Mumsys_Session();

        // for code coverage
        $this->_object->clear();
        $actual6 = $this->_object->getAll();
        $expected6 = array();

        // test setter
        $this->_object->replace('testkey', array('val1', 'val2'));

        $actual1 = $this->_object->get('testkey');
        $expected1 = array('val1', 'val2');

        $actual2 = $this->_object->getAll();
        $this->_object->__destruct();
        $expected2 = $_SESSION;

        $actual3 = $this->_object->getID();
        $expected3 = key($_SESSION);

        $actual5 = $this->_object->getCurrent();
        $expected5 = $expected2[$expected3]['mumsys'];

        $this->_object->register('newkey', array('val5', 'val6'));
        $actual4 = $this->_object->get('newkey');
        $expected4 = array('val5', 'val6');
        // test default return
        $actual7 = $this->_object->get('notsetbefor', 'dingding');
        $expected7 = 'dingding';

        // get
        $this->assertEquals($expected1, $actual1);
        // __destruct
        $this->assertEquals($expected2, $actual2);
        // getID
        $this->assertEquals($expected3, $actual3);
        // register
        $this->assertEquals($expected4, $actual4);
        // getCurrent
        $this->assertEquals($expected5, $actual5);
        // clear
        $this->assertEquals($expected6, $actual6);
        // test default return
        $this->assertEquals($expected7, $actual7);

        // version checks
        $this->assertEquals('Mumsys_Session 1.0.1', $this->_object->getVersion());

        // test register existing
        $this->setExpectedException('Mumsys_Session_Exception', 'Session key "testkey" exists');
        $this->_object->register('testkey', array('val5', 'val6'));

        echo 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    }

}