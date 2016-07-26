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
 * Created: 2006 based on Mumsys_Field, renew 2016
 */
/*}}}*/


/**
 * Default item implementation as variable item interface for general web
 * related tasks like create/edit/save variables.
 * Each variable should be an object with a standard set of methodes which are
 * needed for these tasks.
 * This class only keeps minimum getter/setter like get/set name, value and error messages.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 */
abstract class Mumsys_Variable_Item_Abstract
    extends Mumsys_Variable_Abstract
    implements Mumsys_Variable_Item_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.1';

    /**
     * List of initial incoming variable properties to be set on construction.
     * @var array
     */
    protected $_input = array();

    /**
     * List of error messages
     * @var array
     */
    protected $_errorMessages = array();


    /**
     * Returns the item key/identifier name.
     * Note: From a list of key/value pairs: this is the key used as name.
     *
     * @param mixed $default Default (null) return value if name was not set
     * @return string Item name key/identifier
     */
    public function getName( $default = null )
    {
        return (isset($this->_input['name']) ? (string) $this->_input['name'] : $default);
    }


    /**
     * Sets the item key name/ identifier.
     *
     * @param string $value Item key/itenifier
     */
    public function setName( $value )
    {
        $this->_input['name'] = (string) $value;
    }


    /**
     * Returns the item value or null if not set
     *
     * @param mixed $default Default return value if value not exists
     * @return mixed|null Returns the item value or $default
     */
    public function getValue( $default = null )
    {
        return (isset($this->_input['value'])) ? $this->_input['value'] : $default;
    }


    /**
     * Sets the item value.
     *
     * @param mixed $value Item value to be set
     */
    public function setValue( $value )
    {
        $this->_input['value'] = $value;
    }


    /**
     * Sets/ replace an error message by given key and message value.
     *
     * @param string $key Error key name/identifier
     * @param string $message Error message
     */
    public function setErrorMessage( $key, $message )
    {
        $this->_errorMessages[$key] = (string) $message;
    }


    /**
     * Returns all error messages of this item if any exists.
     *
     * @return array List of key/value pairs of error messages
     */
    public function getErrorMessages()
    {
        return $this->_errorMessages;
    }

}
