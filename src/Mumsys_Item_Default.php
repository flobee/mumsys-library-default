<?php

/*{{{*/
/**
 * Mumsys_Item_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Item
 * @version     1.1.1
 * Created: 2006 based on Mumsys_Field_EXception, renew 2016
 */
/*}}}*/

/**
 * Generic exception class
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Item
 */
class Mumsys_Item_Default
    extends Mumsys_Abstract
{

    /**
     * Version ID information
     */
    const VERSION = '1.0.0';

    /**
     * List of initial incoming properties to be set/ checked on construction or in later use.
     * @var array
     */
    private $_input = array();

    /**
     * List of key/value pair properties handled by this item as whitelist.
     * @var array
     */
    private $_properties = array('value' => true, 'key' => true, 'type' => true);
    /**
     * PHP types and optional additional types to handle by this item.
     * @var array
     */
    private $_types = array('string', 'char', 'varchar', 'text', 'array', 'date', 'datetime', 'timestamp', 'email');


    /**
     * Initialisation of the item object.
     *
     * @see $_properties
     *
     * @param array $properties List of config parameters to be set.
     * @param array $values List of key/value pairs e.g. request parameters
     */
    public function __construct( array $properties )
    {
        foreach($properties as $key => $value) {
            if (isset($this->_properties[$key])) {
                $this->_input[$key] = $value;
            }
        }
    }

    /**
     * Returns the item key/identifier name.
     * Note: From a liust of key/value pairs: this is the key.
     *
     * @param mixed $default Default (null) return vale if key was not set
     * @return string Item key/identifier
     */
    public function getKey($default=null)
    {
        return (isset($this->_input['key']) ? (string)$this->_input['key'] : $default);
    }

    /**
     * Sets the item key name/ identifier.
     * If value exists and is the same than the current one null is returned.
     *
     * @param string $value Item key/itenifier
     * @return void
     */
    public function setKey( $value )
    {
        if ($value === $this->getKey()) {
            return;
        }

        $this->_input['key'] = (string) $value;
    }

    /**
     * Returs the item value or null if not set;
     *
     * @return mixed|null Returns the item value or null
     */
    public function getValue()
    {
        return (isset($this->_input['value'])) ? $this->_input['value'] : null;
    }

    /**
     * Sets the item value.
     * If value exists and is the same than the current one null is returned.
     *
     * @param mixed $value Item value to be set
     * @return void
     */
    public function setValue( $value )
    {
        if ($value === $this->getValue()) {
            return;
        }

        $this->_input['value'] = $value;
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
    public function setType($value)
    {
        if ($value == $this->getType()) {
            return;
        }

        $this->_input['type'] = (string) $value;
    }

}

