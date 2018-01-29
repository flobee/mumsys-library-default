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
 * @see lib/mumsys2/Mumsys_Weather_Factory.php
 * @filesource
 * @author      Florian Blasel <info@flo-W-orks.com>
 * @copyright   Copyright (c) 2013, Florian Blasel for FloWorks Company
 * @license     All rights reseved
 */
/*}}}*/



/**
 * Factory to initialize weather plugin object.
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Weather
 */
class Mumsys_Weather_Factory
{
    /**
     * Initialize and returns weather plugin object.
     *
     * @todo only openweathermaps is implemented now.
     *
     * @param array $params Basic parameters to be set for the driver:
     * - [format] string Format of service response: json (default), xml, html
     * - [unit] string Unit can be 'metric', 'imperial', 'internal' (default: metric).
     * - [language] string A language code like en, de, es, fr up to five characters if a locale is needed.
     * - [apikey] string Your application key/ token/ access key your need to access the data. Max 40 character
     * @param string $service Service plugin to be used. Possible implementations: 'autodetect' (to find one of the
     * following and in this order (to speed up things):) 'openweathermaps'
     *
     * @return Mumsys_Weather_Interface
     * @throws Exception
     */
    public static function getInstance( $service = 'autodetect', array $params = array() )
    {
        if ( $service == 'autodetect' ) {
            $service = self::autodetectService();
            $newService = self::_initService($service, $params);
        } else if ( in_array($service, array('openweathermaps')) ) {
            $newService = $this->_initService($service, $params);
        } else {
            $newService = new Mumsys_Weater_OpenWeatherMap($params);
        }

        return $newService;
    }


    /**
     * Find possible services to get weather data.
     *
     * @return string internal name of the available plugin service
     */
    private static function autodetectService()
    {
        $service = 'openweathermaps';

        return $service;
    }


    /**
     * Initialize weather service object.
     *
     * @param string $service Service to initialize.
     */
    private static function _initService( $service, array $params = array() )
    {
        switch ( $service )
        {
            case 'openweathermaps':
                $newService = new Mumsys_Weather_OpenWeatherMap($params);
                break;
        }

        return $newService;
    }


}
