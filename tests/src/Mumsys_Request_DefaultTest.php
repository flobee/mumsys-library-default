<?php


/**
 * Mumsys_Request_Default Test
 */
class Mumsys_Request_DefaultTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_Request_Default
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $options = array(
            'programKey' => 'program-key',
            'controllerKey' => 'controller-key',
            'actionKey' => 'action-key'
        );

        $this->_object = new Mumsys_Request_Default($options);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }


    /**
     * Just for code coverage
     * @covers Mumsys_Request_Default::__construct
     */
    public function test_construct()
    {
        $_GET['program-key'] = 'prg';
        $_GET['controller-key'] = 'ctrl';
        $_GET['action-key'] = 'actn';

        $_POST['program-key'] = 'prg';
        $_POST['controller-key'] = 'ctrl';
        $_POST['action-key'] = 'actn';

        $_COOKIE = array('abc' => 123);

        $this->assertEquals($_REQUEST, $this->_object->getParams());
    }


    /**
     * @covers Mumsys_Request_Default::getInputPost
     */
    public function testGetInputPost()
    {
        $this->assertEquals($_POST, $this->_object->getInputPost());
    }


    /**
     * @covers Mumsys_Request_Default::getInputGet
     */
    public function testGetInputGet()
    {
        $this->assertEquals($_GET, $this->_object->getInputGet());
    }

}