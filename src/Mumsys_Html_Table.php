<?php

/* {{{ */
/**
 * Mumsys_Html_Table
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
 * -----------------------------------------------------------------------
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @see lib/mumsys2/class.Html.php
 * @filesource class.HtmlTable.phps
 * @version 0.4 - Created on 2006-12-01
 * $Id: class.HtmlTable.php 2892 2013-12-03 13:11:52Z flobee $
 * -----------------------------------------------------------------------
 */
/* }}} */



/**
 * CHANGELOG:
 *
 * 2007-08-18
 * 		Added global row and col attributes:
 * 		$table->setRowAttributes('_', array('style'=>'color:red;', 'nowrap'=>'nowrap'));
 * 		$table->setColAttributes('_', 4, array('align'=>'right'));
 *
 * ToDo:
 * Colspan, Rowspan
 */
// ================================================================== //
// CLASS HtmlTable
// Fast solution instead of using pear, for maximum features
// use pear libary (pear.php.net)!
//
// USAGE:
//
// $tableAttributes = array('border'=>0,'cellpadding'=>2);
// $objTable = new Mumsys_Html_Table($tableAttributes);
// $headlines = array('USERNAME', 'WEEK','YEAR','DATE', 'OPTIONS' );
// $objTable->setHeadlines( $headlines );
// $objTable->setAltRowColor( array('#000000','#FFFFFF') );
// if the value in this col has canged than the row befor, then the color will change
// $objTable->setAltRowColor($arrayColors, $colKeyChange=0);
// $objTable->setRowAttributes($row, $content=array())
// $objTable->setColAttributes($row,$col, $content=array('width'=>'100'))
// $tabledata = array(0=>array('col1'=>'val1','col2'=>'val3','col3'=>'val3...'));
// $objTable->setContent($tabledata);
//
// echo $objTable->toHTML();
//
// $html =  $objTable->getHtml();
// $html = str_replace('>','&gt;', $html);
// $html = str_replace('<','&lt;', $html);
// echo nl2br($html);
// ================================================================== //


/**
 * Class for generating a Html-Table
 *
 * @category mumsys_library
 * @package mumsys_library
 */
