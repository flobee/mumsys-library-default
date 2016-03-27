<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Config_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2009 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Config
 * Created: 2009-11-29
 */
/*}}}*/


/**
 * Mumsys config class 2.0.
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
     * Configuration vars in an array container.
     * @var array
     */
    private $_config = array();

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
    public function __construct( Mumsys_Context $context, array $config = array(),
        array $paths = array() )
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
     * Get all config parameters
     *
     * @return array
     */
    public function getAll()
    {
        return $this->_configs;
    }


    /**
     * Adds/ registers config parameters to the current state if possible.
     *
     * @param array $config Configuration parameters to register
     * @throws Mumsys_Config_Exception On errors or if a config already exists
     */
//    public function add(array $config = array())
//    {
//        foreach ($config as $key => & $value) {
//            $this->register($key, $value);
//        }
//    }


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
        throw new Mumsys_Config_Exception('exit in: ' . basename(__FILE__) . ':' . __LINE__);
    }

}
