<?php

/**
 * Mumsys_Variable_Item_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 * Created: 2006 based on Mumsys_Field, renew 2016
 */


/**
 * Default item implementation as variable item interface for general web
 * related tasks like create/edit/save variables.
 *
 * This class does the following: Each variable should be an object with a
 * standard set of methodes which are needed for these tasks.
 * This class keeps default properties like name, value, type, minlen, maxlen,
 * label, description, additional information, regular expressions for
 * validation checks, filter, callbacks setups and error messages.
 * With this you already have a powerful set to handle and validate variables
 * internally and also for the frontend to show properties around the key/
 * value pair.
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
        'name' => true,
        'value' => true,
        'label' => true,
        'desc' => true,
        'info' => true,
        'default' => true,
        'type' => true,
        'minlen' => true,
        'maxlen' => true,
        'regex' => true,
        'allowEmpty' => true,
        'required' => true,
        'errors' => true,
        'filters' => true,
        'callbacks' => true,
    );


    /**
     * Initialisation of the item object.
     *
     * @see $_properties
     *
     * @param array $properties List of key/value config parameters to be set.
     * Config values MUST NOT be null!
     */
    public function __construct( array $properties = array() )
    {
        foreach ( $this->_properties as $key => $value ) {
            if ( isset($properties[$key]) ) {
                $this->_input[$key] = $properties[$key];
            }
        }
    }


    /**
     * Returns the variable item type.
     *
     * Hint: {@link Mumsys_Variable_Abstract::TYPES} To see internal handling
     * by the manager.
     *
     * @return string|null Item type
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
     * {@link Mumsys_Variable_Abstract::getTypes} for a complete list.
     *
     * @param string $value Type to be set
     *
     * @throws Mumsys_Variable_Item_Exception it type not implemented
     */
    public function setType( $value )
    {
        if ( $value === $this->getType() ) {
            return;
        }

        if ( !in_array($value, Mumsys_Variable_Abstract::$_types) ) {
            throw new Mumsys_Variable_Item_Exception(sprintf('Type "%1$s" not implemented', $value));
        }

        $this->_input['type'] = (string) $value;
        $this->_modified = true;
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
        if ( $value === $this->getMinLength() ) {
            return;
        }

        if ( $value === null ) {
            $this->_input['minlen'] = null;
        } else {
            $this->_input['minlen'] = (float) $value;
        }

        $this->_modified = true;
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
     * @param float|null $value Maximum item value length
     */
    public function setMaxLength( $value )
    {
        if ( $value === $this->getMaxLength() ) {
            return;
        }

        if ( $value === null ) {
            $this->_input['maxlen'] = null;
        } else {
            $this->_input['maxlen'] = (float) $value;
        }

        $this->_modified = true;
    }


    /**
     * Returns the list of regular expressions.
     *
     * @return array List of regular expression or null
     */
    public function getRegex()
    {
        $value = & $this->_input['regex'];
        $return = array();

        if ( isset($value) ) {
            if ( is_array($value) ) {
                $return = (array) $value;
            } else if ( is_string($value) && $value > '' ) {
                $return = $this->_input['regex'] = array($this->_input['regex']);
            }
        }

        return $return;
    }


    /**
     * Sets/ replace a regular expression.
     *
     * @param string $value Regular expression
     */
    public function setRegex( $value )
    {
        if ( $value === $this->getRegex() || !is_string($value) ) {
            return;
        }

        $this->_input['regex'] = array((string) $value);
        $this->_modified = true;
    }


    /**
     * Adds a new regular expressions to the list of regular expressions.
     *
     * @param string $value Regular expression
     */
    public function addRegex( $value )
    {
        $this->_input['regex'][] = (string) $value;
        $this->_modified = true;
    }


    /**
     * Returns the allow empty flag of the item.
     *
     * @return boolean|null Allow empty flag
     */
    public function getAllowEmpty()
    {
        return ( isset($this->_input['allowEmpty']) ? (boolean) $this->_input['allowEmpty'] : null );
    }


    /**
     * Sets the allow empty flag of the item.
     *
     * @param boolean $value True to allow empty values or false for not allow empty values
     */
    public function setAllowEmpty( $value )
    {
        if ( $value == $this->getAllowEmpty() ) {
            return;
        }

        $this->_input['allowEmpty'] = (boolean) $value;
        $this->_modified = true;
    }


    /**
     * Returns the required status of the item.
     *
     * @return boolean|null Required status
     */
    public function getRequired()
    {
        return ( isset($this->_input['required']) ? (boolean) $this->_input['required'] : null );
    }


    /**
     * Sets required flag of the item.
     *
     * @param boolean $value Required flag
     */
    public function setRequired( $value )
    {
        if ( $value == $this->getRequired() ) {
            return;
        }

        $this->_input['required'] = (boolean) $value;
        $this->_modified = true;
    }


    /**
     * Returns the item label (or item "name" if label was not set or default if both arn't set).
     *
     * @param string $altnKey Alternativ property key to get if label not
     * exists (default: "name" for getName().
     * @param mixed $default Default value to return if the propertys are not set
     *
     * @return string Item/ variable label
     */
    public function getLabel( $altnKey = 'name', $default = '' )
    {
        if ( isset($this->_input['label']) ) {
            $return = $this->_input['label'];
        } else if ( isset($this->_input[$altnKey]) ) {
            $return = (string) $this->_input[$altnKey];
        } else {
            $return = $default;
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
        if ( $value === $this->getLabel() ) {
            return;
        }

        $this->_input['label'] = (string) $value;
        $this->_modified = true;
    }


    /**
     * Returns the item description.
     *
     * @param mixed $default Default value to return if the property was not set
     *
     * @return string|null Item description
     */
    public function getDescription( $default = null )
    {
        return (isset($this->_input['desc'])) ? $this->_input['desc'] : $default;
    }


    /**
     * Sets the item description.
     * Note: Description of what kind of value will be expected e.g. in a form. E.g: "Enter your email address"
     *
     * @param string $value Description to set
     */
    public function setDescription( $value )
    {
        if ( $value === $this->getDescription() ) {
            return;
        }

        $this->_input['desc'] = (string) $value;
        $this->_modified = true;
    }


    /**
     * Returns the item additional information value.
     *
     * @param mixed $default Default value to return if the property was not set
     *
     * @return string|null Item information
     */
    public function getInformation( $default = null )
    {
        return (isset($this->_input['info'])) ? $this->_input['info'] : $default;
    }


    /**
     * Sets the item additional information value.
     * Note: Information about the item of what kind of value will be expected or how things will go.
     *
     * @param string $value Additional information value
     */
    public function setInformation( $value )
    {
        if ( $value === $this->getInformation() ) {
            return;
        }

        $this->_input['info'] = (string) $value;
        $this->_modified = true;
    }


    /**
     * Returns the default item value.
     *
     * @param mixed $default Default value to return if the property was not set
     *
     * @return mixed|null Item "default" value
     */
    public function getDefault( $default = null )
    {
        return ( isset($this->_input['default']) ? $this->_input['default'] : $default );
    }


    /**
     * Sets a default value for the item.
     *
     * @param mixed $value Default value to set
     * @return void
     */
    public function setDefault( $value )
    {
        if ( $value === $this->getDefault() ) {
            return;
        }

        $this->_input['default'] = $value;
        $this->_modified = true;
    }

}
