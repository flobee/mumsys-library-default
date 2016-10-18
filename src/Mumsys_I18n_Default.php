<?php

/**
 * Mumsys_I18n_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2013 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_I18n
 * Created: 2013-12-17
 */


/**
 * Default/ simple implementation for the internationalization Interface (I18n).
 *
 * There is no real translation! This is just the wrapper class as default for
 * further implementations e.g. using "gettext" or other drivers.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_I18n
 */
class Mumsys_I18n_Default
    extends Mumsys_I18n_Abstract
    implements Mumsys_I18n_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '3.2.1';


    /**
     * Initialise the I18n default driver
     *
     * @param string $locale The locale string the translation belongs to e.g.
     * de or de_DE,
     * @throws Mumsys_I18n_Exception On errors with the locale
     */
    public function __construct( $locale = '' )
    {
        parent::__construct($locale);
    }


    /**
     * Simple translation.
     *
     * @param string $string String to translate
     * @return string The translated string (or the source string if no translation was found)
     * @throws Mumsys_I18n_Exception Throws exception on errors
     */
    public function _t( $string )
    {
        return $string;
    }


    /**
     * Retruns translated string by given domain.
     *
     * @param string $domain Translation domain
     * @param string $string String to translate
     * @return string The translated string (or the source string if no translation was found)
     * @throws Mumsys_I18n_Exception Throws exception on errors
     */
    public function _dt( $domain, $string )
    {
        return $string;
    }


    /**
     * Returns the translated string by the given domain, plural and quantity.
     *
     * @param string $domain Translation domain
     * @param string $singular Singular string
     * @param string $plural Plural string
     * @param integer $number Quantity for languages with more than one plural form
     *
     * @return string Returns the translated string as singular or plural form based on given number.
     * @throws Mumsys_I18n_Exception Throws exception on errors
     */
    public function _dtn( $domain, $singular, $plural, $number )
    {
        $index = $this->getPluralIndex($number);

        if ( $index >= 1 ) {
            return $plural;
        }

        return $singular;
    }

}
