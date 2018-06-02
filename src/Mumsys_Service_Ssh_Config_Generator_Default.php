<?php
declare(strict_types=1);

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
 * created: 2018-05-10
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
     * List of config files as host/configs
     * @var array
     */
    private $_configs = array();

    /**
     * Location to the global identity file
     * @var string
     */
    private $_globalIdenittyFile = '';

    /**
     * Location of the $HOME directory.
     * @var string
     */
    private $_home;


    /**
     * Initialize the object
     *
     * @param string $configsPath optional path of configs for this program.
     * Default: ~/.ssh/conffiles
     * @param string $outFile Optional location to the config file to be created.
     * Default $HOME/.ssh/ssh-config-generated.
     *
     * @throws Exception If config path invalid or not running in cli mode
     * @throws Mumsys_Service_Exception
     */
    public function __construct( string $configsPath = null, string $outFile = null )
    {
        $this->_home = './';
        if ( isset( $_SERVER['HOME'] ) && ($_home = (string) $_SERVER['HOME'] ) ) {
            $this->_home = $this->_checkPath( $_home );
        }

        if ( $outFile ) {
            if ( is_string( $outFile )
                && is_dir( dirname( $outFile ) . DIRECTORY_SEPARATOR ) ) {

                $genCfgFile = $outFile;
            } else {
                $mesg = sprintf(
                    'Given config file path not found: "%1$s"', $outFile
                );
                throw new Mumsys_Service_Exception( $mesg );
            }
        } else {
            $genCfgFile = $this->_home . '/.ssh/ssh-config-generated';
        }

        $this->setConfigFile( $genCfgFile );
        $this->setConfigsPath( $configsPath );
    }


    /**
     * Initialize the object to be prepeard to run actions.
     *
     * You may use setConfigFile(), setConfigsPath() at a later time then init()
     * must be called befor run create|register|revoke|deploy action.
     */
    public function init(): void
    {
        $this->_loadConfigs();
    }


    /**
     * Sets the location to the path where config files exists to create the
     * ssh config file.
     *
     * @param string $path Location to conffiles path (default: ~/.ssh/conffiles)
     */
    public function setConfigsPath( string $path = null )
    {
        if ( !$path ) {
            $path = $this->_home . '/.ssh/conffiles';
        }

        if ($path[0] == '~') {
            $path = str_replace( '~', $this->_home, $path );
        }

        if ( $path && is_dir( $path . DIRECTORY_SEPARATOR ) ) {
            $this->_confsPath = (string) $path;
        } else {
            $mesg = sprintf( 'Configs paths not found "%1$s"', $path );
            throw new Mumsys_Service_Exception( $mesg );
        }

        $this->_globalIdenittyFile = include $this->_confsPath . '/../global/identityFile.php';
    }


    /**
     * Sets the location to the ssh config file to write to.
     *
     * @param string $file Location to the ssh config file
     *
     * @throws Mumsys_Service_Exception If path of the file not exists or
     * target is not writeable
     */
    public function setConfigFile( string $file )
    {
        $this->_checkPath( dirname( $file ) );
        $this->_sshConfFile = $file;
    }


    /**
     * Sets permission mode of the ssh config file to write to.
     *
     * @param octal $mode Mode to change the permission in octal
     */
    public function setMode( int $mode = 0600 )
    {
        $this->_sshConfFileMode = $mode;
    }


    /**
     * Generates the ssh config file.
     *
     * If you want to revoke and/or register new keys you should do that first
     * and then create the new ssh config. Otherwise you may can not connect to
     * the target anymore because the new config was set but wasnt deployed
     * before and you end up in connection failures.
     *
     * @param boolean $justOutput Flag to just output the results.
     */
    public function create( bool $justOutput = false ): void
    {
        $string = '';
        foreach ( $this->_configs as $cfg ) {
            $identity = $this->_getIdentityLocation( $cfg['config'] );
            if ( $identity ) {
                $cfg['config']['IdentityFile'] = $identity;
            }

            $string .= $this->_configToString( $cfg['config'] );
        }

        if ( $justOutput ) {
            echo '# output for: ' . $this->_sshConfFile . PHP_EOL;
            echo $string . PHP_EOL;
        } else {
            file_put_contents( $this->_sshConfFile, $string );
            chmod( $this->_sshConfFile, $this->_sshConfFileMode );
        }
    }


