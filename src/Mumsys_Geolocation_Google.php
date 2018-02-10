<?php

/**
 * Mumsys_Geolocation_Google
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
 * Created: 2013, renew 2018
 */


/**
 * Google remote service implementation.
 *
 * @see https://developers.google.com/maps/documentation/geocoding/
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Geolocation
 */
class Mumsys_Geolocation_Google
    extends Mumsys_Geolocation_Abstract
    //implements Mumsys_Geolocation_Interface
{
    /**
     * Url to request the service.
     * @var string
     */
    protected $_serviceUrl = 'http://maps.googleapis.com/maps/api/geocode/json?';


    /**
     * Returns all available location data.
     *
     * @return array List of key => value pairs. Keys are: <br />
     * 'city', 'region', 'areaCode', 'countryCode', 'countryName', 'continentCode', 'latitude', 'longitude'<br />
     * optional: 'dmaCode', 'currencyCode', 'currencySymbol', 'currencyConverter', 'phonePrefixCode'
     */
    public function locate()
    {
        // sensor=false
        $search = array('{IP}', '{CURRENCY}');
        $replace = array($this->_ip, '&base_currency=' . $this->_currency);

        $this->_serviceUrl = str_replace($search, $replace, $this->_serviceUrl );

		$data = array();

		$response = $this->fetch($this->_serviceUrl);

		$stdObj = json_decode($response);



        return $this->toArray();
	}


}
