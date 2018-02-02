<?php

/**
 * Mumsys_Geolocation_ByIp_Interface
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
 * $Id: Mumsys_Geolocation_ByIp_Interface.php 2908 2013-12-09 11:18:20Z flobee $
 */


/**
 * Geolocation by IP interface for plugins/ driver
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Geolocation
 */
interface Mumsys_Geolocation_ByIp_Interface
{
    /**
     * Initialize Geolocation plugin object.
     *
     * @param string $remoteAddr IP address of the requesting client.
     * @param string $currency Optional; Not implemented yet; Currency code
     * @throws Exception Throws exception if ip address is missing or the service plugin is not available
     */
    public function __construct( $remoteAddr = '', $currency = '' );

    /**
     * Makes all available data from plugin available and returns one or a list of Mumsys_Geolocation_Item's
     *
     * @return Mumsys_Geolocation_Item|false
     */
    public function locate();

    /**
     * Returns a newly created geolocation item.
     *
     * @return Mumsys_Geolocation_Item
     */
    public function createItem();

}
