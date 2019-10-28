<?php
//declare(strict_types=1);

/**
 * Mumsys_Service_Ssh_Config_Generator_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2018 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Abstract
 * created: 2018-02-10
 */


/**
 * SSH config generator.
 *
 * Uses config files, each per host, to improve rollouts to other machines.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 */
class Mumsys_Service_Ssh_Config_Generator_Default
    extends Mumsys_Abstract
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Location to the target ssh config file to save the content to
     * @var string
     */
    private $_sshConfFile;

    /**
     * Location to the path where configs exists to create the ssh config
     * @var string
     */
    private $_confsPath;

    /**
     * Mode to set the file permission in octal, eg: 0600
     * @var octal
     */
    private $_sshConfFileMode = 0600;


    /**
     * Initialize the object
     */
    public function __construct()
    {
        $_home = '';
        if ( isset( $_SERVER['HOME'] ) && ( $_home = $_SERVER['HOME'] ) ) {
            $_home = $this->_checkPath( $_home );
        }

        $this->_sshConfFile = $_home . '/.ssh/config-generated';
        $this->_confsPath = $_home . '/.ssh/conffiles/';
    }


    /**
     * Sets the location to the path where config files exists to create the
     * ssh config file.
     *
     * @param string $path Location to conffiles path
     */
    public function setConfigsPath( string $path )
    {
        $this->_confsPath = $path;
    }


    /**
     * Sets the location to the ssh config file to write to.
     *
     * @param string $file Location to the ssh config file
     *
     * @throws Mumsys_Service_Exception If path of the file not exists or
     * target is not writeable
     */
    public function setFile( string $file )
    {
        $this->_checkPath( dirname( $file ) );
        $this->_sshConfFile = $file;
    }


    /**
     * Sets permission mode of the ssh config file to write to.
     *
     * @param int $mode Mode to change the permission in octal (not integer!
     * octal has no php internal type)
     */
    public function setMode( $mode )
    {
        $this->_sshConfFileMode = $mode;
    }


    /**
     * Generates the ssh config file.
     */
    public function run()
    {
        $list = scandir( $this->_confsPath );
        natcasesort( $list );
        $string = '';
        foreach ( $list as $file ) {
            if ( $file[0] === '.' ) {
                continue;
            }

            $location = $this->_confsPath . '/' . $file;
            if ( !is_readable( $location ) ) {
                $message = sprintf(
                    'Config file not found or wrong permission "%1$s"', $location
                );
                throw new Mumsys_Service_Exception( $message );
            }

            $string .= $this->_configToString( include $location );
        }

        file_put_contents( $this->_sshConfFile, $string );
        chmod( $this->_sshConfFile, $this->_sshConfFileMode );
    }


    /**
     * Returns the config content as string.
     *
     * @param array $config List of key/value pairs representinga line of a ssh
     * config
     *
     * @return string Config string to be added to the target
     */
    private function _configToString( array $config ): string
    {
        $string = '';
        foreach ( $config as $key => $value ) {
            $numeric = is_numeric( $key );
            if ( $numeric ) {
                $string .= $value . "\n";
            } else {
                $string .= $key . ' ' . $value . "\n";
            }
        }

        return $string . "\n";
    }


    /**
     * Check the path for the ssh config file.
     *
     * @param string $path Returns
     * @throws Mumsys_Service_Exception
     */
    private function _checkPath( string $path ): string
    {
        $targetPath = filter_var( $path, FILTER_SANITIZE_STRING );

        if ( !is_dir( $targetPath . DIRECTORY_SEPARATOR ) ) {
            $message = sprintf( 'Path does not exists "%1$s"', $targetPath );
            throw new Mumsys_Service_Exception( $message );
        }

        if ( !is_writable( $targetPath . DIRECTORY_SEPARATOR ) ) {
            $message = sprintf( 'Path not writable "%1$s"', $targetPath );
            throw new Mumsys_Service_Exception( $message );
        }

        return $targetPath;
    }

}
