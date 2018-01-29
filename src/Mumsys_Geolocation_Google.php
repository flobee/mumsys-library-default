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
 * Created: 2013-12-08
 * $Id$
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Geolocation
 * @see lib/mumsys2/Mumsys_Geolocation_Google.php
 * @filesource
 * @author      Florian Blasel <info@flo-W-orks.com>
 * @copyright   Copyright (c) 2013, Florian Blasel for FloWorks Company
 * @license     All rights reseved
 */
/*}}}*/


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
