<?php

/**
 * Mumsys_Geolocation_ByIp_GeoPlugin
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 * @verion      1.0.0
 * Created: 2013-11-01, renew 2018
 * $Id: Mumsys_Geolocation_Abstract.php 2910 2013-12-09 11:43:09Z flobee $
 */


/**
 * GeoPlugin remote service implementation.
 *
 * This PHP class uses the PHP Webservice of http://www.geoplugin.com/ to
 * geolocate IP addresses.
 * Geographical location of the IP address (visitor) and locate currency
 * (symbol, code and exchange rate) are returned.
 * See http://www.geoplugin.com/webservices/php for more specific details of
 * this free service.
 * Please read details/ restrictions of this service.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 */
class Mumsys_Geolocation_ByIp_GeoPlugin
    extends Mumsys_Geolocation_Abstract
    implements Mumsys_Geolocation_ByIp_Interface
{
    /**
     * Url to request the service.
     * @var string
     */
    protected $_serviceUrl = 'http://www.geoplugin.net/json.gp?ip={IP}{CURRENCY}';


    /**
     * Initialize Geolocation plugin object.
     *
     * @param string $remoteAddr IP address of the requesting client.
     * @param string $currency Optional; Not implemented yet; Currency code
     */
    public function __construct( $remoteAddr = '', $currency = '' )
    {
        $this->_ip = (string) $remoteAddr;
        $this->_currency = (string) $currency;
    }


    /**
     * Returns all available data from plugin available.
     *
     * @return Mumsys_Geolocation_Item|false Object containing all properties which can be set from the service
     * @throws Mumsys_Geolocation_Exception
     */
    public function locate()
    {
        $search = array('{IP}', '{CURRENCY}');
        $replace = array($this->_ip, '&base_currency=' . $this->_currency);

        $this->_serviceUrl = str_replace( $search, $replace, $this->_serviceUrl );

        $data = array();

        $response = $this->fetch( $this->_serviceUrl );

        $stdObj = json_decode( $response );

        if ( (int) $stdObj->geoplugin_status == 200 ) {
            $init = array(
                'publisher' => array(
                    'name' => 'geoplugin',
                    'language' => 'en',
                    'copyright' => (string) $stdObj->geoplugin_credit,
                ),
                'location' => array(
                    'city' => (string) $stdObj->geoplugin_city,
                    'region' => (string) $stdObj->geoplugin_region,
                    'countryCode' => (string) $stdObj->geoplugin_countryCode,
                    'countryName' => (string) $stdObj->geoplugin_countryName,
                    'continentCode' => (string) $stdObj->geoplugin_continentCode,
                    'latitude' => (float) $stdObj->geoplugin_latitude,
                    'longitude' => (float) $stdObj->geoplugin_longitude,
                    'currencyCode' => (string) $stdObj->geoplugin_currencyCode,
                    'currencySymbol' => (string) $stdObj->geoplugin_currencySymbol,
                    'currencyConverter' => (string) $stdObj->geoplugin_currencyConverter,
                )
            );

            if ( !empty( $stdObj->geoplugin_areaCode ) ) {
                $init['location']['areaCode'] = (string) $stdObj->geoplugin_areaCode;
            }
            if ( !empty( $stdObj->geoplugin_dmaCode ) ) {
                $init['location']['dmaCode'] = (string) $stdObj->geoplugin_dmaCode;
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
            'publisher' => array('name' => 'geoplugin', 'language' => 'en'),
        );
        return $this->_createItem( $init );
    }


    /**
     * Returns list of locations from a "near by search".
     *
     * @param integer $radius Number in Km to search around the current location by latitude and longitude
     * @param integer $limit Number to limit the number of results.
     * @return type
     */
    public function nearby( $radius = 10, $limit = 0 )
    {
        if ( !is_numeric( $this->_latitude ) || !is_numeric( $this->_longitude ) ) {
            throw new Mumsys_Geolocation_Exception( __METHOD__ . ': Invalid latitude or longitude' );
        }

        $url = 'http://www.geoplugin.net/extras/nearby.gp?lat=' . $this->_latitude
            . '&long=' . $this->_longitude
            . '&radius=' . $radius;

        if ( $limit && is_numeric( $limit ) ) {
            $url .= '&limit=' . $limit;
        }

        $url .= '&format=xml';

        return json_decode( $this->fetch( $url ) );
    }

}
