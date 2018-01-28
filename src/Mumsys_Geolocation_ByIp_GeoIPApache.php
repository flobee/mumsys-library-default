<?php

/**
 * Mumsys_Geolocation_ByIp_GeoIPApache
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 * @version     1.0.0
 * Created: 2013-11-01
 * $Id: Mumsys_Geolocation_ByIp_GeoIPApache.php 2908 2013-12-09 11:18:20Z flobee $
 */


/**
 * GeoIP Apache module implementation.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 */
class Mumsys_Geolocation_ByIp_GeoIPApache
    extends Mumsys_Geolocation_Abstract
    implements Mumsys_Geolocation_ByIp_Interface
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';


    /**
     * Returns all available data from plugin available.
     *
     * @return Mumsys_Geolocation_Item_Default|false Object containing all
     * properties which are existsfor the service
     * @throws Mumsys_Geolocation_Exception If the service is not available
     */
    public function locate()
    {
        // apache city db exists ?

        $remoteAddr = Mumsys_Php_Globals::getServerVar( 'GEOIP_ADDR', false );
        $region = Mumsys_Php_Globals::getServerVar( 'GEOIP_REGION_NAME', false );

        if ( $remoteAddr && $region ) {
            $this->_ip = strip_tags( $remoteAddr );

            $init = array(
                'publisher' => array(
                    'name' => 'geoip_apache',
                    'language' => 'en',
                ),
                'location' => array(
                    'city' => filter_input(
                        INPUT_SERVER, 'GEOIP_CITY', FILTER_SANITIZE_STRING
                    ),
                    'region' => strip_tags( (string) $region ),
                    'areaCode' => filter_input(
                        INPUT_SERVER, 'GEOIP_AREA_CODE', FILTER_SANITIZE_STRING
                    ),
                    'countryCode' => filter_input(
                        INPUT_SERVER, 'GEOIP_COUNTRY_CODE', FILTER_SANITIZE_STRING
                    ),
                    'countryName' => filter_input(
                        INPUT_SERVER, 'GEOIP_COUNTRY_NAME', FILTER_SANITIZE_STRING
                    ),
                    'continentCode' => filter_input(
                        INPUT_SERVER, 'GEOIP_CONTINENT_CODE', FILTER_SANITIZE_STRING
                    ),
                    'latitude' => filter_input(
                        INPUT_SERVER, 'GEOIP_LATITUDE', FILTER_SANITIZE_NUMBER_FLOAT
                    ),
                    'longitude' => filter_input(
                        INPUT_SERVER, 'GEOIP_LONGITUDE', FILTER_SANITIZE_NUMBER_FLOAT
                    ),
                    'dmaCode' => filter_input(
                        INPUT_SERVER, 'GEOIP_DMA_CODE', FILTER_SANITIZE_STRING
                    ),
                )
            );

            return $this->_createItem( $init );
        }

        return false;
    }


    /**
     * Returns a newly created geolocation item.
     *
     * @param array $params Parameter to initialize the item
     * @return Mumsys_Geolocation_Item
     */
    public function createItem()
    {
        $init = array(
            'publisher' => array('name' => 'geoip_apache', 'language' => 'en'),
        );
        return $this->_createItem( $init );
    }

}
