<?php

/* {{{ */
/**
 * Mumsys_Variable_Manager_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 * Created: 2006 based on Mumsys_Field, renew 2016
 */
/* }}} */


/**
 * Default variable item manager to handle variable items.
 *
 * This class hold your variables as objects and is able to to show specific
 * informations (if set) or does the validation after a user input.
 * Example:
 * <code>
 * $config = array(
 *     'username' => array(
 *         'minlen' => 1,
 *         'maxlen' => 250,
 *         'required' => true,
 *         'allowEmpty' => false,
 *         'label' => 'Username',
 *         'desc' => 'You username/ alias name',
 *         'info' => 'Required, min 1, max 250 chars',
 *     )
 * );
 * $object = new Mumsys_Variable_Manager_Default($config, $_POST);
 * // in edit mode
 * $usernameObject = $object->getItem('username');
 * // in template, e.g.:
 * if ($usernameObject->getDescription()) echo $usernameObject->getDescription();
 * if ($usernameObject->getInformation()) echo $usernameObject->getInformation();
 * echo $usernameObject->getLabel() . ': ' . $usernameObject->getValue()
 * // in save mode:
 * if ($object->validate()) { save data }
 * </code>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 */
class Mumsys_Variable_Manager_Default
    implements Mumsys_Variable_Manager_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.1';

    /**
     * Value "%1$s" does not match the regex rule: "%2$s"
     */
    const REGEX_FAILURE = 'REGEX_FAILURE';

    /**
     * Error in regular expression
     */
    const REGEX_ERROR = 'REGEX_ERROR';

    /**
     * Missing required value
     */
    const REQUIRED_MISSING = 'REQUIRED_MISSING';

    /**
     * Missing value
     */
    const ALLOWEMPTY_ERROR = 'ALLOWEMPTY_ERROR';

    /**
     * Value (json):"%1$s" is not a "string"
     */
    const TYPE_INVALID_STRING = 'TYPE_INVALID_STRING';

    /**
     * Value (json):"%1$s" is not an "array"
     */
    const TYPE_INVALID_ARRAY = 'TYPE_INVALID_ARRAY';

    /**
     * Value "%1$s" is not a valid value for type "email"
     */
    const TYPE_INVALID_EMAIL = 'TYPE_INVALID_EMAIL';

    /**
     * Value (json):"%1$s" is not a "numeric" type
     */
    const TYPE_INVALID_NUMERIC = 'TYPE_INVALID_NUMERIC';

    /**
     * Value (json):"%1$s" is not a "float" type
     */
    const TYPE_INVALID_FLOAT = 'TYPE_INVALID_FLOAT';

    /**
     * Value (json):"%1$s" is not an "integer" type
     */
    const TYPE_INVALID_INT = 'TYPE_INVALID_INT';

    /**
     * Value (json):"%1$s" is not a "date" type
     */
    const TYPE_INVALID_DATE = 'TYPE_INVALID_DATE';

    /**
     * Value (json):"%1$s" is not a "datetime" type
     */
    const TYPE_INVALID_DATETIME = 'TYPE_INVALID_DATETIME';

    /**
     * Value (json):"%1$s" is not an "ipv4" address
     */
    const TYPE_INVALID_IPV4 = 'TYPE_INVALID_IPV4';

    /**
     * Value (json):"%1$s" is not an "ipv6" address
     */
    const TYPE_INVALID_IPV6 = 'TYPE_INVALID_IPV6';

    /**
     * Value "%1$s" must contain at least "%2$s" characters
     */
    const MINMAX_TOO_SHORT_STR = 'MINMAX_TOO_SHORT_STR';

    /**
     * Value "%1$s" must contain maximum of "%2$s" characters, "%3$s" given
     */
    const MINMAX_TOO_LONG_STR = 'MINMAX_TOO_LONG_STR';

    /**
     * Value "%1$s" must be minimum "%2$s"
     */
    const MINMAX_TOO_SHORT_NUM = 'MINMAX_TOO_SHORT_NUM';

    /**
     * Value "%1$s" can be maximum "%2$s"
     */
    const MINMAX_TOO_LONG_NUM = 'MINMAX_TOO_LONG_NUM';

    /**
     * Min/max type error "%1$s". Must be "string", "integer", "numeric", "float" or "double"
     */
    const MINMAX_TYPE_ERROR = 'MINMAX_TYPE_ERROR';

    /**
     * Filter "%1$s" failt for label/name: "%2$s"
     */
    const FILTER_ERROR = 'FILTER_ERROR';

    /**
     * Filter function "%1$s" not found for item: "%2$s"
     */
    const FILTER_NOTFOUND = 'FILTER_NOTFOUND';

    /**
     * Callback "%1$s" for "%2$s" failt for value: "%3$s"'
     */
    const CALLBACK_ERROR = 'CALLBACK_ERROR';

    /**
     * Callback function "%1$s" not found for item: "%2$s"
     */
    const CALLBACK_NOTFOUND = 'CALLBACK_NOTFOUND';

    /**
     * List key/validation items.
     * @var array
     */
    private $_items;

    /**
     * List of error messages used in this manager
     * @var array
     */
    private $_messageTemplates = array(
        // basic checks
        self::REQUIRED_MISSING => 'A required value is missing',
        self::ALLOWEMPTY_ERROR => 'Missing value',

        //regex checks
        self::REGEX_FAILURE => 'Value "%1$s" does not match the regular expression/s (json): "%2$s"',
        self::REGEX_ERROR => 'Error in regular expression. Check syntax!',

        // type checks
        self::TYPE_INVALID_STRING => 'Value (json): "%1$s" is not a "string"',
        self::TYPE_INVALID_ARRAY => 'Value (json): "%1$s" is not an "array"',
        self::TYPE_INVALID_EMAIL => 'Value "%1$s" is not a valid "email" address',
        self::TYPE_INVALID_NUMERIC => 'Value (json): "%1$s" is not a "numeric" value',
        self::TYPE_INVALID_FLOAT => 'Value (json): "%1$s" is not a "float" value',
        self::TYPE_INVALID_INT => 'Value (json): "%1$s" is not an "integer"',
        self::TYPE_INVALID_DATE => 'Value (json): "%1$s" is not of type "date"',
        self::TYPE_INVALID_DATETIME => 'Value (json): "%1$s" is not of type "datetime"',
        self::TYPE_INVALID_IPV4 => 'Value (json):"%1$s" is not an "ipv4" address',
        self::TYPE_INVALID_IPV6 => 'Value (json):"%1$s" is not an "ipv6" address',

        //min max checks
        self::MINMAX_TOO_SHORT_STR => 'Value "%1$s" must contain at least "%2$s" characters',
        self::MINMAX_TOO_LONG_STR => 'Value "%1$s" must contain maximum of "%2$s" characters, "%3$s" given',
        self::MINMAX_TOO_SHORT_NUM => 'Value "%1$s" must be minimum "%2$s"',
        self::MINMAX_TOO_LONG_NUM => 'Value "%1$s" can be maximum "%2$s"',
        self::MINMAX_TYPE_ERROR => 'Min/max type error "%1$s". Must be "string", "integer", "numeric", "float"'
        . ' or "double"',

        self::FILTER_ERROR => 'Filter "%1$s" failt for label/name: "%2$s"',
        self::FILTER_NOTFOUND => 'Filter function "%1$s" not found for item: "%2$s"',

        self::CALLBACK_ERROR => 'Callback "%1$s" for "%2$s" failt for value: "%3$s"',
        self::CALLBACK_NOTFOUND => 'Callback function "%1$s" not found for item: "%2$s"',
    );


    /**
     * Initialises the default manager and variable item objects.
     *
     * Example:
     * <code>
     * $config = array(
     *  'user.name' => array(           // address/name of the item to work with withing the manager
     *      'name' => 'name',           // real name of the item; optional if the address contains the same name
     *      'label' => 'User name',
     *      'desc' => 'User group name',
     *      'info' => "Allowed characters: a-z A-Z 0-9 _ - \nMin. 5 chars max. 45 chars.",
     *      'type' => 'string',
     *      'minlen' => 5,
     *      'maxlen' => 45,
     *      'allowEmpty' => false,
     *      'required' => true,
     *      'regex' => '/^([a-zA-Z0-9-_]{4,45})*$/i',
     *      'default' => '',
     *      'filters' => array(
     *          'onSave' => array(
     *              'trim', 'substr' => array('%value%', 0,45)
     *          )
     * ), ...
     * $values = $_REQUEST;
     * $validator = new Mumsys_Validate_Manager_Default($config, $values);
     *
     * // Sets the state and applys it to the items so that filters are ready befor validatation.
     * //default is "onView"; for maximum performance user the state on construction, this is just
     * a helper to force the state for all reqistered items.
     * $validator->setAttributes( array('state' => 'onSave') );
     * $validator->filtersApply()
     * $success = $validator->validate();
     *
     * $userItem = $validator->getItem('user.name');
     * $userItem->setValue('I\'m your user name');
     * echo $userItem->getLabel();
     * echo $userItem->getDescription();
     * echo $userItem->getInformation();
     * print_r($userItem->getErrorMessages());
     * $itemSuccess = $validator->isValid($userItem);
     * </code>
     *
     * @param array $config List of key/value configuration pairs containing item properties for the item construction
     * @param array $values List of key/value pairs to set/bind to the item values e.g: the post parameters
     */
    public function __construct( array $config = array(), array $values = array() )
    {
        foreach ( $config as $itemKey => $properties )
        {
            /**
             * @todo name vs itemKey needs more understanding how to use it
             * if name is missing then its easy but if both is set and
             * different it is difficult to understand!
             */
            if (!isset($properties['name'])) {
                $properties['name'] = $itemKey;
            }

//            if ($properties['name'] !== $itemKey) {
//                $message = sprintf(
//                    'Item name ("%1$s") and item address (record key: "%2$s"), both set and not identcal',
//                    $properties['name'],
//                    $itemKey
//                );
//                throw new Mumsys_Variable_Manager_Exception($message);
//            }

            $internalKey = $properties['name'];

            if ( isset( $values[$internalKey] ) ) {
                $properties['value'] = $values[$internalKey];
            }

            $this->_items[$itemKey] = $this->createItem($properties);
        }
    }


    /**
     * Validate registered variable items.
     *
     * @return boolean True on success or false on error
     */
    public function validate()
    {
        $status = true;
        foreach ( $this->_items as $key => $item ) {
            if ( !$this->isValid($item) ) {
                $status = false;
            }
        }

        return $status;
    }


    /**
     * Item type validation.
     *
     * If the test fails an error message will be set at the item.
     *
     * @param Mumsys_Variable_Item_Interface $item Variable item interface
     *
     * @return boolean True on success otherwise false.
     */
    public function validateType( Mumsys_Variable_Item_Interface $item )
    {
        $return = true;

        $type = $item->getType();
        $value = $item->getValue();

        $errorKey = false;
        $errorMessage = false;

        switch ( $type )
        {
            case 'string':
            case 'char':
            case 'varchar':
            case 'text':
            case 'tinytext':
            case 'longtext':
                if ( !is_string($value) ) {
                    $errorKey = self::TYPE_INVALID_STRING;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_STRING'], json_encode($value));
                }
                break;

            case 'array':
                if ( !is_array($value) ) {
                    $errorKey = self::TYPE_INVALID_ARRAY;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_ARRAY'], json_encode($value));
                }
                break;

            case 'email':
                $email = trim($value);
                if ( !preg_match('/^[a-z0-9_\.-]+@[a-z0-9_\.-]+\.[a-z]{2,6}$/i', $email) ) {
                    $errorKey = self::TYPE_INVALID_EMAIL;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_EMAIL'], $email);
                }
                break;

            case 'numeric':
                if ( !is_numeric($value) ) {
                    $errorKey = self::TYPE_INVALID_NUMERIC;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_NUMERIC'], json_encode($value));
                }
                break;

            case 'float':
            case 'double':
                $value = is_numeric($value) ? (float) $value : $value;
                if ( !is_float($value) ) {
                    $errorKey = self::TYPE_INVALID_FLOAT;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_FLOAT'], json_encode($value));
                }
                break;

            case 'int':
            case 'integer':
            case 'smallint':
                $value = is_numeric($value) ? (int) $value : $value;
                if ( !is_int($value) ) {
                    $errorKey = self::TYPE_INVALID_INT;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_INT'], json_encode($value));
                }
                break;

            case 'date':
                if ( strlen($value) != 10 && !preg_match('/^(\d{4})-(\d{2})-(\d{2})/i', $value) ) {
                    $errorKey = self::TYPE_INVALID_DATE;
                    $errorMessage = sprintf(
                        $this->_messageTemplates['TYPE_INVALID_DATE'],
                        json_encode($value)
                    );
                }
                break;

            case 'datetime':
            case 'timestamp':
                if ( strlen($value) != 19 && !preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{1,2}):(\d{1,2}):(\d{1,2})/i',
                        $value) )
                {
                    $errorKey = self::TYPE_INVALID_DATETIME;
                    $errorMessage = sprintf($this->_messageTemplates['TYPE_INVALID_DATETIME'], json_encode($value));
                }
                break;

            case 'unixtime':
                throw new Mumsys_Variable_Manager_Exception(sprintf('Type "%1$s" not implemented', $type));
                break;

            case 'ipv4':
                $return = $this->validateIPv4($item);
                break;

            case 'ipv6':
                $return = $this->validateIPv6($item);
                break;

            default:
                throw new Mumsys_Variable_Manager_Exception(sprintf('Type "%1$s" not implemented', $type));
        }

        if ($errorKey && $errorMessage) {
            $item->setErrorMessage($errorKey, $errorMessage);
            $return = false;
        }

        return $return;
    }


    /**
     * Item validation for min and/or max item values.
     *
     * Hint: if item type is string min/max values will be testes by string
     * length. If the type is an integer it will be tested against greater/
     * lower the current value.
     *
     * @param Mumsys_Variable_Item_Interface $item
     * @return boolean True on success otherwise false
     */
    public function validateMinMax( Mumsys_Variable_Item_Interface $item )
    {
        $return = true;
        $min = $item->getMinLength();
        $max = $item->getMaxLength();

        if ( $min === null && $max === null ) {
            return $return;
        }

        $type = $item->getType();
        $value = $item->getValue();

        $errorKey = false;
        $errorMessage = false;

        switch ( $type )
        {
//            case 'array':
//                if ( isset($min) && count($value) < $min ) {
//                    $errorKey = self::MINMAX_TOO_SHORT_ARRAY;
//                    $errorMessage = sprintf(
//                        $this->_messageTemplates['MINMAX_TOO_SHORT_ARRAY'], $value, $min
//                    );
//                }
//                if ( isset($max) && count($value) > $max ) {
//                    $errorKey = self::MINMAX_TOO_LONG_ARRAY;
//                    $errorMessage = sprintf(
//                        $this->_messageTemplates['MINMAX_TOO_LONG_ARRAY'], $value, $min
//                    );
//                }
//                break;

            case 'string':
            case 'char':
            case 'varchar':
            case 'text':
            case 'tinytext':
            case 'longtext':
            case 'email':
            case 'date':
            case 'datetime':
            case 'unixtime':
                $strlen = strlen($value);
                if ( isset($min) && $strlen < $min ) {
                    $errorKey = self::MINMAX_TOO_SHORT_STR;
                    $errorMessage = sprintf($this->_messageTemplates['MINMAX_TOO_SHORT_STR'], $value, $min);
                }

                if ( isset($max) && $strlen > $max ) {
                    $errorKey = self::MINMAX_TOO_LONG_STR;
                    $errorMessage = sprintf($this->_messageTemplates['MINMAX_TOO_LONG_STR'], $value, $max, $strlen);
                }
                break;

            case 'int':
            case 'integer':
            case 'smallint':
            case 'float':
            case 'double':
            case 'numeric':
                if ( isset($min) && $value < $min ) {
                    $errorKey = self::MINMAX_TOO_SHORT_NUM;
                    $errorMessage = sprintf($this->_messageTemplates['MINMAX_TOO_SHORT_NUM'], $value, $min);
                }

                if ( isset($max) && $value > $max ) {
                    $errorKey = self::MINMAX_TOO_LONG_NUM;
                    $errorMessage = sprintf($this->_messageTemplates['MINMAX_TOO_LONG_NUM'], $value, $max);
                }
                break;

            default:
                $errorKey = self::MINMAX_TYPE_ERROR;
                $errorMessage = sprintf($this->_messageTemplates['MINMAX_TYPE_ERROR'], $type);
        }

        if ($errorKey && $errorMessage) {
            $item->setErrorMessage($errorKey, $errorMessage);
            $return = false;
        }

        return $return;
    }


    /**
     * Item validation agains regular expressions.
     *
     * @param Mumsys_Variable_Item_Interface $item Validate item object
     * @return boolean True on success or if no regex was set or false on error
     */
    public function validateRegex( Mumsys_Variable_Item_Interface $item )
    {
        $return = true;

        if ( ($expr = $item->getRegex()) && ($value = $item->getValue()) ) {
            foreach ( $expr as $regex )
            {
                $match = preg_match($regex, $value);

                $errorKey = false;
                $errorMessage = false;

                if ( $match === 0 ) {
                    $errorKey = self::REGEX_FAILURE;
                    $errorMessage = sprintf($this->_messageTemplates[self::REGEX_FAILURE], $value, $regex);
                }

                if ( $match === false ) {
                    $errorKey = self::REGEX_ERROR;
                    $errorMessage = sprintf($this->_messageTemplates[self::REGEX_ERROR], $value, $regex);
                }

                if ($errorKey && $errorMessage) {
                    $item->setErrorMessage($errorKey, $errorMessage);
                    $return = false;
                }
            }
        }

        return $return;
    }


    /**
     * Item validation for an ipv4 address.
     *
     * @todo implement checks for FILTER_FLAG_NO_PRIV_RANGE
     * (also: return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? $value : (((ip2long($value) &
     * 0xff000000) == 0x7f000000) ? FALSE : $value); and FILTER_FLAG_NO_RES_RANGE
     * this may need type attributes!?
     *
     * @param Mumsys_Variable_Item_Interface $item Validate item object
     *
     * @return boolean True on success or false for invalid ip format
     */
    public function validateIPv4( Mumsys_Variable_Item_Interface $item )
    {
        if ( filter_var($item->getValue(), FILTER_VALIDATE_IP , FILTER_FLAG_IPV4) === false ) {
            $message = sprintf($this->_messageTemplates[self::TYPE_INVALID_IPV4], $item->getValue());
            $item->setErrorMessage(self::TYPE_INVALID_IPV4, $message);
            return false;
        }

        return true;
    }


    /**
     * Item validation for an ipv4 address.
     *
     * @param Mumsys_Variable_Item_Interface $item Validate item object
     *
     * @return boolean True on success or false for invalid ip format
     */
    public function validateIPv6( Mumsys_Variable_Item_Interface $item )
    {
        if ( filter_var($item->getValue(), FILTER_VALIDATE_IP , FILTER_FLAG_IPV6) === false ) {
            $message = sprintf($this->_messageTemplates[self::TYPE_INVALID_IPV6], $item->getValue());
            $item->setErrorMessage(self::TYPE_INVALID_IPV6, $message);
            return false;
        }

        return true;
    }


    /**
     * Checks the variable item to be valid.
     *
     * Returns true if and only if $value matches the requirements.
     *
     * If $value fails the validation tests false will return and
     * getErrorMessages() contains a list of reasons to explain what was wrong.
     *
     * @param Mumsys_Variable_Item_Interface $item Variable item
     *
     * @return boolean Returns true on success otherwise false
     */
    public function isValid( Mumsys_Variable_Item_Interface $item )
    {
        $status = true;
        $value = $item->getValue();
        $allowEmpty = $item->getAllowEmpty();
        $required = $item->getRequired();

        if (
            ( $required == false && $allowEmpty === true && empty($value)) ||
            ( $required == true && $allowEmpty === true && $value !== null && empty($value) )
        ) {
            return true;
        }

        if ( $value === null && ( $required || ($allowEmpty === false) ) ) {
            if ( $required ) {
                $item->setErrorMessage(self::REQUIRED_MISSING, $this->_messageTemplates['REQUIRED_MISSING']);
            } else {
                $item->setErrorMessage(self::ALLOWEMPTY_ERROR, $this->_messageTemplates['ALLOWEMPTY_ERROR']);
            }
            $status = false;
        }

        if ( !$this->validateType($item) ) {
            $status = false;
        }

        if ( !$this->validateMinMax($item) ) {
            $status = false;
        }

        if ( !$this->validateRegex($item) ) {
            $status = false;
        }

        $item->setValidated( $status );

        return $status;
    }


    /**
     * Returns all variable items.
     *
     * @return array List of key/variable items implementing
     * Mumsys_Variable_Item_Interface where key is the identifier of the item/
     * variable
     */
    public function getItems()
    {
        return $this->_items;
    }


    /**
     * Returns a variable item by given key.
     *
     * @param string $key Key/ identifier of the variable item
     * @return Mumsys_Variable_Item_Interface|false Variable item or false
     */
    public function getItem( $key )
    {
        if ( isset($this->_items[$key]) ) {
            return $this->_items[$key];
        }

        return false;
    }


    /**
     * Register a variable item object.
     *
     * @param string $key Key/ identifier of the item
     * @param Mumsys_Variable_Item_Interface $item Variable item object
     *
     * @throws Mumsys_Variable_Manager_Exception If key already exists
     */
    public function registerItem( $key, Mumsys_Variable_Item_Interface $item )
    {
        if ( !isset($this->_items[$key]) ) {
            $this->_items[$key] = $item;
        } else {
            throw new Mumsys_Variable_Manager_Exception(sprintf('Item "%1$s" already set', $key));
        }
    }


    /**
     * Creates a new variable item object.
     *
     * @see Mumsys_Variable_Item_Default
     *
     * @param array $properties List of key/value pairs to initialize the variable item object
     *
     * @return Mumsys_Variable_Item_Interface
     */
    public function createItem( array $properties = array() )
    {
        return new Mumsys_Variable_Item_Default($properties);
    }


    /**
     * Returns the list of all error messages from all variable items.
     *
     * Identified by the item key/ID as array index. E.g:
     * array('variablename' => array('errorID' => 'errorMessage', ...);
     *
     * @return array Returns the list of errors or empty array for no errors
     */
    public function getErrorMessages()
    {
        $messages = array();

        foreach ( $this->_items as $key => $item ) {
            if ( ($errors = $item->getErrorMessages() ) ) {
                $messages[$key] = $errors;
            }
        }

        return $messages;
    }


    /**
     * Returns the message templates.
     *
     * @return array List of error message templates
     */
    public function getMessageTemplates()
    {
        return $this->_messageTemplates;
    }


    /**
     * Set/ replaces the message templates.
     *
     * Hint: The key is the message identifier to easyly find it where the
     * massage can vari depending on the values.
     *
     * @param array $templates List of key/value pairs for the message templates.
     */
    public function setMessageTemplates( array $templates )
    {
        $this->_messageTemplates = $templates;
    }


    /**
     * Sets/ replaces a message template by given key, and value.
     *
     * @param string $key Message key/ID
     * @param string $value The message
     */
    public function setMessageTemplate( $key, $value )
    {
        $this->_messageTemplates[(string) $key] = (string) $value;
    }


    /**
     * Sets attributes/ propertys to all or selected items.
     *
     * You can set attributes like the value or state for all items like
     *      array('value' => 'this value in all items')
     *      array('state' => 'onSave')
     *
     * Additional and possible attributes to set in $attr are: "values" and
     * "labels" where you can set some of the items and set an individual value.
     * E.g: Item A gets value 1, item B gets value 2, item C gets value 3:
     * <pre>
     *  // for some
     *  $attr = array('values' => array(a => '1', b => 2, c => 3 );
     *  // dont work!
     *  $attr = array('values' => 'new value');
     *  // this works for all
     *  $attr = array('value' => 'new value');
     * </pre>
     * Hint:
     *  - "value" for all items managed in this manager.
     *  - "values" must include a list of key/value pairs to set specific item values.
     *
     * @param array $attr List of key->value pairs to be set
     *
     * @throw Mumsys_Variable_Manager_Exception If attribute setter not implemented
     */
    public function setAttributes( array $attr = array() )
    {
        foreach ( $attr AS $fieldKey => $value )
        {
            foreach ( $this->_items as $item )
            {
                $fieldName = $item->getName();

                switch ( $fieldKey )
                {
                    // all items
                    case 'value':
                        $item->setValue( $value );
                        break;

                    // some if given or none
                    case 'values':
                        if ( isset( $value[$fieldName] ) ) {
                            $item->setValue( $value[$fieldName] );
                        }
                        break;
                    // some if given or none
                    case 'labels':
                        if ( isset( $value[$fieldName] ) ) {
                            $item->setLabel( $value[$fieldName] );
                        }
                        break;

                    case 'state':
                        $item->stateSet( $value );
                        break;

                    default:
                        $msg = sprintf( 'Set item attributes for "%1$s" not implemented.', $fieldKey );
                        throw new Mumsys_Variable_Manager_Exception( $msg );
                        break;
                }
            }
        }
    }


    /**
     * Returns the list of key/value pairs for all items.
     *
     * @param boolean $byAddress If true the item address will be used as key
     * otherwise the item name property (default)
     *
     * @return array List of key/value pairs
     */
    public function toArray($byAddress=false)
    {
        $list = array();
        foreach ( $this->_items as $address => $item ) {
            if ($byAddress) {
                $list[ $address ] = $item->getValue();
            } else {
                $list[ $item->getName() ] = $item->getValue();
            }
        }

        return $list;
    }


    /**
     * Apply filters and callbacks in this order.
     *
     * You may want to use a different order of validate() filter*() and
     * callback*() of items. This applys only filtersApply() and if successful
     * callbacksApply() and returns the status
     *
     * @return boolean Status, true for success otherwise false
     */
    public function externalsApply()
    {
        $status = false;

        if ( $this->filtersApply() === true && $this->callbacksApply() === true) {
            $status = true;
        }

        return $status;
    }


    /**
     * Apply/ execute all filters of the current state.
     *
     * @return boolean Returns true on success or false on failure
     */
    public function filtersApply()
    {
        $status = true;

        foreach ( $this->_items as $item ) {
            $this->filterItem( $item );
            if ($item->isValid() === false) {
                $status = false;
            }
        }

        return $status;
    }


    /**
     * Apply filters of the given item.
     *
     * @param Mumsys_Variable_Item_Interface $item Validate item
     *
     * @return boolean Returns true on success or false on failure
     */
    public function filterItem( Mumsys_Variable_Item_Interface $item )
    {
        $filters = $item->filtersGet(true);
        $state = $item->stateGet();
        $status = true;

        if ( empty( $filters[$state] ) ) {
            $item->setValidated($status);
            return $status;
        }

        $value = $item->getValue();
        $itemName = ( ( $a = $item->getLabel() ) ? $a : $item->getName() );

        $toReplace = $params = $x = null;

        $_filters = $filters[$state];

        foreach ( $_filters as $opts )
        {
            $parameters = $opts['params'];
            $cmd = $opts['cmd'];

            if ( is_callable( $cmd ) )
            {
                if ( $parameters !== null )
                {
                    if ( is_array( $parameters ) )
                    {
                        $params = array();
                        foreach ( $parameters as $tmp => &$toReplace ) {
                            if ( $toReplace === '%value%' ) {
                                $params[$tmp] = $value;
                            } else {
                                $params[$tmp] = $toReplace;
                            }
                        }

                        // $x = $this->_execExternal($cmd, $params, 'array');
                        $x = call_user_func_array($cmd, $params);

                    } else {
                        if ( $parameters === '%value%' ) {
                            $params = $value;
                        } else {
                            $params = $parameters;
                        }

                        $x = $this->_execExternal($cmd, $params);
                    }
                } else {
                    $x = $this->_execExternal($cmd, $value);
                }


                if ( $x === false ) {
                    $status = false;
                    /* false as return or false of the callback ?
                     * boolean values should not be filtered! */
                    $message = sprintf(
                        $this->_messageTemplates['FILTER_ERROR'], $cmd, $itemName
                    );
                    $item->setErrorMessage( self::FILTER_ERROR, $message );

                } else {
                    $item->setValue( $x );
                    $value = $x;
                }

            } else {
                $status = false;
                $message = sprintf(
                    $this->_messageTemplates['FILTER_NOTFOUND'], $cmd, $itemName
                );
                $item->setErrorMessage( self::FILTER_NOTFOUND, $message );
            }
        }

        $item->setValidated($status);

        return $status;
    }


    /**
     * Apply/ execute all callbacks.
     *
     * @param mixed Mixed data to pipe to the callback function
     *
     * @return boolean Returns true on success or false on failure
     */
    public function callbacksApply($data=null)
    {
        $status = true;

        foreach ( $this->_items as $item ) {
            $this->callbackItem( $item, $data );
            if ($item->isValid() === false) {
                $status = false;
            }
        }

        return $status;
    }


    /**
     * Apply callbacks of the given item based on the current state.
     *
     * @todo toggle $data and $params in function call! $data is in less use (mor individual) ???
     *
     * Callback function signature is:
     * functionName(Mumsys_Variable_Item_Interface object, mixed $dataFromExtenrnalCallerFunc=null,
     * array optionalParams=null);
     *
     * @param Mumsys_Variable_Item_Interface $item Validate item
     * @param mixed Mixed data to pipe to the callback function
     *
     * @return boolean Returns true on success or false on failure
     */
    public function callbackItem( Mumsys_Variable_Item_Interface $item, $data = null )
    {
        $callbacks = $item->callbacksGet( true );
        $state = $item->stateGet();
        $status = true;

        if ( empty( $callbacks[$state] ) ) {
            $item->setValidated( $status );
            return $status;
        }

        $value = $item->getValue();
        $itemName = ( ( $a = $item->getLabel() ) ? $a : $item->getName() );

        $_callbacks = $callbacks[$state];

        foreach ( $_callbacks as $opts )
        {
            $parameters = $opts['params'];
            $cmd = $opts['cmd'];

            if ( is_callable( $cmd ) )
            {
                if ( $parameters !== null )
                {
                    if ( is_array( $parameters ) )
                    {
                        $params = array();
                        foreach ( $parameters as $tmp => &$toReplace ) {
                            if ( $toReplace === '%value%' ) {
                                $params[$tmp] = $value;
                            } else {
                                $params[$tmp] = $toReplace;
                            }
                        }

                        $x = call_user_func( $cmd, $item, $data, $params );
                    } else {
                        if ( $parameters === '%value%' ) {
                            $params = $value;
                        } else {
                            $params = $parameters;
                        }

                        $x = call_user_func( $cmd, $item, $data, $params );
                    }
                } else {
                    $x = call_user_func( $cmd, $item, $data );
                }

                if ( $x === false ) {
                    $status = false;
                    /* false as return or false of the callback ?
                     * boolean values should not be filtered! */
                    $message = sprintf(
                        $this->_messageTemplates['CALLBACK_ERROR'], $cmd, $itemName, (is_array($value)?print_r($value, true):$value)
                    );
                    $item->setErrorMessage( self::CALLBACK_ERROR, $message );
                } else {
                    $item->setValue( $x );
                    $value = $x;
                }
            } else {
                $status = false;
                $message = sprintf(
                    $this->_messageTemplates['CALLBACK_NOTFOUND'], $cmd, $itemName
                );
                $item->setErrorMessage( self::CALLBACK_NOTFOUND, $message );
            }
        }

        $item->setValidated( $status );

        return $status;
    }


    /**
     * Execute and return external filter or callback result.
     *
     * @param string $cmd Funtion/method to be called
     * @param string|array $params Parameters to pipe to the function
     * @param string $ptype Type of the parameters empty string|string|array
     *
     * @return mixed|false Returns to value of the callback or false on errors
     */
    private function _execExternal($cmd, $params, $ptype='string')
    {
        /* future for callbacks:
        if ($ptype=='array') {
            return call_user_func_array($cmd, $params);
        }*/

        $value = false;

        /* switches to improve performance */
        switch($cmd)
        {
            case 'trim':
                $value = trim($params);
                break;

            case 'htmlspecialchars':
                $value = htmlspecialchars($params);
                break;

            case 'htmlentities':
                $value = htmlentities($params);
                break;

            default:
                $value = call_user_func( $cmd, $params );
        }

        return $value;
    }

}
