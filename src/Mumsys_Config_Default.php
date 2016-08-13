<?php

/**
 * Mumsys_Config_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2009 by Florian Blasel for FloWorks Company
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 */


/**
 * Mumsys config default class
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 */
class Mumsys_Config_Default
    extends Mumsys_Abstract
    implements Mumsys_Config_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '2.0.0';

    /**
     * Initial configuration vars in an array container.
     * @var array
     */
    private $_configs = array();

    /**
     * Context item which must be available for all mumsys objects
     * @var Mumsys_Context
     */
    protected $_context = array();

    /**
     * List of file locations containing config parameters.
     * @var array
     */
    private $_paths = array();

    /**
     * Buffer of config file inclusions.
     * @var array
     */
    private $_incCache = array();


    /**
     * Initialize the config object.
     *
     * @param Mumsys_Context $context Context object
     * @param array $config Config parameters to be set
     * @param array $paths List of locations for config files
     */
    public function __construct( Mumsys_Context $context, array $config = array(), array $paths = array() )
    {
        $this->_context = $context;
        $this->_configs = $config;
        $this->_paths = $paths;
    }


    /**
     * Get config parameter/s by given path.
     * The path can be a string like: frontend/plugins/jquery-ui or an array
     * definig the path: array('frontend','plugins', 'jquery-ui')
     *
     * @param string|array $key Path to the config to get config value/s from
     * e.g. frontend/pageTitle or array('frontend', 'pageTitle)
     * @param mixed|null $default Expectd value or the default value to return
     * if key does not exists
     *
     * @return array Value/s of the requested key or the default will return.
     */
    public function get( $key, $default = null )
    {
        if (is_array($key)) {
            $parts = $key; // old getsubValues() feature
        } else {
            $parts = explode( '/', trim( $key, '/' ) );
        }

		if( ( $value = $this->_get( $this->_configs, $parts ) ) !== null ) {
			return $value;
		}

		foreach( $this->_paths as $path ) {
			$this->_configs = $this->_load( $this->_configs, $path, $parts );
		}

		if( ( $value = $this->_get( $this->_configs, $parts ) ) !== null ) {
			return $value;
		}

		return $default;
    }


    /**
     * Get all config parameters which are used or loaded at this moment
     *
     * @return array Config parameters
     */
    public function getAll()
    {
        return $this->_configs;
    }


    /**
     * Register a configuration parameter if not exists.
     *
     * @param string $key Path including the key to be registered. E.g.: frontend/pageTitle
     * @param mixed $value Mixed value to be set.
     *
     * @throws Mumsys_Config_Exception If key exists
     */
    public function register( $key, $value = null )
    {
        if ( ($test = $this->get($key)) !== null) {
            $message = sprintf('Config key "%1$s" already exists', $key);
            throw new Mumsys_Config_Exception($message);
        }

        $this->replace($key, $value);
    }


    /**
     * Replace/ sets a config parameter.
     *
     * @param string $key Path to the value e.g: frontend/pageTitle
	 * @param mixed $value Value to be set
     */
    public function replace( $key, $value = null )
    {
        $parts = explode('/', trim($key, '/'));
        $this->_configs = $this->_replace($this->_configs, $parts, $value);
    }


    /**
	 * Returns a config value.
	 *
	 * @param array $config Config array
	 * @param array $parts List of sub paths to look for
     *
	 * @return mixed Config value/s or null config does not exists
	 */
	protected function _get( $config,  $parts )
	{
		if ( ( $cur = array_shift($parts) ) !== NULL && isset($config[$cur])) {
            if (count($parts) > 0) {
                return $this->_get($config[$cur], $parts);
            }

            return $config[$cur];
        }

        return null;
    }


    /**
     * Load the config file if possible.
     *
     * @todo check database usage or a mix of cofig files and db
     *
     * @param array $config Current global config
     * @param string $curPath Path of the config file
     * @param array $parts List of config parts to look for
     *
     * @return array The merged config
     */
    protected function _load( array $config, $curPath, array $parts )
    {

        if (( $key = array_shift($parts) ) !== NULL) {
            $newPath = $curPath . DIRECTORY_SEPARATOR . $key;

            if (is_dir($newPath)) {
                if (!isset($config[$key])) {
                    $config[$key] = array();
                }

                $config[$key] = $this->_load($config[$key], $newPath, $parts);
            }

            if (file_exists($newPath . '.php')) {
                if (!isset($config[$key])) {
                    $config[$key] = array();
                }

                $config[$key] = $this->_merge($config[$key], $this->_include($newPath . '.php'));
            }
        }

        return $config;
    }


    /**
     * Merges the configs, leaves existing key.
     *
     * @param array $left Array to be merged into
     * @param array $right Array to be merged
     */
    protected function _merge( array $left, array $right )
    {
        foreach ($right as $key => $value) {
            if (isset($left[$key]) && is_array($left[$key]) && is_array($value)) {
                $left[$key] = $this->_merge($left[$key], $value);
            } else {
                $left[$key] = $value;
            }
        }

        return $left;
    }


    /**
     * Include a config file.
     *
     * @param string $file Location of the config file
     *
     * @return array Configs of the requested file
     * */
    protected function _include( $file )
    {
        if (!isset($this->_incCache[$file])) {
            $this->_incCache[$file] = include $file;
        }

        return $this->_incCache[$file];
    }


    /**
	 * Replaces/ sets a given config to the existing config.
	 *
     * @todo array $path in func signature?
     *
	 * @param array $config Configuration sub-part
	 * @param array $path Parts of the path
	 * @param array $value The value to be set
	 */
	protected function _replace( $config, $path, $value )
	{
		if( ( $current = array_shift( $path ) ) !== NULL )
		{
			if( isset( $config[$current] ) ) {
				$config[$current] = $this->_replace( $config[$current], $path, $value );
			} else {
				$config[$current] = $this->_replace( array(), $path, $value );
			}

			return $config;
		}

		return $value;
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
        throw new Mumsys_Config_Exception('exit in: ' . basename(__FILE__) . '');


        $oDB = $this->_context->getDatabase();

        $sql = $this->get('database/mumsys/config/get');
/*        echo $sql = sprintf(
            'SELECT config_key, config_val, config_type FROM %1$s%2$s WHERE config_app = \'%3$s\'',
            $this->_config['table_prefix'],
            $this->_config['table_config'],
            $appKey
        );
        ...
*/

        while ( list($key, $val, $type) = $oRes->fetch('ROW') )
        {
            $key = trim($key);
            $val = trim($val);
            $result = null;

            $msgTmpl = 'Config for "%1$s" exists for type: "%2$s", key: "%3$s" (value: "%4$s")';

            if ( $type != 'CONSTANT' && isset($this->_configs[$key])) {
                $message = sprintf($msgTmpl, $appKey, $type, $key, substr($val, 0, 15));
                throw new Mumsys_Config_Exception($message);
            }

            switch ( $type )
            {
                case 'BOOL':
                    if ( empty($val) || $val == 'false' ) {
                        $result = false;
                    } else {
                        $result = true;
                    }
                    $this->register($key, $result);
                    break;

                case 'DECIMAL':
                    $this->register($key, (int)$val);
                    break;

                case 'DOUBLE':
                    $this->register($key, (float)$val);
                    break;

                case 'FUNCTION': //closures?
                    throw new Mumsys_Config_Exception('not implemented yet');
                    break;

                case 'CONSTANT':
                    if ( defined($key) ) {
                        $message = sprintf($msgTmpl, $appKey, $type, $key, substr($val,0,15));
                        throw new Mumsys_Config_Exception($message);
                    }
                    define($key, $val);
                    break;

                case 'SERIALIZED':
                    $this->register($key, unserialize($val));
                    break;

                case 'JSON':
                    $this->register($key, json_decode($val));
                    break;

                case 'VARIABLE':
                case 'STRING':
                default:
                    $this->register($key, $val);
                    break;
            }
        }
        $oRes->free();

        return $this->_configs;
    }

}
