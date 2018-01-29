<?php





/**
 * Mumsys_Weather_Item_Default Test
 * Generated on 2013-12-06 at 17:40:19.
 */
class Mumsys_Weather_Item_DefaultTest extends Mumsys_Unittest_Testcase
{

    /**
     * @var Mumsys_Weather_Item_Default
     */
    protected $_object;
    protected $_prps;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

        $this->_prps = array(
            'publisher' => array(
                'id' => 123,
                'name' => 'publisher name',
            ),
            'lastupdate' => 12345,
            'language' => 'de',
            'location' => array(
                'id' => 88,
                'name' => 'Hamburg',
                'country' => 'Germany',
                'countryCode' => 'DE',
                'sunrise' => 1,
                'sunset' => 100,
                'latitude' => 53.55,
                'longitude' => 10.00,
                'altitude' => 0,
                'tz_offset' => '+02:00',
            ),
            'description' => array(
                'id' => 'ISHd4',
                'key' => 'weather desc',
                'name' => 'wetter beschr.',
                'icon' => 'img.jpg',
            ),
            'temperature' => array(
                'value' => 30.0,
                'min' => 15.0,
                'max' => 45.0,
                'night' => 12.0,
                'eve' => 15.0,
                'morn' => 10.0,
                'unit' => array(
                    'key' => 'degreese',
                    'name' => 'Grad',
                    'sign' => '°',
                    'code' => 'no code',
                ),
            ),
            'pressure' => array(
                'value' => 1006.0,
                'sea' => 996.0,
                'ground' => 1006.6,
                'unit' => array(
                    'key' => 'hectopascal',
                    'name' => 'Hektopascal',
                    'sign' => 'no sign',
                    'code' => 'hPa',
                ),
                'trend' => -1,
            ),
            'humidity' => array(
                'value' => 75,
                'unit' => array(
                    'key' => 'percent',
                    'name' => 'Prozent',
                    'sign' => '%',
                    'code' => 'no code',
                ),
            ),
            'visibility' => array(
                'value' => 0.8,
                'unit' => array(
                    'key' => 'kilometer',
                    'name' => 'Kilometer',
                    'sign' => 'no sign',
                    'code' => 'km',
                ),
            ),
            'wind' => array(
                'speed' => array(
                    'value' => 5,
                    'min' => 4,
                    'max' => 6,
                    'unit' => array(
                        'key' => 'meter per second',
                        'name' => 'Meter pro Sekunde',
                        'sign' => 'no sign',
                        'code' => 'm/s',
                    ),
                    'key' => 'strong breese',
                    'name' => 'Steife briese',
                    'gust' => 7,
                ),
                'direction' => array(
                    'value' => 300,
                    'begin' => 290,
                    'end' => 310,
                    'unit' => array(
                        'key' => 'degreese',
                        'name' => 'Grad',
                        'sign' => '°',
                        'code' => 'no code',
                    ),
                    'key' => 'Wind Name- description',
                    'name' => 'Wind- Name/ Bezeichnung',
                    'code' => 'WWS',
                ),
                'chill' => array(
                    'value' => 10,
                    'unit' => array(
                        'key' => 'degreese',
                        'name' => 'Grad',
                        'sign' => '°',
                        'code' => 'no code',
                    ),
                ),
            ),
            'clouds' => array(
                'value' => 85,
                'unit' => array(
                    'key' => 'percent',
                    'name' => 'Prozent',
                    'sign' => '%',
                    'code' => 'no code',
                ),
                'key' => 'clouds desc',
                'name' => 'Wolken beschr.',
            ),
            'precipitation' => array(
                'rain' => array(
                    'value' => 3,
                    'unit' => array(
                        'key' => 'millimetres',
                        'name' => 'Millimeter',
                        'sign' => 'no sign',
                        'code' => 'mm',
                    ),
                    'interval' => '3h',
                ),
                'snow' => array(
                    'value' => 0.5,
                    'unit' => array(
                        'key' => 'millimetres',
                        'name' => 'Millimeter',
                        'sign' => 'no sign',
                        'code' => 'mm',
                    ),
                    'interval' => '1h',
                ),
            ),
        );

