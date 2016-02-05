<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Control_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
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
 * Adds methodes to be used in last instance to output data to the frontend.
 * Eg.: content buffer or sending headers.
 * Mumsys_Mvc_Display_Control_Abstract is the base for all views.
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
    const VERSION = '1.0.0';


    /**
     * Buffer of content to output or return.
     * @var string|mixed
     */
    private $_buffer = '';

    /**
     * Last instance to the output.
     * @var string
     */
    private $_output = '';

    /**
     * header to be set if needed for the output
     * @var string
     */
    private $_header = array(); // array to set php header()


    /**
     * Constructora are to be implemented in main display controller.
     * e.g.: Mumsys_Mvc_Display_Control_Html
     */
    abstract public function __construct( Mumsys_Context $context, array $options = array() );


    /**
     * Adds header to be send on output.
     * @param string $s content of a Html header line
     */
    public function addHeader( $header = '' )
    {
        $this->_header[] = $header;
    }


    /**
     * Returns headers.
     *
     * @return array List of header
     */
    public function getHeader()
    {
        return $this->_header;
    }


    /**
     * Output all headers which were set.
     */
    public function applyHeaders()
    {
        while (list(, $header) = each($this->_header)) {
            header($header);
        }
    }


    /**
     * Sends given header to output directly.
     *
     * @param string $header content of the header line e.g:
     * "Content-Type: text/html" or "Location: index.php/a/b/c"
     *
     * @throws Mumsys_Mvc_Display_Exception Throws exception if string is empty
     * or not a string
     */
    public function sendHeader( $header = '' )
    {
        if (empty($header) || !is_string($header)) {
            $message = _('Can not send header.');
            $code = Mumsys_Mvc_Display_Exception::ERROR_HTTP500;
            throw new Mumsys_Mvc_Display_Exception($message, $code);
        } else {
            header($header);
        }
    }


    /**
     * Add content to the display/output buffer.
     *
     * @param string $c content to buffer
     */
    public function add( $content )
    {
        $this->_buffer .= $content;
    }


    /**
     * Output the current buffer and resets it.
     *
     * @param string $c content to buffer
     */
    public function apply( $content )
    {
        echo $this->_buffer;
        $this->_buffer = '';
    }


    /**
     * Print out the complete data of headers and content.
     */
    public function show()
    {
        if (empty($this->_header)) {
            // Set header by default to be text/html if nothing was set
            $this->addHeader(
                'Content-Type: text/html; charset=' . $this->_context->getConfig()->get('charset')
            );
        }
        $this->applyHeaders();
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
        if ($this->_buffer) {
            $this->_output .= $this->_buffer;
        }

        $this->_buffer = '';
        return $this->_output;
    }

}
