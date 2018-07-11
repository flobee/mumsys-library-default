<?php

/**
 * Mumsys_Variable_Item_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Variable
 * @verion      1.1.1
 * Created: 2006 based on Mumsys_Field, renew 2016
 */


/**
 * Default variable item interface.
 *
 * Default item implementation as variable item interface for general web
 * related tasks like create/edit/save variables.
 * Each variable should be an object with a standard set of methodes which are
 * needed for these tasks. This is the standard set of methodes as interface
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Variable
 */
interface Mumsys_Variable_Item_Interface
{
    /**
     * Initialisation of the variable item object.
     *
     * @see $_properties
     *
     * @param array $properties List of key/value config parameters to be set.
     * Config values MUST NOT be null!
     */
    public function __construct( array $properties = array() );


    /**
     * Returns the item key/identifier name.
     * Note: From a list of key/value pairs: this is the key used as name.
     *
     * @param mixed $default Default (null) return value if name was not set
     *
     * @return string|mixed|null Item name key/identifier
     */
    public function getName( $default = null );


    /**
     * Sets the item key name/ identifier.
     * If value exists and is the same than the current one null is returned.
     *
     * @param string $value Item key/itenifier
     */
    public function setName( $value );


    /**
     * Returs the item value or null if not set;
     *
     * @param mixed $default Default return value if value not exists
     * @return mixed|null Returns the item value or $default
     */
    public function getValue( $default = null );


    /**
     * Sets the item value.
     * If value exists and is the same than the current one null is returned.
     *
     * @param mixed $value Item value to be set
     */
    public function setValue( $value );


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
     * {@link Mumsys_Variable_Abstract::TYPES} for a complete list.
     *
     * @param string $value Type to be set
     *
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


    /**
     * Returns the list of regular expressions.
     *
     * @return array List of regular expression or null
     */
    public function getRegex();


    /**
     * Sets/ replace a regular expression.
     *
     * @param string $value Regular expression
     */
    public function setRegex( $value );


    /**
     * Adds a new regular expressions to the list of regular expressions.
     *
     * @param string $value Regular expression
     */
    public function addRegex( $value );


    /**
     * Returns the allow empty flag of the item.
     *
     * @return boolean Allow empty flag
     */
    public function getAllowEmpty();


    /**
     * Sets the allow empty flag of the item.
     *
     * @param boolean $value True to allow empty values or false for not allow
     * empty values
     */
    public function setAllowEmpty( $value );


    /**
     * Returns the required status of the item.
     *
     * @return boolean Required status
     */
    public function getRequired();


    /**
     * Sets required flag of the item.
     *
     * @param boolean $value Required flag
     */
    public function setRequired( $value );


    /**
     * Returns the item label.
     *
     * @param string $altnKey Alternativ property key to get if label not exists
     * (default: "name" for getName().
     *
     * @return string Item/ variable label
     */
    public function getLabel( $altnKey = 'name' );


    /**
     * Sets the item label.
     *
     * @param string $value Label to set
     */
    public function setLabel( $value );


    /**
     * Returns the item description.
     *
     * @return string|null Item description
     */
    public function getDescription();


    /**
     * Sets the item description.
     * Note: Description of what kind of value will be expected e.g. in a form.
     * E.g: "Enter your email address"
     *
     * @param string $value Description to set
     */
    public function setDescription( $value );


    /**
     * Returns the item additional information value.
     *
     * @return string|null Item information
     */
    public function getInformation();


    /**
     * Sets the item additional information value.
     * Note: Information about the item of what kind of value will be expected
     * or how things will go.
     *
     * @param string $value Additional information value
     */
    public function setInformation( $value );


    /**
     * Sets/ replace an error message by given key and message value.
     *
     * @param string $key Error key name/identifier
     * @param string $message Error message
     */
    public function setErrorMessage( $key, $message );


    /**
     * Returns all error messages of this item if any exists.
     *
     * @return array List of key/value pairs of error messages
     */
    public function getErrorMessages();
}
