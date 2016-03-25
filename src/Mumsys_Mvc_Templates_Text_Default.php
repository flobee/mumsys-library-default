<?php

/*{{{*/
/**
 * Mumsys_Mvc_Templates_Text_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * Created: 2016-02-04
 * @filesource
 */
/*}}}*/


/**
 * Default text templates for the view.
 * These methodes are basicly view helpers for the text output
 *
 * @category Mumsys
 * @package Mumsys_Library
 * @subpackage Mumsys_Mvc
 */
class Mumsys_Mvc_Templates_Text_Default
    extends Mumsys_Mvc_Templates_Text_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Returns a "table" by given records of data.
     *
     * @param array $rowsAndCols List of records in list of key/value pairs to
     * create the 2D structure
     */
    public function getCreateTable($rowsAndCols)
    {
        $result = ' table for text by given array not implemented yet';
        return $result;
    }

    /**
     * Sets/Adds a "table" (in time) to the buffer of the output
     *
     * @param array $rowsAndCols List of records in list of key/value pairs to
     * create the 2D structure
     */
    public function addCreateTable(array $rowsAndCols)
    {
        $this->add($this->getCreateTable($rowsAndCols));
    }


    /**
     * Adds a message box to the output buffer.
     *
     * @param string $title Title of the message
     * @param string $content The message
     */
    public function addTitleBlock( $title = '', $content = '' )
    {
        $this->add($this->getTitleBlock($title, $content));
    }


    /**
     * Returns a message box for the output.
     *
     * @param string $title Title of the message
     * @param string $content The message
     *
     * @return string The formatted message box string
     */
    public function getTitleBlock( $title = '', $content = '' )
    {
        $data = $title . _NL . _NL;
        $data = $content . _NL . _NL . _NL;

        return $data;
    }


    /**
     * html header and footer in return to display. called in display controller.
     *
     * @param $extras arrray
     */
    public function getSiteHeader($extras='')
    {
        $outp = 'Site Head not implemented yet. But a page title, if set is here "'.$this->_pagetitle.'"';
        // if ( >get('charset') ) {}

        return $outp;
    }


    /**
     * Returns the footer contents.
     *
     * @todo to be implemented for text mode
     *
     * @return string All content to finish the output at the end of an output
     */
    public function getSiteFooter()
    {
        $retour = _NL . _NL . '* MUMSYS POWER *' . _NL;

        return $retour;
    }


    /**
     * Send no access/ no permission information to output.
     *
     * @throws Mumsys_Display_Exception Throws exception for controlled script
     * end or to have detailed informations (eg: debug mode)
     */
    public function noAccess()
    {
        $l = $this->_context->getTranslation();
        $noAccess = $l->t('_CMS_NOACCESS');
        $this->title( $noAccess, $l->_t(_CMS_NOPERMISSION_INFO));
        $this->show();
        throw new Mumsys_Mvc_Display_Exception($noAccess, Mumsys_Mvc_Display_Exception::ERROR_HTTP401);
    }

    /**
     * Error reporting helper to add error messages.
     *
     * @param string|array $msg Message/s to output
     * @param string $line Line of the error
     * @param string $file File of the error
     * @param string $func Name of the function of the error
     * @param string $class Name of the class of the error
     */
    public function mkError($msg, $line=null, $file=null, $func=false, $class=false)
    {
        $l = $this->_context->getTranslation();

        if ( is_array($msg) ) {
            $retour = '';
            foreach ( $msg AS $key => &$val ) {
                $key++;
                $retour .= '  *' . $l->_t('_CMS_ERROR') . ': ' . $key . ' - ' . $val . '' . _NL;
            }
            $retour .= _NL . _NL;
        } else {
            $retour = '* ' . $msg . '' . _NL;
        }
        if ( $line || $file || $func || $class ) {
            $retour .= '' . _NL;
        }
        if ( $line ) {
            $retour .= ' || Line: ' . $line;
        }
        if ( $file ) {
            $retour .= ' || File: ' . basename($file);
        }
        if ( $func ) {
            $retour .= ' || Function/Methode: ' . ($func);
        }
        if ( $class ) {
            $retour .= ' || Class: ' . ($class);
        }
        $retour .= _NL;

        $this->setTitleBlock($l->_t('_CMS_ERROR'), $retour);
    }

}
