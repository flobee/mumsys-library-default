<?php

/* {{{ */
/**
 * Mumsys_Xml_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Xml
 */
/* }}} */


/**
 * Class for xml code creation, validation and filtering
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Xml
 */
abstract class Mumsys_Xml_Abstract
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '3.2.1';


    /**
     * Returns xml/html tag attributes from array
     *
     * @param array $array List of key/value pairs.
     * @return string String eg: 'name="val" id="key" '
     */
    public static function attributesCreate( array $array = array() )
    {
        $sum = array();
        foreach ($array as $key => &$value) {
            if (!is_scalar($value)) {
                $msg = sprintf('Invalid attribute value for key: "%1$s": "%2$s"', $key, gettype($value));
                throw new Mumsys_Xml_Exception($msg);
            }

            $sum[] = $key . '="' . $value . '"';
        }

        return implode(' ', $sum);
    }

}