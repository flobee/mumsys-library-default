<?php

/* {{{ */
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
/* }}} */


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
     * Flag to set if some properties has changed.
     * @var boolean
     */
    protected $_modified = false;

    /**
     * Flag if value was validated or not.
     * @var boolean
     */
    private $_isValidated = false;

    /**
     * Flag if validation succeed.
     * @var boolean
     */
    private $_isValid = false;

    /**
     * List of possible states to render the value.
     * @var array
     */
    private $_states = array('onEdit', 'onView', 'onSave');

    /**
     * Current status to use filters or callbacks for.
     * @var string
     */
    private $_state = 'onView';

    /**
     * Registered filters.
     *
     * @var array
     */
    private $_filters = null;

    /**
     * Registered callbacks.
     * @var array
     */
    private $_callbacks = null;


    /**
     * Returns the item value for output.
     *
     * @return string
     */
    public function __toString()
    {
        $return = $this->getValue();
        if ($return === null) {
            $return = '';
        }

        return $return;
    }


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
        if ( isset($this->_input['name']) && $value === $this->_input['name'] ) {
            return;
        }

        $this->_input['name'] = (string) $value;
        $this->_modified = true;
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
        if ( $value === $this->getValue() ) {
            return;
        }

        $this->_input['value'] = $value;
        $this->_modified = true;
    }


    /**
     * Returns all error messages of this item if any exists.
     *
     * @return array List of key/value pairs of error messages
     */
    public function getErrorMessages()
    {
        return ( isset($this->_input['errors']) ? (array) $this->_input['errors'] : array() );
    }


    /**
     * Sets/ replaces an error message by given key.
     *
     * @param string $key Internal ID of the error (e.g: TOO_LONG, TOO_SHORT message)
     * @param string $value Error message value
     */
    public function setErrorMessage( $key, $value )
    {
        $this->_input['errors'][$key] = $value;
    }


    /**
     * Set/ replaces the list of error messages.
     *
     * @param array $list List of key/value pairs of error messages
     */
    public function setErrorMessages( array $list )
    {
        $this->_input['errors'] = $list;
    }


    /**
     * Returns the item validation status.
     *
     * @return boolean Returns true on success otherwise false
     */
    public function isValid()
    {
        return $this->_isValid;
    }


    /**
     * Sets the validation status.
     *
     * @param boolean $value True for success otherwise false
     */
    public function setValidated( $success = false )
    {
        $this->_isValidated = true;
        $this->_isValid = (bool) $success;
    }


    /**
     * Adds a filter for the given state.
     *
     * Filters have a variable signature like php functions have. Filter
     * function signature is: functionName(mixed params)
     * To replace the current value use %value% in the parameters list.
     *
     * Differents between filters and callbacks:
     *  - different function signature
     *  - filters are only for the item object itselves
     *  - callbacks can be used from outside using callbacksGet() methode.
     *
     * Example:
     * <code>
     * // php function substr($value, 0, 150);
     * $item->filterAdd('onSave', 'substr', array('%value%', 0, 150) );
     * // php function str_replace('this', 'by that', $value]);
     * $item->filterAdd('onSave', 'str_replace', array('this', 'by that', '%value%'));
     * // call php's substr and cut the last 3 chars
     * $item->filterAdd('onSave', 'substr', array('%value%', -3) );
     * // cast total to be a float value. Both options are possible:
     * $item->filterAdd('onEdit', 'floatval');
     * $item->filterAdd('onEdit', 'floatval', array('%value%') );
     * </code>
     *
     * @param string $state State to add the filter for {@link $_states}
     * @param string $cmd Function name to call
     * @param array|null $parameters Parameters to be set for 'php' operations.
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@link $_states}
     */
    public function filterAdd( $state, $cmd, array $parameters = null )
    {
        if ( $this->_initExternalType('filters') ) {
            $this->_initExternalCalls('filters', $this->_input['filters']);
        }

        $this->_filterSet($state, $cmd, $parameters);
    }


    /**
     * Returns a list of filter configurations.
     *
     * If flag $all is set to true all filters will return otherwise just the
     * filters of the current {@link $_state}.
     *
     * @param boolean $all Flag to return all filters.
     *
     * @return array List of filter rules or empty array if none exists.
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@link $_states}
     */
    public function filtersGet( $all = null )
    {
        if ( $this->_initExternalType('filters') ) {
            $this->_initExternalCalls('filters', $this->_input['filters']);
        }

        if ( $all === true ) {
            $return = $this->_filters;
        } else {
            $this->_stateCheck($this->_state);

            if ( !isset($this->_filters[$this->_state]) ) {
                $return = array();
            } else {
                $return = $this->_filters[$this->_state];
            }
        }

        return $return;
    }


    /**
     * Adds a callback for the given state.
     *
     * Callbacks have a static function signature:
     *      functionName(Mumsys_Variable_Item $item, array $optionalParams)
     *
     * Differents between filters and callbacks:
     *  - different function signature
     *  - filters are only for the item object itselves
     *  - callbacks can be used from outside using callbacksGet() methode.
     *
     * Example:
     * <code>
     * // To call eg: my_substr(Mumsys_Variable_Item $item, $params=array(0, 150));
     * $item->callbackAdd('onSave', 'my_substr', array(0, 150));
     * </code>
     *
     * @param string $state State to add the filter for {@link $_states} e.g.:
     * 'onEdit', 'onSave', 'onView'
     * @param string $cmd Function name to call
     * @param array|null $parameters Parameters to be set for 'php' operations.
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@link $_states}
     */
    public function callbackAdd( $state, $cmd, array $parameters = null )
    {
        if ( $this->_initExternalType('callbacks') ) {
            $this->_initExternalCalls('callbacks', $this->_input['callbacks']);
        }

        $this->_callbackSet($state, $cmd, $parameters);
    }


    /**
     * Returns a list of callback configurations.
     *
     * If flag $all is set to true all callbacks will return otherwise just the
     * filters of the current {@link $_state}.
     *
     * @param boolean $all Flag to return all callbacks.
     *
     * @return array List of callbacks rules or empty array if none exists.
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@link $_states}
     */
    public function callbacksGet( $all = null )
    {
        if ( $this->_initExternalType('callbacks') ) {
            $this->_initExternalCalls('callbacks', $this->_input['callbacks']);
        }

        if ( $all === true ) {
            $return = $this->_callbacks;
        } else {
            $this->_stateCheck($this->_state);

            if ( !isset($this->_callbacks[$this->_state]) ) {
                $return = array();
            } else {
                $return = $this->_callbacks[$this->_state];
            }
        }

        return $return;
    }

    // --- private methodes ---------------------------------------------------


    /**
     * Sets the filter to the list of item filters.
     *
     * @param string $state State to add the filter for {@link $_states} e.g.:
     * 'onEdit', 'onSave', 'onView'
     * @param string $cmd Function name to call
     * @param array|null $parameters Parameters to be set for 'php' operations.
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@see $_states of manager}
     */
    private function _filterSet( $state, $cmd, $parameters = null )
    {
        $this->_stateCheck($state);
        $this->_filters[$state][] = array(
            'cmd' => $cmd,
            'params' => $parameters,
        );
    }


    /**
     * Sets the callback to the list of item callbacks.
     *
     * @param string $state State to add the filter for {@link $_states} e.g.:
     * 'onEdit', 'onSave', 'onView'
     * @param string $cmd Function name to call
     * @param array|null $parameters Parameters to be set for 'php' operations.
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@see $_states of manager}
     */
    private function _callbackSet( $state, $cmd, $parameters = null )
    {
        $this->_stateCheck($state);
        $this->_callbacks[$state][] = array(
            'cmd' => $cmd,
            'params' => $parameters,
        );
    }


    /**
     * Initialize internal callback variables to be able to fill it with
     * properties when needed and not in construction which may not needed
     * in some cases.
     *
     * @param string $type Type of the variable to init: filters or callbacks
     * @return boolean Returns true to init existing callbacks from construction,
     * false if there are no callbacks or filters set
     */
    private function _initExternalType( $type )
    {
        $_type = '_' . $type;
        if ( $this->$_type !== null ) {
            return false;
        }

        if ( !isset($this->_input[$type]) ) {
            $this->$_type = array();
            return false;
        }

        return true;
    }


    /**
     * Initialize a list of callback/filter rules.
     *
     * Example:
     * <code>
     * $list = array(
     *  'onView' => array(
     *      'substr', array('%value%', 0, 150),
     *      'str_replace', array('this', 'by that', '%value%')),
     *  'onEdit' => array(
     *      'htmlspecialchars', array('%value%', ENT_QUOTES)),
     *  'onSave => array(
     *      'trim',
     *      'json_encode' => array('%value%', true)
     *  ...
     * </code>
     *
     * @param string $type Type to initialise e.g: "filters"|"callbacks"
     * @param array $list List of rules to initialize
     */
    private function _initExternalCalls( $type, $list )
    {
        foreach ( $this->_input[$type] as $state => $props ) {
            if ( is_array($props) ) {
                foreach ( $props as $cmd => $params ) {
                    if ( is_int($cmd) ) {
                        $this->_setExternalCall($type, $state, $params);
                    } else {
                        $this->_setExternalCall($type, $state, $cmd, $params);
                    }
                }
            } else if ( is_string($props) ) {
                $this->_setExternalCall($type, $state, $props);
            }
        }
    }


    /**
     * Sets/ adds a callback/filter by given type..
     *
     * @param string $type Type to set e.g.: "filters" | "callbacks"
     * @param string $state State to add the filter for {@link $_states} e.g.:
     * 'onEdit', 'onSave', 'onView'
     * @param string $cmd Function name to call
     * @param array|null $params Parameters to be set for 'php' operations.
     *
     * @throws Mumsys_Variable_Item_Exception On errors
     */
    private function _setExternalCall( $type, $state = null, $cmd = null, $params = null )
    {
        switch ( $type )
        {
            case 'filters':
                $this->_filterSet($state, $cmd, $params);
                break;

            case 'callbacks':
                $this->_callbackSet($state, $cmd, $params);
                break;
        }
    }


    /**
     * Tests if the Item was modified or not.
     *
     * @return boolean True if modified otherwise false
     */
    public function isModified()
    {
        return $this->_modified;
    }


    /**
     * Sets the modified flag of the object.
     */
    public function setModified()
    {
        $this->_modified = true;
    }


    /**
     * Sets the current state for filters and callbacks.
     *
     * @param string $state State to be set: 'onEdit','onView', 'onSave'
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@link $_states}
     */
    public function stateSet( $state = 'onView' )
    {
        $this->_stateCheck($state);
        $this->_state = $state;
    }


    /**
     * Returns the current state.
     *
     * @return string Current state
     */
    public function stateGet()
    {
        return $this->_state;
    }


    /**
     * Returns the list of possible states.
     *
     * @return array List of states
     */
    public function statesGet()
    {
        return $this->_states;
    }


    /**
     * Checks if given state is in the list of allowed/ implemented states.
     *
     * @param string $state State to be set: 'onEdit','onView', 'onSave'...
     *
     * @throws Mumsys_Variable_Item_Exception If state not part of {@link $_states}
     */
    private function _stateCheck( $state )
    {
        if ( !in_array($state, $this->_states) ) {
            $message = sprintf('State "%1$s" unknown', $state);
            throw new Mumsys_Variable_Item_Exception($message);
        }
    }

}
