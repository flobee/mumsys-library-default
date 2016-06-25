<?php

/*{{{*/
/**
 * Mumsys_Variable_Item_Extended
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 * Created: 2006 based on Mumsys_Field_EXception, renew 2016
 */
/*}}}*/

/**
 * Extended item implementation as variable item interface for general web
 * related tasks like create/edit/save variables.
 * Each variable should be an object with a standard set of methodes which are
 * needed for these tasks.
 * This class keeps additional properties to also validate item values by a validation manager.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 */
class Mumsys_Variable_Item_Extended
    extends Mumsys_Variable_Item_Default
    implements Mumsys_Variable_Item_Interface, Mumsys_Variable_Item_Extended_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.1';

    /**
     * List of key/value pair properties handled by this item as whitelist.
     * @var array
     */
    private $_properties = array(
        'type' => true, 'minlen' => true, 'maxlen' => true,
    );

    /**
     * PHP types and optional additional types for this item.
     * @var array
     */
    private $_types = array(
        'string', 'integer', 'float', 'double', 'boolean', 'array', 'object', 'date', 'datetime', 'email'
    );


    /**
     * Initialisation of the item object.
     *
     * @see $_properties
     *
     * @param array $properties List of key/value config parameters to be set. Config values MUST NOT be null!
     */
    public function __construct( array $properties )
    {
        parent::__construct($properties);

        foreach ( $this->_properties as $key => $value ) {
            if ( isset($properties[$key]) ) {
                $this->_input[$key] = $properties[$key];
            }
        }
    }


    /**
     * Returns the item type.
     *
     * @return string Item type
     */
    public function getType()
    {
        return (isset($this->_input['type'])) ? $this->_input['type'] : null;
    }


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
    public function setType( $value )
    {
        if ( $value == $this->getType() ) {
            return;
        }

        if ( in_array($value, $this->_types) ) {
            $this->_input['type'] = (string) $value;
        } else {
            $message = sprintf('Type "%1$s" not implemented/ exists', $value);
            throw new Mumsys_Variable_Item_Exception($message);
        }
    }


    /**
     * Returns the minimum item value length (number or string length).
     *
     * @return float|null Minimum length
     */
    public function getMinLength()
    {
        return (isset($this->_input['minlen'])) ? $this->_input['minlen'] : null;
    }


    /**
     * Sets the minimum item value length (number or string length).
     *
     * @param float $value Minimum item value length
     */
    public function setMinLength( $value )
    {
        $this->_input['minlen'] = (float) $value;
    }


    /**
     * Returns the maximum item value length (number or string length).
     *
     * @return float|null Maximum item value length
     */
    public function getMaxLength()
    {
        return (isset($this->_input['maxlen'])) ? $this->_input['maxlen'] : null;
    }


    /**
     * Sets the maximum item value length (number or string length).
     *
     * @param float $value Maximum item value length
     */
    public function setMaxLength( $value )
    {
        $this->_input['maxlen'] = (float) $value;
    }

}