<?php

/**
 * Mumsys_Assets_Upload
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2017 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Assets
 */

/**
 * Upload handler for assets.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Assets
 */
class Mumsys_Assets_Upload
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Internal prozess ID to fetch progress status information while uploading.
     * @var string
     */
    private $_prozessID;

    /**
     * Mumsys session interface.
     * @var Mumsys_Session_Interface
     */
    private $_session;

    /**
     * @var array
     */
    private $_options;

    /**
     * List of key/value pairs of error messages where the key is the error code.
     * @link http://php.net/manual/en/features.file-upload.errors.php Documentation
     *
     * @var array
     */
    private $errorMessages = array(
        UPLOAD_ERR_OK => 'Upload successful',
        // code 1
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize '
            . 'directive in php.ini',
        // code 2
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive '
            . 'that was specified in the HTML form',
        // code 3
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        // code 4
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        // code 6
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        //code 7
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        // code 8
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
    );

    /**
     * Initializes the upload handle.
     *
     * @throws Mumsys_Assets_Item_Exception If image couldn't be retrieved from the
     * given file location
     */
    public function __construct( $prozessKey, Mumsys_Session_Interface $session, array $options=array() )
    {
        if (ini_get('file_uploads') == false) {
            throw new Mumsys_Assets_Exception('File uploads disabled. Check the PHP.ini');
        }
        // Check if key exists
        if (isset($_FILES[$key]) === false) {
            throw new \InvalidArgumentException("Cannot find uploaded file(s) identified by key: $key");
        }

        // to be added in form field
        $this->_prozessID = ini_get("session.upload_progress.prefix") . $prozessKey;

        $this->_session = $session; // $_SESSION;

        if ($this->_session->get($this->_prozessID, false)) {
            // update session on async status
            if (isset($_SESSION[$this->_prozessID]) )
            {
                $this->_session->replace($this->_prozessID, $_SESSION[$this->_prozessID]);

            }


        }
    }


    /**
     * Update async data to session.
     */
    public function __destruct()
    {
         $this->_session->replace($this->_prozessID, $_SESSION[$this->_prozessID]);
    }

//
//    /**
//     * Returns the process ID.
//     *
//     * @return string Prozess ID
//     */
//    public function getID()
//    {
//        return $this->_prozessID;
//    }

    public function setStatus( $status = false )
    {
        switch ( $status ) {
            case 'cancel':
                $reg = array('cancel_upload' => true);
                $this->_session->replace($this->_prozessID, $reg);
                break;

        }
    }

    /**
     * Returns upload status informations.
     *
     * @return array|false List of upload status informations or false if data
     * could not be found
     */
    public function getStatus()
    {
        return $this->_session->get($this->_prozessID, false);
    }

}