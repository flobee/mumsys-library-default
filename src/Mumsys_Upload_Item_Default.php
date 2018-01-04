<?php

/**
 * Mumsys_Upload_Item_Default
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2018 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Upload
 */


/**
 * Class for standard file upload items.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Upload
 */
class Mumsys_Upload_Item_Default
    extends Mumsys_Abstract
    implements Mumsys_Upload_Item_Interface
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Data container for the file properties @see __construct()
     *
     * @var array
     */
    private $_input = array();


    /**
     * Initialize the item object.
     *
     * @param array $input Prameters to be set like:
     *  - 'name' File name
     *  - 'type' Mime type
     *  - 'tmp_name' Location to tmp file
     *  - 'error' Error code
     *  - 'size' File size
     */
    public function __construct( array $input = array() )
    {
        $this->_input = $input;
    }


    /**
     * Destruct the item object.
     */
    public function __destruct()
    {
        $this->_input = array();
    }


    /**
     * Returns the name of the uploaded file.
     *
     * @return string Name of the uploaded file
     */
    public function getName(): string
    {
        return (isset($this->_input['name']) ? (string) $this->_input['name'] : '' );
    }


    /**
     * Sets the name of the uploaded file.
     *
     * @param string $value File name (e.g.: name.ext)
     */
    public function setName( string $value ): void
    {
        $this->_input['name'] = $value;
    }


    /**
     * Returns the tmp location/tmp_name of the uploaded file.
     *
     * @return string Tmp location/tmp_name of the uploaded file
     */
    public function getTmpName(): string
    {
        return (isset($this->_input['tmp_name']) ? (string) $this->_input['tmp_name'] : '' );
    }


    /**
     * Sets the tmp location/tmp_name of the uploaded file.
     *
     * @param string $value File tmp location/tmp_name (e.g.: /tmp/tmpnamexyz)
     *
     * @throws Mumsys_Upload_Exception If directory of value not exists
     */
    public function setTmpName( string $value ): void
    {
        if ( !is_dir(basename($value)) ) {
            $message = sprintf('Directory not exists: "%1$s"', $value);
            throw new Mumsys_Upload_Exception($message);
        }

        $this->_input['tmp_name'] = $value;
    }


    /**
     * Returns the type/ mimetype of the uploaded file.
     *
     * @return string Type/mimetype of the uploaded file. Default: unknown
     */
    public function getType(): string
    {
        return (isset($this->_input['type']) ? (string) $this->_input['type'] : 'unknown' );
    }


    /**
     * Sets the type/ mimetype of the uploaded file.
     *
     * @param string $value type/ mimetype (e.g.: text/plain)
     */
    public function setType( string $value ): void
    {
        $this->_input['type'] = $value;
    }


    /**
     * Returns the file size of the uploaded file.
     *
     * @return integer Filesize of uploaded file
     */
    public function getSize()
    {
        return (isset($this->_input['size']) ? (int) $this->_input['size'] : 0 );
    }


    /**
     * Sets the file size of the uploaded file.
     *
     * @param integer $value File size for uploaded file
     */
    public function setSize( int $value ): void
    {
        $this->_input['size'] = $value;
    }


    /**
     * Returns the upload file error code.
     *
     * @return integer Upload file error code of uploaded file
     */
    public function getError()
    {
        return (isset($this->_input['error']) ? (int) $this->_input['error'] : 0 );
    }


    /**
     * Sets the upload file error code of the uploaded file.
     *
     * @param integer $value Upload file error code for the uploaded file
     */
    public function setError( int $value ): void
    {
        $this->_input['error'] = $value;
    }

}
