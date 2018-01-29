<?php




/*{{{*/
/**
 * MUMSYS 2 Library for Multi User Management Interface
 *
 * -----------------------------------------------------------------------
 *
 * LICENSE
 *
 * All rights reseved.
 * DO NOT COPY OR CHANGE ANY KIND OF THIS CODE UNTIL YOU  HAVE THE
 * WRITTEN/ BRIFLY PERMISSION FROM THE AUTOR, THANK YOU
 *
 * -----------------------------------------------------------------------
 * @version {VERSION_NUMBER}
 * Created: 2013-11-01
 * $Id$
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Weather
 * @see lib/mumsys2/Mumsys_Weather_Interface.php
 * @filesource
 * @author      Florian Blasel <info@flo-W-orks.com>
 * @copyright   Copyright (c) 2013, Florian Blasel for FloWorks Company
 * @license     All rights reseved
 */
/*}}}*/


/**
 * Weather Interface for creating and registering weather plugins.
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Weather
 */
interface Mumsys_Weather_Interface
{
    /**
     * Returns a list of Mumsys_Weather_Item's for the current weather specified by query parameter.
     *
     * @param mixed $query Parameter to get the current weather
     * @return array|false List of Mumsys_Weather_Item's
     */
    public function getWeather( $query = '' );

    /**
     * Returns a list of Mumsys_Weather_Item's for the weather forecast specified by query parameter.
     *
     * @param mixed $query Parameter to get the weather forecast
     * @return array|false List of Mumsys_Weather_Item's
     */
    public function getWeatherForecast( $query = '' );


    /**
     * Returns raw data from weather request.
     *
     * @return string|false Raw response string from request
     */
    public function getRawWeather( $query = '' );


    /**
     * Returns the raw data from a previous request used with getRawWeatherData() or getWeather().
     *
     * @return mixed Returns the data from the prevous made request.
     */
    public function getRawData();

    /**
     * Returns the request url.
     * @return string Url of the request.
     */
    public function getRequestUrl();

    /**
     * Returns temperature units.
     *
     * @param string $key Key to identify the unit "metric" (celsius), "imperial" (fahrenheit), "internal" (kelvin)
     * @return \stdClass Object including name, sign and code
     */
    public function getUnitTemperature( $unit = '' );

    /**
     * Returns the code of the wind direction.
     * E.g. SW for South west wind.
     *
     * @param integer $deg wind direction in degrees
     * @param integer $precision Precision of the wind direction to return. 0 = 45° default,  1 = 22.5° steps
     *
     * @return string Code of the wind direction
     * @throws Mumsys_Weather_Exception
     */
    public function getCodeWindDirection( $deg=0, $precision=0 );

}