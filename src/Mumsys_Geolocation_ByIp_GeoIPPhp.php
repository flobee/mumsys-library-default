<?php

/**
 * Mumsys_Geolocation_ByIp_GeoIPPhp
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
 * $Id: Mumsys_Geolocation_ByIp_GeoIPPhp.php 2908 2013-12-09 11:18:20Z flobee $
 */


/**
 * GeoIP php extension implementation.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 */
class Mumsys_Geolocation_ByIp_GeoIPPhp
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
     * properties which can be set from the service
     *
     * @throws Mumsys_Geolocation_Exception
     */
    public function locate()
    {
        // php geoip modul, the most required informations go by
        $location = geoip_record_by_name( $this->_ip );

        if ( $location && is_array( $location ) ) {
            $init = array(
                'publisher' => array(
                    'name' => 'geoip_php',
                    'language' => 'en',
                ),
                'location' => array(
                    'city' => mb_convert_encoding( $location['city'], 'UTF-8', 'ISO-8859-1' ),
                    'region' => (string) $location['region'],
                    'countryCode' => (string) $location['country_code'],
                    'countryName' => (string) $location['country_name'],
                    'continentCode' => (string) $location['continent_code'],
                    'latitude' => (float) $location['latitude'],
                    'longitude' => (float) $location['longitude'],
                    'tz_name' => geoip_time_zone_by_country_and_region(
                        $location['country_code'], $location['region']
                    ),
                )
            );

            if ( !empty( $location['area_code'] ) ) {
                $init['location']['phonePrefixCode'] = (string) $location['area_code'];
            }

            if ( !empty( $location['postal_code'] ) ) {
                $init['location']['areaCode'] = (string) $location['postal_code'];
            }

            if ( !empty( $location['dma_code'] ) ) {
                $init['location']['dmaCode'] = (string) $location['dma_code'];
            }

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
            'publisher' => array('name' => 'geoip_php', 'language' => 'en'),
        );
        return $this->_createItem( $init );
    }

}
