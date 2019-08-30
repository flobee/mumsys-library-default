<?php

/**
 * Mumsys_Geolocation_Item_Default
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
 * $Id: Mumsys_Geolocation_Item.php 2908 2013-12-09 11:18:20Z flobee $
 */


/**
 * Geolocation item containing all informations which may come from a location
 * service or a storrage.
 *
 * Note: This is a data container as array for the moment.
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Geolocation
 */
class Mumsys_Geolocation_Item_Default
{
    /**
     * The store for all data. See $_itemDefaults for all possible properties.
     * @var array
     */
    protected $_record = array();

    /**
     * Array containing the list of all possible properties.
     * @var type
     */
    private $_itemDefaults = array(
        'publisher' => array(
            'id',
            'name',
            'lastupdate',
            'language',
            'copyright'
        ),
        'location' => array(
            'id',
            'thatid',
            'street',
            'areaCode',
            'city',
            'region',
            'countryName',
            'countryCode',
            'fromattedAddr',
            'continentCode',
            'sunrise',
            'sunset',
            'latitude',
            'longitude',
            'altitude',
            'tz_offset',
            'tz_name',
            'dmaCode',
            'currencyCode',
            'currencySymbol',
            'currencyConverter',
            'phonePrefixCode',
        ),
    );


    /**
     * Initialize the geolocation item object.
     * If flag $strictlyAll is true all possible values will be set with NULL.
     * This is helpful to get a complete list for all properties.
     *
     * @param array $params List of properties to be set:
     *  - 'publisher' possible properties are: 'id', 'name', 'lastupdate',
     * 'language', 'copyright'
     *  - 'location' possible properties are:  'id', 'thatid', 'street',
     * 'areaCode', 'city', 'region', 'countryName', 'countryCode',
     * 'fromattedAddr', 'continentCode', 'sunrise', 'sunset', 'latitude',
     * 'longitude', 'altitude', 'tz_offset', 'tz_name', 'dmaCode',
     * 'currencyCode', 'currencySymbol', 'currencyConverter', 'phonePrefixCode'
     * @param boolean $strictlyAll Flag to set all possible values or just the
     * data you have.
     */
    public function __construct( array $params = array(),
        bool $strictlyAll = false )
    {
        if ( $strictlyAll ) {
            $this->_record = $this->_getItemDefaults( $this->_itemDefaults );
        }

        if ( $params ) {
            if ( isset( $params['publisher'] ) ) {
                $this->setPublisher( $params['publisher'] );
            }
            if ( isset( $params['location'] ) ) {
                $this->setLocation( $params['location'] );
            }
        }
    }


    /**
     * Retruns a list of publisher informations.
     *
     * @return array List of key/value pairs which was set.
     */
    public function getPublisher()
    {
        return $this->_record['publisher'];
    }


    /**
     * Sets the publisher informations
     *
     * @param array $publ Properties for the publisher:
     *  - 'id' Unique identifier for the publisher on this system/enviroment/
     * serverfarm...
     *  - 'name' string Name of the publisher
     *  - 'lastupdate' int Unix timestamp UTC
     *  - 'language' string  Language code or locale all text data belongs to in
     * description or 'name' value
     *  - 'copyright' string Copyright notes, terms, required or important
     * informations
     */
    public function setPublisher( array $publ = array() )
    {
        if ( isset( $publ['id'] ) ) {
            $this->_record['publisher']['id'] = (string) $publ['id'];
        }
        if ( isset( $publ['name'] ) ) {
            $this->_record['publisher']['name'] = (string) $publ['name'];
        }
        if ( isset( $publ['lastupdate'] ) ) {
            $this->_record['publisher']['lastupdate'] = (int) $publ['lastupdate'];
        }
        if ( isset( $publ['language'] ) ) {
            $this->_record['publisher']['language'] = (string) $publ['language'];
        }
        if ( isset( $publ['copyright'] ) ) {
            $this->_record['publisher']['copyright'] = (string) $publ['copyright'];
        }
    }


    /**
     * Retruns a list of location informations.
     *
     * @return array List of key/value pairs which was set.
     */
    public function getLocation()
    {
        return $this->_record['location'];
    }