        $this->_object = new Mumsys_Weather_Item_Default();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = NULL;
        unset($this->_object);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setPublisher
     */
    public function testSetPublisher()
    {
        $expected = array('id' => '123', 'name' => 'Publisher name');
        $this->_object->setPublisher($expected);
        $actual = $this->_object->toArray();
        $this->assertEquals($expected, $actual['publisher']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setLastupdate
     */
    public function testSetLastupdate()
    {
        $this->_object->setLastupdate($this->_prps['lastupdate']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['lastupdate'], $actual['lastupdate']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setLanguage
     */
    public function testSetLanguage()
    {
        $this->_object->setLanguage($this->_prps['language']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['language'], $actual['language']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setLocation
     */
    public function testSetLocation()
    {
        $this->_object->setLocation($this->_prps['location']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['location'], $actual['location']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setWeatherDescription
     */
    public function testSetWeatherDescription()
    {
        $this->_object->setWeatherDescription($this->_prps['description']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['description'], $actual['description']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setTemperature
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function testSetTemperature()
    {
        $this->_object->setTemperature($this->_prps['temperature']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['temperature'], $actual['temperature']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setPressure
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function testSetPressure()
    {
        $this->_object->setPressure($this->_prps['pressure']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['pressure'], $actual['pressure']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setHumidity
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function testSetHumidity()
    {
        $this->_object->setHumidity($this->_prps['humidity']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['humidity'], $actual['humidity']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setVisibility
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function testSetVisibility()
    {
        $this->_object->setVisibility($this->_prps['visibility']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['visibility'], $actual['visibility']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setWind
     */
    public function testSetWind()
    {
        $this->_object->setWind($this->_prps['wind']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['wind'], $actual['wind']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setWindSpeed
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function testSetWindSpeed()
    {
        $this->_object->setWindSpeed($this->_prps['wind']['speed']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['wind']['speed'], $actual['wind']['speed']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setWindDirection
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function testSetWindDirection()
    {
        $this->_object->setWindDirection($this->_prps['wind']['direction']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['wind']['direction'], $actual['wind']['direction']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setWindChill
     */
    public function testSetWindChill()
    {
        $this->_object->setWindChill($this->_prps['wind']['chill']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['wind']['chill'], $actual['wind']['chill']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setClouds
     */
    public function testSetClouds()
    {
        $this->_object->setClouds($this->_prps['clouds']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['clouds'], $actual['clouds']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setPrecipitation
     */
    public function testSetPrecipitation()
    {
        $this->_object->setPrecipitation($this->_prps['precipitation']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['precipitation'], $actual['precipitation']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setPrecipitationRain
     */
    public function testSetPrecipitationRain()
    {
        $this->_object->setPrecipitationRain($this->_prps['precipitation']['rain']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['precipitation']['rain'], $actual['precipitation']['rain']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::setPrecipitationSnow
     */
    public function testSetPrecipitationSnow()
    {
        $this->_object->setPrecipitationSnow($this->_prps['precipitation']['snow']);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps['precipitation']['snow'], $actual['precipitation']['snow']);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::toArray
     */
    public function testToArray()
    {
        $actual = $this->_object->toArray();
        $this->assertEquals(array(), $actual);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::__construct
     * @covers Mumsys_Weather_Item_Default::_getItemDefaults
     */
    public function testConstruct()
    {
        $this->_object->__construct($this->_prps, true);
        $actual = $this->_object->toArray();
        $this->assertEquals($this->_prps, $actual);
    }


    /**
     * @covers Mumsys_Weather_Item_Default::_createUnit
     */
    public function test_GetUnit()
    {
        $this->_prps['temperature']['unit'] = array();
        $this->_object->setTemperature($this->_prps['temperature']);
        $actual = $this->_object->toArray();
        unset($this->_prps['temperature']['unit']);
        $this->assertEquals($this->_prps['temperature'], $actual['temperature']);

        $this->_prps['pressure']['unit'] = array();
        $this->_object->setPressure($this->_prps['pressure']);
        $actual = $this->_object->toArray();
        unset($this->_prps['pressure']['unit']);
        $this->assertEquals($this->_prps['pressure'], $actual['pressure']);
    }


}
