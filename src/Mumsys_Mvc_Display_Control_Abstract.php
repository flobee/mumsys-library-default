<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Control_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * @version     1.0.0
 * Created: 2016-01-30
 * @filesource
 */
/*}}}*/


/**
 * Abstract display control methodes to be used in general.
 *
 * Last instance to output data to the frontend.
 * Mumsys_Mvc_Display_Control_Abstract is the base for all views. Basicly it
 * collects, applys, shows or returns given content.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
abstract class Mumsys_Mvc_Display_Control_Abstract extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.0';


    /**
     * Buffer of content to output or return.
     * @var string|mixed
     */
    private $_buffer = '';


    /**
     * Constructor is to be implemented at the display controller which will be
     * used. e.g. in : Mumsys_Mvc_Display_Control_Http_Html
     */
    abstract public function __construct( Mumsys_Context $context, array $options = array() );


    /**
     * Add content to the display/output buffer.
     *
     * @param string $content Content to add to the buffer
     */
    public function add( $content = '' )
    {
        $this->_buffer .= $content;
    }


    /**
     * Output given content and the current buffer and resets it.
     *
     * @param string $content Optional; Content to output after the buffer
     * contents
     */
    public function apply( $content = '' )
    {
        echo $this->_buffer . $content;
        $this->_buffer = '';
    }


    /**
     * Print out the complete content.
     */
    public function show()
    {
        echo $this->fetch();
    }


    /**
     * Fetch the content without any, maybe needed, headers and resets the buffer.
     * e.g.: this can be used to store the content to a file.
     *
     * @return string Returns the complete data of the requested page. e.g. The
     * hole html page. It depends on the display controller what kind of output
     * was set.
     */
    public function fetch()
    {
        $buffer = $this->_buffer;
        $this->_buffer = '';
        return $buffer;
    }

}
