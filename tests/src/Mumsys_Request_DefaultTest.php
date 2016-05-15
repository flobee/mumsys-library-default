<?php


/**
 * Mumsys_Request_Default Test
 */
class Mumsys_Request_DefaultTest
    extends MumsysTestHelper
{
    /**
     * @var Mumsys_Request_Default
     */
    protected $_object;

    /**
     * @var array
     */
    protected $_input;

    /**
     * @var array
     */
    protected $_inputGet;

    /**
     * @var array
     */
    protected $_inputPost;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $options = array(
            'programKey' => 'programTest',
            'controllerKey' => 'controllerTest',
            'actionKey' => 'actionTest'
        );

        $_GET['programTest'] = 'prg';
        $_GET['controllerTest'] = 'ctrl';
        $_GET['actionTest'] = 'act';
        $this->_inputGet = $_GET;

        $_POST['programTest'] = 'prg';
        $_POST['controllerTest'] = 'ctrl';
        $_POST['actionTest'] = 'act';
        $this->_inputPost = $_POST;

        $_COOKIE = array('HIDDEN' => 'COOK');

        $this->_object = new Mumsys_Request_Default($options);
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
     * For code coverage
     */
    public function test_construct()
    {
        $options = array(
            'programKey' => 'programTest',
            'controllerKey' => 'controllerTest',
            'actionKey' => 'actionTest'
        );

        $_GET['programTest'] = 'prg';
        $_GET['controllerTest'] = 'ctrl';
        $_GET['actionTest'] = 'act';

        $_COOKIE = array('HIDDEN' => 'COOK');

        $this->_object = new Mumsys_Request_Default($options);
    }


    public function testGetInputGet()
    {
        $actual1 = $this->_object->getInputGet();
        $actual2 = $this->_object->getInputGet('programTest');
        $actual3 = $this->_object->getInputGet('notExists', 123);

        $this->assertEquals($this->_inputGet, $actual1);
        $this->assertEquals($this->_inputGet['programTest'], $actual2);
        $this->assertEquals('123', $actual3);
    }


    public function testGetInputPost()
    {
        $actual1 = $this->_object->getInputPost();
        $actual2 = $this->_object->getInputPost('programTest');
        $actual3 = $this->_object->getInputPost('notExists', 123);

        $this->assertEquals($this->_inputPost, $actual1);
        $this->assertEquals($this->_inputPost['programTest'], $actual2);
        $this->assertEquals('123', $actual3);
    }

}