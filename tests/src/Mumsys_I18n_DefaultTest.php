<?php

/**
 * Mumsys_I18n_Default Test
 */
class Mumsys_I18n_DefaultTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mumsys_I18n_Default
     */
    protected $_object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Mumsys_I18n_Default('ru');
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
     * For code coverage.
     * @covers Mumsys_I18n_Default::__construct
     */
    public function test_construct()
    {
        $this->setUp();

        $this->setExpectedException('Mumsys_I18n_Exception', 'Invalid locale "biglocale"');
        $o = new Mumsys_I18n_Default('biglocale');
    }


    /**
     * @covers Mumsys_I18n_Default::_t
     */
    public function test_t()
    {
        $expected = 'to translate';
        $actual = $this->_object->_t($expected);

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_I18n_Default::_dt
     */
    public function test_dt()
    {
        $expected = 'to translate';
        $actual = $this->_object->_dt('domain', $expected);

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_I18n_Default::_dtn
     */
    public function test_dtn()
    {
        $expected = 'Flower';
        $actual = $this->_object->_dtn('domain', $expected, 'Flowers', 2); //two flowers

        $this->assertEquals($expected . 's', $actual);
    }

}