class Mumsys_Html_Table
    extends Mumsys_Xml_Abstract
{
    /**
     * 	Array containing the table attributes used in the constructor
     * 	@var array
     */
    private $_tblProps = array();

    /**
     * 	Array containing the content of headlines
     * 	@var array
     */
    private $_headlines = array();

    /**
     * 	Array containing your data
     * 	@var array
     */
    private $_content = array();

    /**
     * 	Array containing number of Cols
     * 	@var integer
     */
    private $_numCols = 0;

    /**
     * 	Array containing number of Rows
     * 	@var integer
     */
    private $_numRows = 0;

    /**
     * 	String containing the html code of your table
     * 	@var string
     */
    private $_html = '';

    /**
     * 	Array containing alternative row color for listing presentation
     * 	@var array
     */
    private $_altRowColor = array(); // [0] = '#CCCCCC' [1] = '#EFEFEF'
    private $_altRowColorKeyChange;
    private $_colorChangeVal = 0;

    /**
     * 	Array containing the table structure
     * 	@var array
     */
    private $_structure = array();  // $this->_structure[$row][$col]['attr']

    /**
     * 	Value to insert into empty cells
     * 	@var string
     */
    private $_autoFill = '&nbsp;';

    /**
     * 	Array containing error messages
     * 	@var array
     */
    private $_errors = array();


    /**
     * 	Initialize the table object.
     *
     * 	@param array $attributes List of key/value pairs for the table tag attributes
     */
    public function __construct( array $attributes = array() )
    {
        if (!defined('_NL')) {
            define('_NL', "\n");
        }

        $this->_tblProps = $attributes;
        $this->_headlines['values'] = false;
        $this->_headlines['attr'] = array();
    }


    /**
     * Set attributes for the <table> tag.
     *
     * @param array $attributes List of key/value pairs for the table tag attributes
     */
    public function setTableProps( array $attributes )
    {
        $this->_tblProps = $attributes;
    }


    /**
     * Set headline values for the table
     *
     * @param array $attr List of values or if set true to use the key of the
     * input data which will expect key/value pairs and the keys will be used
     */
    public function setHeadlines( $values = true )
    {
        $this->_headlines['values'] = $values;
    }


    /**
     * Set attributes for each column at the headlines. Plural, the th-row.
     *
     * @param array $attr List of key/value pais for the attributes to set
     */
    function setHeadlinesAttributes( $attr = array() )
    {
        if (!isset($this->_headlines['attr']['attr']) || !is_array($this->_headlines['attr']['attr'])) {
            $this->_headlines['attr']['attr'] = array();
        }
        $this->_headlines['attr']['attr'] = $attr;
    }


    /**
     * Returns headlines attributes (thead section)
     */
    public function getHeadlinesAttributes()
    {
        $attrib = '';
        if (!empty($this->_headlines['attr']['attr'])) {
            $attrib = parent::attributesCreate($this->_headlines['attr']['attr']);
        }

        return $attrib;
    }


    /**
     * Sets headline attributes, singular, for a specified column
     */
    public function setHeadlinesAttribute( $col = 0, $attr = array() )
    {
        if (!isset($this->_headlines['attr'][$col]) || !is_array($this->_headlines['attr'][$col])) {
            $this->_headlines['attr'][$col] = array();
        }

        $this->_headlines['attr'][$col] = $attr;
    }


    /**
     * Returns headlines attributes for the specified column.
     *
     * @param integer $col Column to get the attributes for
     * @return string Attributes for the specified column
     */
    public function getHeadlinesAttribute( $col = 0 )
    {
        $result = '';
        if (!empty($this->_headlines['attr'][$col])) {
            $result = parent::attributesCreate($this->_headlines['attr'][$col]);
        }

        return $result;
    }


    /**
     * Adds a new column as headline.
     *
     * @param string $value Name of the new column
     */
    public function addHeadline( $value )
    {
        $this->_headlines['values'][] = $value;
    }


    /**
     * Sets the auto fill character for empty fields. E.g: n/a or [space]
     *
     * @param string $string String to fill if a value is empty eg.: &nbsp;
     */
    public function setAutoFill( $string )
    {
        $this->_autoFill = $string;
    }


    /**
     * Set the content to fill the table.
     *
     * @param array $data List of records
     */
    public function setContent( array $data )
    {
        $this->_content = $data;
    }


    /**
     * Return the number of columns based on the number of elements of the first row.
     *
     * @return integer Number of columns
     */
    public function getNumCols()
    {
        $this->_numCols = count(current($this->_content));
    }


    /**
     * Return the number of rows of data. without the headlines.
     *
     * @return integer Number of rows
     */
    public function getNumRows()
    {
        $this->_numRows = count($this->_content);
    }


    /**
     * Set alternativ row colors.
     *
     * If the second argument is given the color for each row will only change
     * if the value of the given key is changed.
     *
     * @param array $arr List of colors to set e.g.: array('#FFFFF','#333333'..
     * @param integer $colKey The column when a color-change should take effect
     */
    public function setAltRowColor( $arr, $colKey = false )
    {
        $this->_altRowColor = $arr;

        if ($colKey !== false) { // col number
            $this->_altRowColorKeyChange = $colKey;
        }
    }


    /**
     * Returns the alternativ row color if possible/ was set befor.
     *
     * @param integer $num Row number the color should be used for (NOT IMPLEMENTED)
     * @param string $val Value to detect a color change
     *
     * @return string|false New color sting or false for no change
     */
    public function getAltRowColor( $num, $val = false )
    {
        if ($this->_altRowColor) {
            // echo "$num,$val,".$this->_colorChangeVal.'::';
            if (isset($this->_altRowColorKeyChange) && $this->_colorChangeVal == $val) {
                return current($this->_altRowColor);
            } else {
                // toggle color
                return ($c = next($this->_altRowColor)) ? $c : reset($this->_altRowColor);
            }
            // new 2006-05-01
            $color = next($this->_altRowColor);
            if ($color) {
                return $color;
            } else {
                $color = reset($this->_altRowColor);
            }
            return $color;
        }

        return false;
    }


    /**
     * Get the html-head of the table including <thead> tags.
     *
     * @return string the html head or empty string if no headlines are given
     */
    public function getHeadlines()
    {
        $r = '';
        if ($this->_headlines['values']) { // true OR array with content!
            $r .= '<thead>' . _NL . '<tr ';
            $r .= $this->getHeadlinesAttributes();
            $r .= '>' . _NL;
            $tmp = false;
            // for ( $i = 0; $i < $this->_numCols; $i++ ) {

            if ($this->_headlines['values'] === true) {
                $values = array_keys($this->_content[0]);
            } else {
                $values = $this->_headlines['values'];
            }

            foreach ($values as $i => $value) {
                if ($this->_headlines['attr']) {
                    $attr = $this->getHeadlinesAttribute($i); // sing.
                } else {
                    $attr = '';
                }

                if ($this->_headlines['values'] !== true) {
                    // creating by content
                    if (!$this->_headlines['values'][$i] || is_int($this->_headlines['values'][$i])) {
                        $r .= '   <th' . $attr . '>' . $this->_autoFill . '</th>' . _NL;
                    } else {
                        $r .= '   <th' . $attr . '>' . $this->_headlines['values'][$i] . '</th>' . _NL;
                    }
                } else {
                    // creating by array indexes ( if assoc )
                    if (!$tmp) {
                        $tmp = array_keys($this->_content[0]);
                    }
                    if (is_int($tmp[$i])) {
                        $r .= '   <th' . $attr . '>' . $this->_autoFill . '</th>' . _NL;
                    } else {
                        $r .= '   <th' . $attr . '>' . $tmp[$i] . '</th>' . _NL;
                    }
                }
            }
            $r .= '</tr>' . _NL . '</thead>' . _NL;
        }
        return $r;
    }


    /**
     * Set attrtibutes for a specified row or column.
     *
     * @param integer|string $row Number of the row where attributes should be placed or "_" for all rows
     * @param integer $col Number of the column where attributes should be placed
     * @param array $content the content for this cell as 'key'=>'value'
     */
    public function setColAttributes( $row, $col, $content = array() )
    {
        if (!isset($this->_structure[$row])) {
            $this->_structure[$row] = array();
        }
        if (!isset($this->_structure[$row][$col]) || !is_array($this->_structure[$row][$col])) {
            $this->_structure[$row][$col] = array();
        }

        // global attributes for each col
        if ($row == '_') {
            $this->_structure['_'][$col]['attr'] = $content;
        } else {
            $this->_structure[$row][$col] = array('attr' => $content);
        }
    }


    /**
     * Get the attrutes for a specified row and col.
     *
     * @param integer $row Number of the selected row
     * @param integer $col Number of the selected column
     *
     * @return string attrubutes for the html tag eg.: key="value"
     */
    public function getColAttributes( $row, $col )
    {
        $result = '';
        if (isset($this->_structure[$row][$col]['attr'])) {
            $result .= parent::attributesCreate($this->_structure[$row][$col]['attr']);
        }

        // global attributes for each col
        if (isset($this->_structure['_'][$col]['attr'])) {
            $result .= parent::attributesCreate($this->_structure['_'][$col]['attr']);
        }

        return $result;
    }


    /**
     * Set attributes for a specified row.
     *
     * @param integer|string $row Number of the row where attributes should be placed or "_" for all rows
     * @param array $content List of key/value pairs for the attributes
     */
    public function setRowAttributes( $row, $content = array() )
    {
        if (!isset($this->_structure[$row]) || !is_array($this->_structure[$row])) {
            $this->_structure[$row] = array();
        }

        // global attributes
        if ($row == '_') {
            $this->_structure['_']['attr'] = $content;
        } else {
            $this->_structure[$row]['attr'] = $content;
        }
    }


    /**
     * Return the attributes defined for this row of for all rows.
     *
     * @param integer $row Row number
     * @return string The html/ xml attributes string
     */
    public function getRowAttributes( $row )
    {
        $attributes = false;
        if (isset($this->_structure[$row]['attr'])) {
            $attributes .= parent::attributesCreate($this->_structure[$row]['attr']);
        }

        // global attributes
        if (isset($this->_structure['_']['attr'])) {
            $attributes .= parent::attributesCreate($this->_structure['_']['attr']);
        }
        return $attributes;
    }


    /**
     * Set value replacements for a specified field.
     *
     * @todo test the method for detailed docs for parameters
     *
     * @param string $str Value to set
     * @param integer $row Number of the row, -1 for all rows
     * @param integer $col Number of the column, -1 for all columns
     */
    public function setColContents( $str, $row = true, $col = true )
    {
        if ($row === true) {
            $row = -1;
        }
        if ($col === true) {
            $col = -1;
        }
        $this->_structure[$row][$col]['replacement'] = $str;
    }


    /**
     * ToDo: docs; replacement?? what is this for? value substitution... %s with str
     * @todo test the method for detailed docs for parameters
     */
    public function getColContents( $row, $col, $str )
    {
        if (isset($this->_structure[$row][$col]['replacement']) || isset($this->_structure[-1][$col]['replacement']) || isset($this->_structure[$row][-1]['replacement'])) {

            if (isset($this->_structure[-1][$col]['replacement'])) {
                $row = -1;
            }
            if (isset($this->_structure[$row][-1]['replacement'])) {
                $col = -1;
            }
            $r = str_replace('%s', $str, $this->_structure[$row][$col]['replacement']);
        } else {
            if (!isset($this->_structure[-1][-1]['replacement'])) {
                $r = $str;
            } else {
                //$ret = sprintf($this->_structure[-1][-1]['replacement'], $str);
                $r = str_replace('%s', $str, $this->_structure[-1][-1]['replacement']);
            }
        }
        return $r;
    }


    /**
     * 	Retuns the html code of the generated table.
     * 	we parse/build the table only once
     * 	if there is no html content, it will be generated only once for each object
     * 	@version    2004/11/06
     *   @access     public
     *   @author     Florian Blasel
     *   @param
     */
    function getHtml()
    {
        if (empty($this->_html)) {
            $this->toHtml();
        }
        return $this->_html;
    }


    /**
     * Returns the source aa html representation.
     * NOT the html code to use!
     *
     * @return string html enterties encoded string of the generated html code of the table
     */
    public function getSource()
    {
        if (empty($this->_html)) {
            $this->toHtml();
        }

        return htmlentities($this->_html);
    }


    /**
     * Generates the HTML output.
     * You may need to generate the code twice. This is the method
     *
     * @return string HTML ode of the table
     */
    public function toHtml()
    {
        if (!$this->_content) {
            $this->_errors[] = 'No content found';
            return false;
        }

        $tmp = false;

        if (!$this->_numCols) {
            $this->getNumCols();
        }

        if (!$this->_numRows) {
            $this->getNumRows();
        }

        $theHtml = '<table';

        if ($this->_tblProps) {
            $theHtml .= ' ' . parent::attributesCreate($this->_tblProps);
        }
        $theHtml .= '>' . _NL;

        $theHtml .= $this->getHeadlines();

        if ($this->_numRows) {
            $theHtml .= '<tbody>' . _NL;
        }

        //for( $row=0; $row < $this->_numRows; $row++) {
        foreach ($this->_content as $row => $value) {
            // col content
            $tmp = array_values($this->_content[$row]);

            // row begin
            $theHtml .= '<tr';
            if ($this->_altRowColor) {
                if (isset($this->_altRowColorKeyChange)) {
                    $colorKey = $tmp[$this->_altRowColorKeyChange];
                } else {
                    $colorKey = null;
                }
                $theHtml .= ' bgcolor="' . $this->getAltRowColor($row, $colorKey) . '"';
                if (isset($this->_altRowColorKeyChange)) {
                    // memory
                    $this->_colorChangeVal = $tmp[$this->_altRowColorKeyChange];
                }
            }
            $theHtml .= $this->getRowAttributes($row);
            $theHtml .= '>' . _NL;

            // col begin
            for ($col = 0; $col < $this->_numCols; $col++) {
                $attr = $this->getColAttributes($row, $col);

                $theHtml .= '   <td';
                // place attributes
                if ($attr) {
                    $theHtml .= $attr;
                }
                $theHtml .= '>';

                // content
                if (isset($tmp[$col])) {
                    $theHtml .= $this->getColContents($row, $col, $tmp[$col]);
                } else {
                    $theHtml .= $this->_autoFill;
                }

                $theHtml .= '</td>' . _NL;
            }
            $theHtml .= '</tr>' . _NL;
        }
        if ($this->_numRows) {
            $theHtml .= '</tbody>' . _NL;
        }
        $theHtml .= '</table>' . _NL;

        $this->_html = & $theHtml;

        return $theHtml;
    }


    /**
     * Returns the list of errors.
     *
     * @return array List of errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }

}