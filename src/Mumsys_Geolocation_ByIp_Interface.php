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
 * $Id: Mumsys_Geolocation_ByIp_Interface.php 2908 2013-12-09 11:18:20Z flobee $
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Geolocation
 * @see lib/mumsys2/Mumsys_Geolocation_ByIp_Interface.php
 * @filesource
 * @author      Florian Blasel <info@flo-W-orks.com>
 * @copyright   Copyright (c) 2013, Florian Blasel for FloWorks Company
 * @license     All rights reseved
 */
/*}}}*/


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
