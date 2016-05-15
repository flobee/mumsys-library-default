<?php

/* {{{ */
/**
 * Mumsys_Html
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Html
 */
/* }}} */

/* {{{ */
/**
 * MUMSYS 2 Library for Multi User Management Interface
 *
 * LICENSE
 *
 * All rights reseved
 * DO NOT COPY OR CHANGE ANY KIND OF THIS CODE UNTIL YOU  HAVE THE
 * WRITTEN/ BRIFLY PERMISSION FROM THE AUTOR, THANK YOU
 * -----------------------------------------------------------------------
 * @category mumsys_library
 * @package mumsys_library
 * @copyright Copyright (c) 2009 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <info@flo-W-orks.com>
 * @version 0.1 - Created on 2009-11-12
 * $Id: Mumsys_Html.php 2980 2013-12-19 13:40:29Z flobee $
 * -----------------------------------------------------------------------
 */
/* }}} */


/**
 * Class for html code creation, validation and filtering
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Html
 */
class Mumsys_Html
    extends Mumsys_Xml_Abstract
{
    /**
     * Universal Html attributes which are always allowed in here
     * @var array
     */
    protected static $_attributesDefaultAllowed = array(
        'title',
        'style',
        'id',
        'class',
        'lang',
        'dir',
    );

    /**
     * Html tags which are allowed when parsing incomming html code
     * @var array
     */
    protected static $_htmlTagsDefaultAllowed = array(
        'noscript' => array(),
        'a' => array('href', 'name', 'alt'),
        'b' => array(),
        'blockquote' => array(),
        'br' => array(),
        'code' => array(),
        'div' => array(),
        'dd' => array(),
        'dl' => array(),
        'dt' => array(),
        'em' => array(),
        'font' => array(),
        'h1' => array('align'),
        'h2' => array('align'),
        'h3' => array('align'),
        'h4' => array('align'),
        'h5' => array('align'),
        'h6' => array('align'),
        'hr' => array('align'),
        'i' => array(),
        'img' => array('src', 'title', 'border', 'heigth', 'width', 'hspace', 'vspace'),
        'li' => array(),
        'ol' => array(),
        'p' => array('align'),
        'pre' => array(),
        'strong' => array(),
        'span' => array(),
        'table' => array('border', 'cellpadding', 'cellspacing'),
        'tt' => array(),
        'th' => array(),
        'tr' => array('align', 'width'),
        'td' => array('align', 'width', 'nowrap', 'colspan', 'rowspan'),
        'u' => array(),
        'ul' => array(),
    );


    /**
     * Create Html Attributes from array
     *
     * @param array $array associative array with key and value to create xhtml atributes
     * @return string compiles string eg: 'name="val" id="key" '
     */
    public static function attributesCreate( array $array = array() )
    {
        try {
            $return = parent::attributesCreate($array);
        } catch (Exception $e) {
            throw new Mumsys_Html_Exception($e->getMessage());
        }
    }


    /**
     * Re-format the html tag.
     *
     * @param string $tag String of the html tag
     * @param string $stringAttributes String with attributes
     * @return string
     */
    protected static function _formatTag( $tag, $stringAttributes = false )
    {
        switch ($tag) {
            case 'br':
            case 'hr':
            case 'input':
            case 'img':
            case 'link':
            case 'meta':
                if ($stringAttributes) {
                    $tag = '<' . $tag . ' ' . $stringAttributes . ' />';
                } else {
                    $tag = '<' . $tag . ' />';
                }
                break;
            default:
                $tag = parent::_formatTag($tag, $stringAttributes);
                break;
        }

        return $tag;
    }

}