//    /**
//     * Deploy/publish key files based on given config.
//     *
//     * It outputs scp commands you may run or pipe them to shell for execution.
//     */
//    public function deploy()
//    {
//        foreach ( $this->_configs as $host => $cfg ) {
//            if ( isset( $cfg['deploy'] ) && $cfg['deploy'] ) {
//
//                foreach ( $cfg['deploy'] as $targetHost => $listIdFiles ) {
//
//                    $configList = array();
//
//                    foreach ( $listIdFiles as $idSrc => $idTarget ) {
//                        if ( is_numeric( $idSrc ) ) {
//                            switch ( $idTarget )
//                            {
//                                case '*':
//                                    $idSrc = dirname($this->_globalIdenittyFile) .'/*';
//                                    $configList[$idSrc] = dirname($this->_globalIdenittyFile);//$idTarget
//                                    break;
//
//                                case 'IdentityFile':
//                                    /** @TODO */
////                                    if ($cfg['config']['IdentityFile'] === true) {
////
////                                    }
//                                    $configList[$this->_globalIdenittyFile] = $this->_globalIdenittyFile;
//                                    $configList[$this->_globalIdenittyFile . '.pub'] = $this->_globalIdenittyFile . '.pub';
//                                    break;
//
//                                default:
//                                    $configList[$idTarget] = $idTarget;
//                                    break;
//                            }
//                        } else {
//
//                            if ($idSrc == '*') {
//                                $idSrc = dirname($this->_globalIdenittyFile) .'/*';
//                            }
//
//                            $addPub = false;
//                            if ( $idSrc == 'IdentityFile' ) {
//                                $idSrc = $this->_globalIdenittyFile;
//                                $addPub = true;
//                            }
//
//                            if ( $idTarget == 'IdentityFile' ) {
//                                $idTarget = $this->_globalIdenittyFile;
//                                $addPub = true;
//                            }
//
//                            $configList[$idSrc] = $idTarget;
//
//                            if ( $addPub ) {
//                                $configList[$idSrc . '.pub'] = $idTarget . '.pub';
//                            }
//                        }
//                    }
//
//                    if ( isset( $cfg['config']['User'] ) ) {
//                        $targetUser = $cfg['config']['User'];
//                    } else {
//                        $targetUser = $_SERVER['USER'];
//                    }
//
//                    $this->_deployExecute( $configList, $targetUser, $targetHost );
//                }
//            }
//        }
//    }
//
//
//    private function _deployExecute( array $configList, $user,
//        $targetHost )
//    {
//        foreach ( $configList as $src => $target ) {
//            echo "scp $src $user@$targetHost:$target" . PHP_EOL;
//        }
//    }
//
//
//    /**
//     * Revoke a list of public/private keys at the remote server.
//     * I should also remokes the pub keys from the known hosts.
//     */
//    public function revoke()
//    {
//        foreach ( $this->_configs as $targetHost => $cfg ) {
//            if ( isset( $cfg['revoke'] ) && $cfg['revoke'] ) {
//                foreach ( $cfg['revoke'] as $file ) {
//                    $target = array();
//
//                    if ( $file == 'IdentityFile' ) {
//                        $target[0] = $this->_globalIdenittyFile;
//                        $target[1] = $this->_globalIdenittyFile . '.pub';
//                    } else {
//                        $target[] = $file;
//                    }
//
//                    if ( isset( $cfg['config']['User'] ) ) {
//                        $targetUser = $cfg['config']['User'];
//                    } else {
//                        $targetUser = $_SERVER['USER'];
//                    }
//
//                    $this->_revokeExecute( $target, $targetUser, $targetHost );
//                }
//            }
//        }
//    }
//
//
//    /**
//     * Removes target key and trys to remove pub key from authorised keys and
//     * trys to remove from known hosts.
//     *
//     * //sed -i.bak '/REGEX_MATCHING_KEY/d' ~/.ssh/authorized_keys
//      // sed -i "s#`cat ~/.ssh/my/id_rsa_fb_2018.pub`##" ~/.ssh/authorized_keys
//      // or: ssh u@h "sed -i 's#`cat ~/.ssh/my/id_rsa_fb_2018.pub`##' ~/.ssh/authorized_keys"
//     * @param array $source
//     * @param array $target
//     * @param type $user
//     * @param type $targetHost
//     */
//    private function _revokeExecute( array $target, $user, $targetHost )
//    {
//        foreach ( $target as $i => $location ) {
//            if ( substr( $location, -4 ) == '.pub' ) {
//                echo "ssh $user@$targetHost \"sed -i 's#`cat $location`##' \$HOME/.ssh/known_hosts\"" . PHP_EOL;
//                echo "ssh $user@$targetHost \"sed -i 's#`cat $location`##' \$HOME/.ssh/authorized_keys\"" . PHP_EOL;
//            }
//
//            echo "ssh $user@$targetHost \"rm  $location\"" . PHP_EOL;
//        }
//    }
//
//
//    /**
//     * Revoke a list of public/private keys at the remote server.
//     * I should also remokes the pub keys from the known hosts.
//     */
//    public function register()
//    {
//        foreach ( $this->_configs as $host => $cfg ) {
//            if ( isset( $cfg['register'] ) && $cfg['register'] ) {
//
//                foreach ( $cfg['register'] as $targetHost => $listPubFiles ) {
//
//                    $configList = array();
//
//                    if ($listPubFiles ==='*') {
//                        echo "$host: register the pub key of each config" . print_r($cfg['config'], true) . PHP_EOL;
//                        continue;
//                    }
//
//
//
//                    foreach ( $listPubFiles as $idSrc => $idTarget ) {
//                        if ( is_numeric( $idSrc ) ) {
//                            switch ( $idTarget )
//                            {
//                                case '*':
//                                    $idSrc = dirname($this->_globalIdenittyFile) .'/*';
//                                    $configList[$idSrc] = dirname($this->_globalIdenittyFile);//$idTarget
//                                    break;
//
//                                case 'IdentityFile':
//                                    /** @TODO */
////                                    if ($cfg['config']['IdentityFile'] === true) {
////
////                                    }
//                                    $configList[$this->_globalIdenittyFile] = $this->_globalIdenittyFile;
//                                    $configList[$this->_globalIdenittyFile . '.pub'] = $this->_globalIdenittyFile . '.pub';
//                                    break;
//
//                                default:
//                                    $configList[$idTarget] = $idTarget;
//                                    break;
//                            }
//                        } else {
//
//                            if ($idSrc == '*') {
//                                $idSrc = dirname($this->_globalIdenittyFile) .'/*';
//                            }
//
//                            $addPub = false;
//                            if ( $idSrc == 'IdentityFile' ) {
//                                $idSrc = $this->_globalIdenittyFile;
//                                $addPub = true;
//                            }
//
//                            if ( $idTarget == 'IdentityFile' ) {
//                                $idTarget = $this->_globalIdenittyFile;
//                                $addPub = true;
//                            }
//
//                            $configList[$idSrc] = $idTarget;
//
//                            if ( $addPub ) {
//                                $configList[$idSrc . '.pub'] = $idTarget . '.pub';
//                            }
//                        }
//                    }
//
//                    if ( isset( $cfg['config']['User'] ) ) {
//                        $targetUser = $cfg['config']['User'];
//                    } else {
//                        $targetUser = $_SERVER['USER'];
//                    }
//
//                    $this->_registerExecute( $configList, $targetUser, $targetHost );
//                }
//            }
//        }
//    }
//    // register before you revoke keys
//    private function _registerExecute( array $target, $user, $targetHost )
//    {
//        foreach ( $target as $i => $location ) {
//            if ( substr( $location, -4 ) == '.pub' ) {
//
//                // awk '!seen[$0]++' file.txt
//                // local  : awk '!seen[$0]++' ~/.ssh/authorized_keys > ~/.ssh/authorized_keys
//                // via ssh: awk '\!seen[\$0]++' ~/.ssh/authorized_keys | cat > ~/.ssh/authorized_keys
//
//                echo "cat $location | awk '{print \"#\\n# \"$3\"\\n\"$0}' | ssh $user@$targetHost \"cat >> ~/.ssh/authorized_keys && awk '\!seen[\\$0]++' ~/.ssh/authorized_keys | cat > ~/.ssh/authorized_keys\"" . PHP_EOL;
//            }
//        }
//    }

    // --- protected or private methodes ---------------------------------------

    /**
     * Returns the location of the identity file if it was set.
     *
     * @param array $config A host configuration ( $host['config'] )
     *
     * @return string Configured IdentityFile  or global IdentityFile locatio
     */
    public function _getIdentityLocation( array $config ): string
    {
        $idFile = '';
        if ( isset( $config['IdentityFile'] ) ) {
            if ($config['IdentityFile'] === true) {
                $idFile = $this->_globalIdenittyFile;
            } else {
                $idFile = $config['IdentityFile'];
            }
        }

        return $idFile;
    }


    /**
     * Returns the host config content as string.
     *
     * @param array $config List of key/value pairs representing a line of a ssh
     * config or a comment line
     *
     * @return string Config string to be added to the target
     */
    private function _configToString( array $config ): string
    {
        $string = '';

        foreach ( $config as $key => $value ) {
            if ( is_numeric( $key ) ) {
                $string .= $value . "\n";
            } else {
                $string .= $key . ' ' . $value . "\n";
            }
        }

        return $string . "\n";
    }


    /**
     * Check the path for the ssh config file if it exists and will be writeable.
     *
     * @param string $path The path to  be checked
     *
     * @return string path Existing location/path
     * @throws Mumsys_Service_Exception If path not exists or not writeable
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


    /**
     * Load the config host files.
     *
     * @return void If configs where already loaded
     * @throws Mumsys_Service_Exception On error loading a config file
     */
    private function _loadConfigs()
    {
        if ( $this->_configs ) {
            return;
        }

        $list = scandir( $this->_confsPath . DIRECTORY_SEPARATOR );
        natcasesort( $list );
        $string = '';
        foreach ( $list as $file ) {
            if ( $file[0] === '.' ) {
                continue;
            }

            $location = $this->_confsPath . '/' . $file;
            if ( !is_readable( $location ) ) {
                $mesg = sprintf(
                    'Config file not found or wrong permission "%1$s"',
                    $location
                );
                throw new Mumsys_Service_Exception( $mesg );
            }

            if ( ( $config = include $location ) === false ) {
                $mesg = sprintf(
                    'Config file could not be loaded "%1$s"', $location
                );
                throw new Mumsys_Service_Exception( $mesg );
            }

            $this->_configs[substr( $file, 0, -4 )] = $config;
        }
    }

//    private function _scanKeyFiles(string $location='~/.ssh/my/id_rsa'): array
//    {
//        $result = array();
//        $isHome = false;
//        $path = dirname($location);
//        if ( $path[0] === '~' ) {
//            $path = str_replace( '~', $this->_home, $path );
//            $isHome = true;
//        }
//
//        $list = scandir( $path );
//        foreach ( $list as $file ) {
//            if ( $file[0] === '.' ) {
//                continue;
//            }
//
//            $fileSrc = $file;
//            if ( is_dir( $path .DIRECTORY_SEPARATOR.$file ) ) {
//                $fileSrc .= '/*';
//            }
//
//            $prefix = $path;
//            if ( $isHome ) {
//                $prefix = str_replace( $this->_home, '~', $path );
//            }
//
//            $result[ $prefix . DIRECTORY_SEPARATOR . $fileSrc ] = $prefix . DIRECTORY_SEPARATOR . $file;
//        }
//
//        return $result;
//    }

}
