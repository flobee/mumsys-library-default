<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
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
 * @version     1.0.0
 * Created: 2016-02-04
 * @filesource
 */
/*}}}*/


/**
 * Default abstract class for stdout output e.g. text for the shell output
 */
abstract class Mumsys_Mvc_Templates_Text_Abstract
    extends Mumsys_Mvc_Display_Control_Stdout_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * Page title for the output
     * @var string
     */
    protected $_pagetitle = '';


    /**
     * Sets the output page title.
     *
     * @param string $title Title to be set
     */
    public function setPageTitle($title='')
    {
        $this->_pagetitle = (string)$title;
    }


}
