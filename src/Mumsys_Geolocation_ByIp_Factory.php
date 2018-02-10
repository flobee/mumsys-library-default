<?php

/**
 * Mumsys_Geolocation_ByIp_Factory
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
 * $Id: Mumsys_Geolocation_ByIp_Factory.php 3110 2015-03-13 18:08:32Z flobee $
 */


/**
 * Factory to initialise a geolocation plugin object to get location
 * informations by a given IP address.
 *
 * You dont need this fatory if you know which service you want. Implement that
 * service instead. This factory gives a little help to just use the service
 * whatever is behind.
 * There are also plugins which just work between address and geografical
 * location informations like the google plugin.
 *
 * The following plugins are currently available:
 *  - 'geoip_apache' (fast),
 *  - 'geoip_php' (middle fast, but more features),
 *  - 'geoplugin' (slow, remote service, but more features)
 *
 * Some details to get geoip run for your server:
 * @link http://flobee.cgix.de/free-geoip-for-server-owner-or-hosting-companys
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Geolocation
 */
class Mumsys_Geolocation_ByIp_Factory
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';


    /**
     * Initialize the requested Geolocation plugin object.
     *
     * @param string $service Service plugin to be used. Possible values:
     * 'autodetect', 'geoip_apache', 'geoip_php', 'geoplugin'
     * @param string $remoteAddr IP address of the requesting client
     * @param string $currency Optional; Not implemented yet; Currency code
     *
     * @return Mumsys_Geolocation_Interface Service object
     * @throws Mumsys_Geolocation_Exception If ip address was missing or the
     * service plugin is not available
     */
    public static function getInstance( $service = 'autodetect',
        $remoteAddr = '', $currency = '' )
    {
        if ( empty( $remoteAddr ) ) {
            throw new Mumsys_Geolocation_Exception( 'Missing IP address' );
        }

        $pluginList = array('geoip_apache', 'geoip_php', 'geoplugin');

        if ( $service === 'autodetect' ) {
            $service = self::autodetectService();
            $newService = self::_initService( $service, $remoteAddr, $currency );
        } else if ( in_array( $service, $pluginList ) ) {
            $newService = self::_initService( $service, $remoteAddr, $currency );
        } else {
            $mesg = sprintf( 'Service "%1$s" is not available', $service );
            throw new Mumsys_Geolocation_Exception( $mesg );
        }

        return $newService;
    }


    /**
     * Returns the service name to be used.
     *
     * Find possible services to get geo data by ip address.
     * The order:
     *  - GeoIP (maxmind) apache modul
     *  - GeoIP (maxmind) php extension
     *  - Geoplugin webservice
     *
     * @return string Internal name of the available plugin service
     */
    private static function autodetectService()
    {
        if ( Mumsys_Php_Globals::getServerVar( 'GEOIP_CITY', false ) ) {
            $service = 'geoip_apache';
        } else if ( function_exists( 'geoip_record_by_name' ) ) {
            $service = 'geoip_php';
        } else {
            $service = 'geoplugin';
        }
        return $service;
    }


    /**
     * Initialize a geo service object.
     *
     * @param string $service Service to initialize.
     * @param string $remoteAddr Remote ip address  to initialize.
     * @param string $currencyCode Currency code to initialize.
     *
     * @return Mumsys_Geolocation_Interface Service object
     */
    private static function _initService( $service, $remoteAddr, $currencyCode )
    {
        switch ( $service )
        {
            case 'geoip_apache':
                $suffix = 'GeoIPApache';
                break;

            case 'geoip_php':
                $suffix = 'GeoIPPhp';
                break;

            case 'geoplugin':
                $suffix = 'GeoPlugin';
                break;
        }

        $classname = sprintf( 'Mumsys_Geolocation_ByIp_%1$s', $suffix );

        return new $classname( $remoteAddr, $currencyCode );
    }

}
