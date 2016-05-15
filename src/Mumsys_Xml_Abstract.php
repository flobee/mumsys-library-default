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
{


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


    /**
     * Check incoming data agains a whitelist and return all allowed key/value pairs.
     *
     * @param array $attributesIn List of attributes to be checked agains the whitelist
     * @param array $whitelist List of allowed attributes
     * @param array $universalAttributes Optional; List of universal attributes to add/ allow (seperated whitelist)
     *
     * @return array Returns a list of allowed attributes and their values
     */
    public static function attributesValidate( array $attributesIn = array(), array $whitelist = array(),
        array $universalAttributes = array() )
    {
        $attrib = array();

        if ($universalAttributes) {
            $whitelist = array_merge($whitelist, $universalAttributes);
        }

        foreach ($attributesIn as $key => &$value) {
            if (in_array($key, $whitelist)) {
                $attrib[$key] = $value;
            }
        }

        return $attrib;
    }


    /**
     * Filter an attribute line for a given tag.
     *
     * Eg: <mytag cellpadding="2" title="a Global Allowed Attribute">
     * First the hole string will be parsed and dublicate whitespaces will be
     * fixed. Invalid quote style will be droped. After it the string will be
     * compared against a whitelist.
     * All code which is not in the whitelist or in a global whitelist will be droped.
     * This is mostly useful for html code than xml!
     * @see $_htmlTagsDefaultAllowed or $_attributesDefaultAllowed in Mumsys_Html class
     *
     * @todo more tests for broken attributes: autocorrection
     * @todo throw errors?
     *
     * @param string $tag The tag for current attributes
     * @param string $stringAttributes String of attributes
     * @param array $allowedAttributes List of allowed attributes
     * @return string Returns a new formatted filtered an validated attribute string
     */
    public static function attributesFilter( $tag, $stringAttributes, array $allowedAttributes = array() )
    {
        $tmp = '';    // string buffer
        $result = ''; // result string
        $i = 0;
        $attrib = -1; // Are we in an HTML attribute ? -1: no attrib, 0: name of the attrib, 1: value of the attrib
        $quote = 0; // a string quote delimited opened ? 0=no, 1=yes
        $len = strlen($stringAttributes);
        while ($i < $len)
        {
            switch ($stringAttributes[$i])
            {
                case '"':      // a quote.
                    if ($quote == 0) {
                        $quote = 1;
                    } else {
                        $quote = 0;
                        if (($attrib > 0) && ($tmp != '')) {
                            $result .= '="' . $tmp . '"';
                        }
                        $tmp = '';
                        $attrib = -1;
                    }
                    break;

                case '=':             // an equal - attrib delimiter
                    if ($quote == 0) {    // found in a string ?
                        $attrib = 1;
                        if ($tmp != '') {
                            $result .= ' ' . $tmp;
                        }
                        $tmp = '';
                    } else {
                        $tmp .= '=';
                    }
                    break;

                // handle wrong single quotes
                case '\'':
                    if ($quote == 0) {
                        $quote = 1;
                    } else {
                        $quote = 0;
                        if (($attrib > 0) && ($tmp != '')) {
                            $result .= '="' . $tmp . '"';
                        }
                        $tmp = '';
                        $attrib = -1;
                    }
                    break;
                    break;

                // replace spaces for now
                case ' ':
                    if ($attrib > 0) {
                        $tmp .= '|||';
                    }
                    break;

                default:
                    if ($attrib < 0) {
                        $attrib = 0;
                    }
                    $tmp .= $stringAttributes[$i];
                    break;
            }

            $i++;
        }

        // check, maybe old html attributes are in standalone like "nowrap"
        if (($quote == 0) && ($tmp != '')) {
            if ($attrib == 1) {
                // If it is the value of an atrib, add the '='
                $result .= '=';
            }
            $result .= '"' . $tmp . '"';
        }

        // attributes validation/fixes done
        // now, filter all
        // - bring source to array
        // - compare with whitelist
        $attributes = array();

        $listAttribs = explode(' ', $result);
        foreach ($listAttribs AS $partAttr) {
            // replace back the spaces
            $partAttr = str_replace('|||', ' ', $partAttr);

            $attrToCheck = explode('="', $partAttr);
            if ($attrToCheck && count($attrToCheck) == 2) {
                if (in_array($attrToCheck[0], $allowedAttributes)) {
                    if (substr($attrToCheck[1], -1) == '"') {
                        $attrToCheck[1] = substr($attrToCheck[1], 0, -1);
                    }
                    $attributes[$attrToCheck[0]] = $attrToCheck[1];
                }
            }
        }

        if ($attributes) {
            $result = self::attributesCreate($attributes);
        } else {
            $result = '';
        }

        unset($tmp, $attrToCheck, $listAttribs, $partAttr, $attributes, $attrib, $stringAttributes, $tag);

        return $result;
    }


    /**
     * Strip Xml/Html code and attributes from a given string
     *
     * If whitelist flag is set the html code will be parsed and filtered to
     * get the best result in return. if $against_whitelist flag is not given
     * all tags, attributes and comments will be striped out
     *
     * @param string $htmlcode The Html code to be striped
     * @param array $allowedTags Optional; List of tags as whitelist to
     * validate/check the code
     *
     * @return string Returns the new (xml)text
     */
    public static function strip( $xmlcode = '', array $allowedTags = array() )
    {
        $xmlcode = Php::stripslashes($xmlcode);

        if ($allowedTags)
        {
            // build whitlist tags for php's strip_tags()
            $tagWhitelist = '<' . implode('><', $allowedTags) . '>';
            $cleanedCode = strip_tags($htmlcode, $tagWhitelist);
            unset($tagWhitelist);

            $newXmlcode = self::filter($cleanedCode, $allowedTags);
        } else {
            $newXmlcode = strip_tags($htmlcode);
        }

        return $newXmlcode;
    }


    /**
     * Filter a xml/html code snippet.
     * (something between the body tags)
     * A whitelist will drop all unknown/ unwanted parts.
     *
     * @param string $htmlcode the html code to parse and filter
     * @return string The filtered html code
     */
    public static function filter( $htmlcode, array $allowedTags = array() )
    {
        $htmlTags = self::$_htmlTagsDefaultAllowed;

        $str = Php::stripslashes($htmlcode);

        // Delete all spaces from html tags eg: < h1 >, <h1 > to be <h1>
        // except attibutes which can have also more whitespaces eg: <h1   class=...>
        // Delete spaces or invalid tags
        $htmlcode = preg_replace("/<\s*(\w+)\s*>/im", '<\1>', $htmlcode);

        $tmp = '';
        while (preg_match("|<(/?[[:alnum:]]*)[[:space:]]*([^>]*)>|im", $htmlcode, $reg)) {
            $i = strpos($htmlcode, $reg[0]);
            $l = strlen($reg[0]);

            $_tag = '';
            if (isset($reg[1][0]) && $reg[1][0] == '/') {
                $tag = $_tag = strtolower(substr($reg[1], 1));
            } else {
                $tag = $_tag = strtolower($reg[1]);
            }

            //if (isset($htmlTags[$tag])) {
            if (in_array($tag, $allowedTags))
            {
                if ($reg[1][0] == '/') {
                    $tag = '</' . $tag . '>';
                } elseif (empty($reg[2])) {
                    $tag = self::_formatTag($tag, false);
                } else {
                    // double quote fix function: $attrb_list = $reg[2];
                    $attrbList = self::attributesFilter($_tag, $reg[2], $allowedTags);

                    // fix & to &amp;
                    //old, wrong!:
                    //$attrbList = preg_replace('/&(?!(#[0-9]|amp)+;)/s', '&amp;', $attrbList);
                    // ok, but attribute values should not be converted!?
                    //$attrbList = preg_replace('/&(?!(amp)+;)/s', '&amp;', $attrbList);

                    $tag = self::_formatTag($tag, $attrbList);
                } // end attributes in tag allowed
            } else {
                $tag = ''; // tag not allowed or no tag: eg. comments
            }
            $tmp .= substr($htmlcode, 0, $i) . $tag;
            $htmlcode = substr($htmlcode, $i + $l);
        }

        $htmlcode = $tmp . $htmlcode;
        unset($tag, $attrbList, $htmlTags, $tmp, $tag, $_tag);
        return $htmlcode;
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
        if ($stringAttributes) {
            $tag = '<' . $tag . ' ' . $stringAttributes . '>';
        } else {
            $tag = '<' . $tag . '>';
        }

        return $tag;
    }

}