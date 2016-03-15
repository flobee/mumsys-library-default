<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Mvc_Display_Control_Stdout_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * @version     1.0.0
 * Created: 2015-12-01
 * @filesource
 */
/*}}}*/


/**
 * abstarct Text/stdout output class
 * The templates (Mumsys_Mvc_Templates_*) adding methodes to this
 * controller to have more helper methods to generate Text/Stdout stuff.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
abstract class Mumsys_Mvc_Display_Control_Stdout_Abstract
    extends Mumsys_Mvc_Display_Control_Abstract
    implements Mumsys_Mvc_Display_Control_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

}