    /**
     * Sets the location informations.
     *
     * @param array $location Properties for the location:
     *  - 'id' string Value which belongs to this system/enviroment. The unique
     * id for this location at this end.
     *  - 'thatid' string Value which belongs to the publisher. The unique id
     * for this location at their end
     *  - 'street' string Name of the street including house number
     *  - 'areaCode' string The Postal Code, FSA or Zip Code.
     *  - 'region' string Name of the region. State in a country. Eg.
     * "Bundesland" or "California" in the USA
     *  - 'city' string Name of the location
     *  - 'countryName' string Full name of the country
     *  - 'countryCode' string Code of the country. Country code by ISO 3166.
     * E.g.: DE, US, FR, AT, CH, RU, UA
     *  - 'fromattedAddr' string Complete formatted address
     *  - 'continentCode' string Code of the continent. Eg.: AF -Africa, AN -
     * Antarctica, AS - Asia, EU - Europe, NA - North america, OC - Oceania,
     * SA - South america.
     *  - 'sunrise' integer Unix timestamp (local time) of the timezone this
     * location belong to
     *  - 'sunset' integer Unix timestamp (local time) of the timezone this
     * location belong to
     *  - 'latitude' float Latitude value of this location
     *  - 'longitude' float Longitude value of this location
     *  - 'altitude' float Altitude value of this location
     *  - 'tz_offset' float Offset in hours e.g.: -7 for -0700 or -07:00
     *  - 'tz_name' string Name of the timezone e.g.: Europe/Berlin
     *  - 'dmaCode' string Designated Market Area code (USA and Canada only)
     *  - 'currencyCode' string Currency code e.g. EUR (€, Euro)
     *  - 'currencySymbol' string Currency symbol like: € or $
     *  - 'currencyConverter' float Conversion factor tba.
     *  - 'phonePrefixCode' string Telephone prefix
     */
    public function setLocation( array $location = array() )
    {
        if ( isset( $location['id'] ) ) {
            $this->_record['location']['id'] = (string) $location['id'];
        }
        if ( isset( $location['thatid'] ) ) {
            $this->_record['location']['thatid'] = (string) $location['thatid'];
        }
        if ( isset( $location['street'] ) ) {
            $this->_record['location']['street'] = (string) $location['street'];
        }
        if ( isset( $location['areaCode'] ) ) {
            $this->_record['location']['areaCode'] = (string) $location['areaCode'];
        }
        if ( isset( $location['region'] ) ) {
            $this->_record['location']['region'] = (string) $location['region'];
        }
        if ( isset( $location['city'] ) ) {
            $this->_record['location']['city'] = $location['city'];
        }
        if ( isset( $location['countryName'] ) ) {
            $this->_record['location']['countryName'] = (string) $location['countryName'];
        }
        if ( isset( $location['countryCode'] ) ) {
            $this->_record['location']['countryCode'] = (string) $location['countryCode'];
        }
        if ( isset( $location['fromattedAddr'] ) ) {
            $this->_record['location']['fromattedAddr'] = (string) $location['fromattedAddr'];
        }
        if ( isset( $location['continentCode'] ) ) {
            $this->_record['location']['continentCode'] = substr( (string) $location['continentCode'], 0, 2 );
        }
        if ( isset( $location['sunrise'] ) ) {
            $this->_record['location']['sunrise'] = (int) $location['sunrise'];
        }
        if ( isset( $location['sunset'] ) ) {
            $this->_record['location']['sunset'] = (int) $location['sunset'];
        }
        if ( isset( $location['latitude'] ) ) {
            $this->_record['location']['latitude'] = (float) $location['latitude'];
        }
        if ( isset( $location['longitude'] ) ) {
            $this->_record['location']['longitude'] = (float) $location['longitude'];
        }
        if ( ( isset( $location['altitude'] ) ) ) {
            $this->_record['location']['altitude'] = (float) $location['altitude'];
        }
        if ( ( isset( $location['tz_offset'] ) ) ) {
            $this->_record['location']['tz_offset'] = (float) $location['tz_offset'];
        }
        if ( ( isset( $location['tz_name'] ) ) ) {
            $this->_record['location']['tz_name'] = (string) $location['tz_name'];
        }
        if ( ( isset( $location['dmaCode'] ) ) ) {
            $this->_record['location']['dmaCode'] = (string) $location['dmaCode'];
        }
        if ( ( isset( $location['currencyCode'] ) ) ) {
            $this->_record['location']['currencyCode'] = substr( (string) $location['currencyCode'], 0, 4 );
        }
        if ( ( isset( $location['currencySymbol'] ) ) ) {
            $this->_record['location']['currencySymbol'] = (string) $location['currencySymbol'];
        }
        if ( ( isset( $location['currencyConverter'] ) ) ) {
            $this->_record['location']['currencyConverter'] = (float) $location['currencyConverter'];
        }
        if ( ( isset( $location['phonePrefixCode'] ) ) ) {
            $this->_record['location']['phonePrefixCode'] = (string) $location['phonePrefixCode'];
        }
    }


    /**
     * Returns all data in a homogeneous structure as array.
     *
     * @see $_itemDefaults for property list and descriptions
     *
     * @return array Returns the records for this item object
     */
    public function toArray()
    {
        return $this->_record;
    }


    /**
     * Returns the hole defaults properties from given array.
     *
     * @param array $params Properties config as array values to initialize as
     * key.
     * @return array Returns list of key/value pairs where the value ist set to
     * be null
     */
    private function _getItemDefaults( array $params = array() )
    {
        $item = array();
        foreach ( $params as $key => $value ) {
            if ( is_array( $value ) ) {
                $x = $this->_getItemDefaults( $value );
                if ( $x ) {
                    $item[$key] = $x;
                }
            } else {
                $item[$value] = null;
            }
        }

        return $item;
    }

}
