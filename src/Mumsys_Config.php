<?php

/* {{{ */
/**
 * @deprecated since version 1.0.1 Use Mumsys_Config_Default from now on
 * ----------------------------------------------------------------------------
 * Mumsys_Config
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2009 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 * @version     1.0.1
 * Created: 2009-11-29
 */
/* }}} */


/**
 * Mumsys configuration.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 */
class Mumsys_Config
    extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.0.1';

    /**
     * Configuration vars in an array container.
     * @var array
     */
    private $_config = array();

    /**
     * Context item which must be available for all mumsys objects
     * @var Mumsys_Context
     */
    private $_context = array();


    /**
     * Initialize the config object.
     *
     * @param Mumsys_Context $context Context object
     * @param array $config Config parameter to be set
     * @throws Mumsys_Config_Exception
     */
    public function __construct( Mumsys_Context $context, array $config = array() )
    {
        $this->_context = $context;
        $this->_config = $config;
    }


    /**
     * Get a list of or a simgle config parameter.
     *
     * @param array|string $key A key or list of keys to return
     * @param mixed|null $default Default value to return if key does not exists
     *
     * @return array List of key/value pairs of requested keys. If the key does
     * not exists the $default will return for that key.
     */
    public function get( $key, $default = null )
    {
        $return = array();

        if (!is_array($key)) {
            $key = array($key);
        }

        foreach ($key as $k) {
            if (isset($this->_config[$k])) {
                $return[$k] = $this->_config[$k];
            } else {
                $return[$k] = $default;
            }
        }

        return $return;
    }


    /**
     * Get all config parameters
     *
     * @return array
     */
    public function getAll()
    {
        return $this->_config;
    }


    /**
     * Returns config values by given depth and values you want from the depth.
     *
     * Example: You want values from database -> configs -> a and c
     * <pre>
     * $config = array(
     *  'database'=> array(
     *      'configs'=> array(
     *          'a'=> array('some props'),
     *          'b'=> array('some props'),
     *          'c'=> array('some props') )));
     * $dept = array('database', 'configs');
     * $keys = array('a', 'c');
     * // returns: array('a'=> array('some props'), 'c'=> array('some props'));
     * </pre>
     *
     * @param array $depth List of values to discribe the depth/path to the value
     * @param array $keys List of array keys you want to get from given depth
     * @param mixed|false $default Optional; Default to return if values not found
     * 
     * @return array List of key/values pairs with values which were found or false.
     */
    public function getSubValues( array $depth = array(), array $keys = array(), $default=false )
    {
        $result = false;
        $cfg = & $this->_config;


        foreach ($depth as $value) {
            if (isset($cfg[$value])) {
                $cfg = $cfg[$value];
            }
        }

        foreach ($keys as $value) {
            if (isset($cfg[$value])) {
                $result[$value] = $cfg[$value];
            }
        }

        if (!$result) {
            $result = $default;
        }


        return $result;
    }


    /**
     * Register a configuration parameter if not extsts.
     *
     * @param string $key Key-Name of the config-parameter
     * @param mixed $value Mixed value to be set.
     *
     * @throws Mumsys_Config_Exception If key exists
     */
    public function register( $key, $value = null )
    {
        if (array_key_exists($key, $this->_config)) {
            $message = sprintf('Config key "%1$s" exists', $key);
            throw new Mumsys_Config_Exception($message);
        }

        $this->_checkKey($key);
        $this->_config[$key] = $value;
    }


    /**
     * Replace/ sets a configuration parameter.
     *
     * @param string $key Key-Name of the config-parameter
     * @param mixed $value Mixed value to be set.
     */
    public function replace( $key, $value = null )
    {
        $this->_checkKey($key);
        $this->_config[$key] = $value;
    }


    /**
     * Load configuration by a given application config key and replace existing
     * values if exists.
     *
     * @uses Mumsys_Db_Interface Database interface will be used
     *
     * @param string $key config-application key to load
     * @return array Returns all configuration parameters
     *
     * @throws Mumsys_Config_Exception If key already registered
     */
    public function load( $appKey = 'mumsys' )
    {
        throw new Mumsys_Config_Exception('exit in: ' . __FILE__ . ':' . __LINE__);


        $oDB = $this->_context->getDatabase();

        $this->get('configs/database/mumsys/config/get');
        echo $sql = sprintf(
        'SELECT config_key, config_val, config_type FROM %1$s%2$s WHERE config_app = \'%3$s\'',
        $this->_config['table_prefix'], $this->_config['table_config'], $appKey
        );

        $oRes = $oDB->query($sql);

        if ($oDB->isError($oRes)) {
            throw new Mumsys_Config_Exception($oDB->getErrorMessage());
        }

        while (list($key, $val, $type) = $oRes->fetch('ROW')) {
            $key = trim($key);
            $val = trim($val);
            $result = null;

            $msgTmpl = 'Config for "%1$s" exists for type: "%2$s", key: "%3$s" (value: "%4$s")';


            if ($type != 'CONSTANT' && isset($this->_config[$key])) {
                $message = sprintf($msgTmpl, $appKey, $type, $key, substr($val, 0, 15));
                throw new Mumsys_Config_Exception($message);
            }

            switch ($type)
            {
                case 'BOOL':
                    if (empty($val) || $val == 'false') {
                        $result = false;
                    } else {
                        $result = true;
                    }
                    $this->_config[$key] = $result;
                    break;

                case 'DECIMAL':
                    $this->_config[$key] = (int)$val;
                    break;

                case 'DOUBLE':
                    $this->_config[$key] = (float)$val;
                    break;

                case 'FUNCTION': //closures?
                    $result = null;
                    break;

                case 'CONSTANT':
                    if (defined($key)) {
                        $message = sprintf($msgTmpl, $appKey, $type, $key, substr($val, 0, 15));
                        throw new Mumsys_Config_Exception($message);
                    }
                    define($key, $val);
                    break;

                case 'SERIALIZED':
                    $this->_config[$key] = unserialize($val);

                    break;

                case 'JSON':
                    $this->_config[$key] = json_decode($val);
                    break;

                case 'VARIABLE':
                default:
                    $this->_config[$key] = (string)$val;
                    break;
            }
        }
        $oRes->free();

        return $this->_config;
    }

}