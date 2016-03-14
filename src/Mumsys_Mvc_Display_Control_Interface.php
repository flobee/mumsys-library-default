<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Control_Interface
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
 * Display control interface
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
interface Mumsys_Mvc_Display_Control_Interface
{
    /**
     * Constructor is to be implemented at the display controller which will be
     * used. e.g. in : Mumsys_Mvc_Display_Control_Http_Default
     */
    abstract public function __construct( Mumsys_Context $context, array $options = array() );

    /**
     * Add content to the display/output buffer.
     *
     * @param string $content Content to add to the buffer
     */
    public function add( $content = '' );

    /**
     * Output given content and the current buffer and resets it.
     *
     * @param string $content Optional; Content to output after the buffer
     * contents
     */
    public function apply( $content = '' );

    /**
     * Print out the complete content.
     */
    public function show();

    /**
     * Fetch the content without any, maybe needed, headers and resets the buffer.
     * e.g.: this can be used to store the content to a file.
     *
     * @return string Returns the complete data of the requested page. e.g. The
     * hole html page. It depends on the display controller what kind of output
     * was set.
     */
    public function fetch();

}
