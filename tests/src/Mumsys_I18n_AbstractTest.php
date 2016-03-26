<?php

/**
 * Mumsys_I18n_Abstract Test
 */
class Mumsys_I18n_AbstractTest extends PHPUnit_Framework_TestCase
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
     * @covers Mumsys_I18n_Abstract::__construct
     */
    public function test_construct()
    {
        $this->setUp();

        $this->setExpectedException('Mumsys_I18n_Exception', 'Invalid locale "biglocale"');
        $o = new Mumsys_I18n_Default('biglocale');
    }


    /**
     * @covers Mumsys_I18n_Abstract::setlocale
     * @covers Mumsys_I18n_Abstract::getlocale
     */
    public function testGetSetlocale()
    {
        $expected = 'de_de';
        $this->_object->setlocale($expected);
        $actual = $this->_object->getLocale();

        $this->assertEquals($expected, $actual);

        $this->setExpectedException('Mumsys_I18n_Exception', 'Invalid locale "invalid"');
        $this->_object->setlocale('invalid');
    }


    /**
     * @covers Mumsys_I18n_Abstract::getPluralIndex
     */
    public function testGetPluralIndex()
    {
        $listOfLocales = array(
            0 => array(
                'am', 'ar', 'bh', 'fil', 'fr', 'gun', 'hi', 'ln', 'lv', 'mg',
                'nso', 'xbr', 'ti', 'wa', 'pt_BR'
            ),
            1 => array(
                'af', 'az', 'bn', 'bg', 'ca', 'da', 'de', 'el', 'en', 'eo',
                'es', 'et', 'eu', 'fa', 'fi', 'fo', 'fur', 'fy', 'gl', 'gu',
                'ha', 'he', 'hu', 'is', 'it', 'ku', 'lb', 'ml', 'mn', 'mr',
                'nah', 'nb', 'ne', 'nl', 'nn', 'no', 'om', 'or', 'pa', 'pap',
                'ps', 'pt', 'so', 'sq',
                'sv', 'sw', 'ta', 'te', 'tk', 'ur', 'zu',
                'be', 'bs', 'hr', 'ru', 'sr', 'uk', 'cs', 'sk',
                'cy', 'ga', 'mt', 'pl', 'ro', 'lt', 'mk', 'sl'
            ),
        );

        foreach ($listOfLocales as $number => $locales) {
            foreach ($locales as $lc) {
                $this->_object->setlocale($lc);
                $this->assertEquals('Flower', $this->_object->_dtn('domain', 'Flower', 'Flowers', $number));
            }
        }

        $this->_object->setlocale('xxxx');
        $this->assertEquals('Flower', $this->_object->_dtn('domain', 'Flower', 'Flowers', 1));
    }

}
