<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Control_Text
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
 * Created: 2006-12-01
 * @filesource
 */
/*}}}*/


/**
 * The default / base for the view/frontend controller for Text/stdout output
 * Its the last instance befor output some data.
 * The templates (Mumsys_Mvc_Templates_Text_*) adding methodes to this
 * controller to have more helper methods to generate Text.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
class Mumsys_Mvc_Display_Control_Stdout
    extends Mumsys_Mvc_Display_Control_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Mumsys_Context
     * @var Mumsys_Context
     */
    private $_context;

    /**
     * Name of the template/ theme to be used
     * Its the path for the templates. Default: "default"
     * @var string
     */
    protected $_template;

    /**
     * Initialize the display controller text version.
     *
     * @param Mumsys_Context $context Context oject
     * @param array $options array with options
     * [program] optional "program" to call, if empty the default program will be used
     * [controller] optional controller to use
     * [pagetitle] optional html header title
     */
    public function __construct( Mumsys_Context $context, array $options = array() )
    {
        $this->_context = $context;
        $this->_template = 'default';

        /**
         * @todo implement permissions pagetitle by given program infomations like:
         * if ( empty($opts['pagetitle']) ) {
         * $this->_pagetitle .= ' : ' . $modinfo[mod]['submodules'][submod]['name'];
         */
    }

}
