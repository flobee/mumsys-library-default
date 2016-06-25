<?php

/*{{{*/
/**
 * Mumsys_Variable_Item_Default
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
 * Default item implementation as variable item interface for general web
 * related tasks like create/edit/save variables.
 * Each variable should be an object with a standard set of methodes which are
 * needed for these tasks.
 * This class only keeps informational properties like name, value, label, description and additional information.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 */
class Mumsys_Variable_Item_Default
    extends Mumsys_Variable_Item_Abstract
    implements Mumsys_Variable_Item_Interface
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
        'name' => true, 'value' => true, 'label' => true, 'desc' => true, 'info' => true
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
        foreach ( $this->_properties as $key => $value ) {
            if ( isset($properties[$key]) ) {
                $this->_input[$key] = $properties[$key];
            }
        }
    }


    /**
     * Returns the item label.
     *
     * @param string $altnKey Alternativ property key to get if label not exists (default: "name" for getName().
     * @return string Item/ variable label
     */
    public function getLabel( $altnKey = 'name' )
    {
        $return = null;

        if ( isset($this->_input['label']) ) {
            $return = $this->_input['label'];
        } else if ( isset($this->_input[$altnKey]) ) {
            $return = (string) $this->_input[$altnKey];
        }

        return $return;
    }


    /**
     * Sets the item label.
     *
     * @param string $value Label to set
     */
    public function setLabel( $value )
    {
        $this->_input['label'] = (string) $value;
    }


    /**
     * Returns the item description.
     *
     * @return string|null Item description
     */
    public function getDescription()
    {
        return (isset($this->_input['desc'])) ? $this->_input['desc'] : null;
    }


    /**
     * Sets the item description.
     * Note: Description of what kind of value will be expected e.g. in a form. E.g: "Enter your email address"
     *
     * @param string $value Description to set
     */
    public function setDescription( $value )
    {
        $this->_input['desc'] = (string) $value;
    }


    /**
     * Returns the item additional information value.
     *
     * @return string|null Item information
     */
    public function getInformation()
    {
        return (isset($this->_input['info'])) ? $this->_input['info'] : null;
    }


    /**
     * Sets the item additional information value.
     * Note: Information about the item of what kind of value will be expected or how things will go.
     *
     * @param string $value Additional information value
     */
    public function setInformation( $value )
    {
        $this->_input['info'] = (string) $value;
    }

}

