<?php

/*{{{*/
/**
 * Mumsys_Variable_Item_Extended_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 * @verion 1.0.0
 * Created: 2006 based on Mumsys_Field_EXception, renew 2016
 */
/*}}}*/

/**
 * Extended variable item interface.
 *
 * Extended item implementation as variable item interface for general web
 * related tasks like create/edit/save variables.
 * Each variable should be an object with a standard set of methodes which are
 * needed for these tasks.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 */
interface Mumsys_Variable_Item_Extended_Interface
    extends Mumsys_Variable_Item_Interface
{
    /**
     * Initialisation of the item object.
     *
     * @see $_properties
     *
     * @param array $properties List of key/value config parameters to be set. Config values MUST NOT be null!
     */
    public function __construct( array $properties );


    /**
     * Returns the item type.
     *
     * @return string Item type
     */
    public function getType();


    /**
     * Sets the item type.
     * If value exists and is the same than the current one null is returned.
     *
     * Types are php types and optional types like email, date or datetime from
     * mysql which can and will be handles as types in this class. For more
     * @see $_types for a complete list handles by this class.
     *
     * @param string $value Type to be set
     * @return void
     */
    public function setType( $value );


    /**
     * Returns the minimum item value length (number or string length).
     *
     * @return float|null Minimum length
     */
    public function getMinLength();


    /**
     * Sets the minimum item value length (number or string length).
     *
     * @param float $value Minimum item value length
     */
    public function setMinLength( $value );


    /**
     * Returns the maximum item value length (number or string length).
     *
     * @return float|null Maximum item value length
     */
    public function getMaxLength();


    /**
     * Sets the maximum item value length (number or string length).
     *
     * @param float $value Maximum item value length
     */
    public function setMaxLength( $value );

}