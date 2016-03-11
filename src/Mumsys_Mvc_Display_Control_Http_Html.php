<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Control
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
 * Mumsys_Mvc_Display_Control_Http_Html is the base for the view for html output.
 * the frontend controller
 * Its the last instance befor output data.
 * The templates (Mumsys_Mvc_Templates_Html_*) adding methodes to this controller
 * to have more helper methods to generate html.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
class Mumsys_Mvc_Display_Control_Http_Html
    extends Mumsys_Mvc_Display_Control_Http_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';
    /**
     * Mumsys_Context
     * @var Mumsys_Context
     */
    protected $_context;

    /**
     * Name of the display type. E.g: default|mini|smarty. For more see
     * Mumsys_Display_Factory::load()
     * @var string
     */
    protected $_display = 'default';

    /**
     * Name of the template/ theme to be used
     * Its the path for the templates classes. Default: "default"
     * @var string
     */
    protected $_template;

    protected $_pagetitle = '';

    protected $mod;
    protected $submod;


    protected $_output = '';

    protected $_htmlhead = array( ); // extra html head informations


    /**
     * All config variables from Mumsys_Config object
     * @var array
     */
    protected $_config;




    protected $cnt = 0; // counter for blockparts


    /**
     * init
     * @param array $x array with options
     * [mod] optional "mod" to call, if empty the global $mod will be taken
     * [submod] optional html header title
     * [pagetitle] optional html header title
     * [display] optional, not implemented in here! to toggle the output
     * [jsfw] optional javascript frameworks to include
     * [theme] Theme to be used.
     */
    public function __construct(Mumsys_Context $context, array $opts=array( ) )
    {
        $this->_context = $context;

        $this->_oCfg = $context->getConfig();
        $this->_config = $this->_oCfg->getAll();

        if ( !isset($opts['theme']) ) {
            $this->_template = 'default';
        } else {
            $this->_template = (string) $opts['theme'];
        }

        if ( !isset($opts['mod']) ) {
            $this->mod = $this->_oCfg->get('mod');
        }

        if ( !isset($opts['submod']) ) {
            $this->submod = $this->_oCfg->get('submod');
        }

        if ( empty($opts['display']) ) {
            $opts['display'] = $this->_display;
        } else {
            $this->_display = $opts['display'];
        }

        if ( !empty($opts['jsfw']) ) {
            $this->_oCfg->set('jsfw', $opts['jsfw']);
        }

        if ( empty($opts['pagetitle']) ) {
            $modinfo = $this->_oPerms->get('moduleinfo');
            if ( isset($modinfo[$this->mod]['myname']) ) {
                $this->_pagetitle = $modinfo[$this->mod]['myname'];
                if ( isset($modinfo[$this->mod]['submodulename'][$this->submod]['myname']) ) {
                    $this->_pagetitle .= ' : ' . $modinfo[$this->mod]['submodulename'][$this->submod]['myname'];
                }
            } else {
                if ( empty($this->_pagetitle) ) {
                    $this->_pagetitle = ucwords($GLOBALS['mod']);
                }
            }
            unset($modinfo);
        } else {
            $this->_pagetitle = ' ' . $opts['pagetitle'];
        }
    }


    /**
     * Print out the complete data of headers and content.
     */
    public function show()
    {
        if ( empty($this->_headers) ) {
            $this->setheader('Content-Type: text/html; charset=' . $this->_oCfg->get('charset'));
        }
        $this->applyHeaders();
        parent::show();
    }


    /**
     * Fetch the content without any, maybe needed, headers.
     * e.g.: this can be used to store the content to a file.
     *
     * @return string Returns the complete data of the requested page. e.g. The
     * hole html page. It depends on the display controller what kind of output
     * was set.
     */
    public function fetch()
    {
        $this->_output .= $this->getSiteHeader($this->_pagetitle);
        $this->_output = parent::fetch();
        $this->_output .= $this->getSiteFooter();

        return $this->_output;
    }

}
