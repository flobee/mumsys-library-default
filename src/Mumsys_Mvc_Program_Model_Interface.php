<?php

/*{{{*/
/**
 * Mumsys_Mvc_Program_Model_Interface
 * for MUMSYS (Multi User Management System)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 * @version     1.0.0
 * Created: 2016-03-16
 * @filesource
 */
/*}}}*/

/**
 * Mumsys program model abstract contains methodes to be used in the program
 * model.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mvc
 */
interface Mumsys_Mvc_Program_Model_Interface
{
    /**
     * Initializes the program model object.
     *
     * @param Mumsys_Context $context Context item
     */
    public function __construct( Mumsys_Context $context );

}
