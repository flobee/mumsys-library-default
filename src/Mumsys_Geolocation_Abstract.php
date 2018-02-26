<?php

/**
 * Mumsys_Geolocation_Abstract
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
 * Abstract class for geolocation package
 *
 * @todo implement nearby search
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Geolocation
 */
abstract class Mumsys_Geolocation_Abstract
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Url, host or path for the remote service if needed
     * @var string
     */
    protected $_serviceUrl;

    /**
     * Ip address the data belongs to for the moment.
     * @var string
     */
    protected $_ip;

    /**
     * Currency code. Eg. EUR, USD
     * @var string
     */
    protected $_currency;


    /**
     * Initialize Geolocation plugin object.
     *
     * @param string $remoteAddr IP address of the requesting client.
     * @param string $currency Optional; Not implemented yet; Currency code
     */
    public function __construct( $remoteAddr = '', $currency = '' )
    {
        $ipv4err = $ipv6err = false;

        if ( filter_var( $remoteAddr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) === false ) {
            $ipv4err = true;
        }

        if ( filter_var( $remoteAddr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) === false ) {
            $ipv6err = true;
        }

        if ( $ipv4err && $ipv6err ) {
            $mesg = 'Invalid IP address';
            throw new Mumsys_Geolocation_Exception( $mesg );
        }

        $this->_ip = (string) $remoteAddr;
        $this->_currency = (string) $currency;
    }


    /**
     * Returns a newly created geolocation item.
     *
     * @param array $params Parameter to initialize the item
     * @return Mumsys_Geolocation_Item_Default
     */
    public function _createItem( array $params = array() )
    {
        return new Mumsys_Geolocation_Item_Default( $params, false );
    }


    public function nearby( $radius = 10, $direction = 0 )
    {
        /*
          'code'=>'product.location.incircle()',
          'internalcode'=>'
          mprolo."latitude" > $2 - $1 / 111.19493 AND
          mprolo."latitude" < $2 + $1 / 111.19493 AND
          mprolo."longitude" > $3 - $1 / 111.19493 / COS( RADIANS( $3 ) ) AND
          mprolo."longitude" < $3 + $1 / 111.19493 / COS( RADIANS( $3 ) ) AND
          ACOS(
          SIN( RADIANS( $2 ) ) * SIN( RADIANS( mprolo."latitude" ) ) +
          COS( RADIANS( $2 ) ) * COS( RADIANS( mprolo."latitude" ) ) *
          COS( RADIANS( mprolo."longitude" ) - RADIANS( $3 ) )
          ) * 6371.0',
          'label'=>'Product locations within a radius, parameter(radius in km,
         * latitude in degrees, longitude in degrees)',
          'type'=> 'float',
         */
// http://www.zwanziger.de/gc_tools_coorddist.html
    }


    /**
     * Returns geo data from requested url.
     *
     * @param string $url Url or host to fetch geo data from
     *
     * @return mixed Returns content containing geo data
     *
     * @throws Mumsys_Geolocation_Exception
     */
    protected function fetch( $url )
    {
        $data = false;
        if ( function_exists( 'curl_init' ) ) {
            $curl = curl_init();
            curl_setopt( $curl, CURLOPT_URL, $url );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
            $version = 'Mumsys_Geolocation PHP Class v:' . self::VERSION;
            curl_setopt( $curl, CURLOPT_USERAGENT, $version );
            $data = curl_exec( $curl );
            curl_close( $curl );
        } else if ( ini_get( 'allow_url_fopen' ) ) {
            $data = file_get_contents( $url );
        } else {
            $message = sprintf(
                'Geolocation: Can not fetch data form "%1$s"',
                $url
            );
            throw new Mumsys_Geolocation_Exception( $message, 1 );
        }

        if ( $data === false ) {
            $mesg = 'Service error. Fetching data failt';
            throw new Mumsys_Geolocation_Exception( $mesg, 500 );
        }

        return $data;
    }

}
