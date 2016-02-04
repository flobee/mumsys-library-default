<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_I18n_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_I18n
 * @version     1.0.0
 * Created: 2013-12-17
 * @filesource
 */
/*}}}*/


/**
 * Internationalization Interface (I18n)
 *
 * http://de.wikipedia.org/wiki/I18n
 * http://www.w3.org/International/questions/qa-i18n.de.php
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_I18n
 */
interface Mumsys_I18n_Interface
{
    /**
     * Returns the plural index number to be used for the plural translation.
     *
     * @param  integer $number Number to find the plural form
     * @param  string  $locale Locale to use
     *
     * @return integer Number of the plural index
     */
    public static function getPluralIndex( $number, $locale );

    /**
     * Sets the system locale by given locale string.
     *
     * @param string $locale Locale to be set.
     */
    public function setlocale( $locale );

    /**
     * Simple translation.
     *
     * @param string $string String to translate
     * @return string The translated string (or the source string if no translation was found)
     * @throws Mumsys_I18n_Exception Throws exception on errors
     */
    public static function t( $string );

    /**
     * Domain translation.
     *
     * @param string $domain Translation domain
     * @param string $string String to translate
     * @return string The translated string (or the source string if no translation was found)
     * @throws Mumsys_I18n_Exception Throws exception on errors
     */
    public static function dt( $domain, $string );

    /**
     * Domain translation plural. Returns the translated string by the given plural and quantity.
     *
     * @param string $domain Translation domain
     * @param string $singular Singular string
     * @param string $plural Plural string
     * @param integer $number Quantity for languages with plural forms
     *
     * @return string Returns the translated string as singular or plural form based on given number.
     * @throws Mumsys_I18n_Exception Throws exception on errors
     */
    public static function dtn( $domain, $singular, $plural, $number );

}
