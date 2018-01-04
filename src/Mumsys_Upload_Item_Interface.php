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
 * @vesion      1.0.0
 */


/**
 * Interface for file upload items.
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Upload
 */
interface Mumsys_Upload_Item_Interface
{
    /**
     * Returns the name of the uploaded file.
     *
     * @return string Name of the uploaded file
     */
    public function getName(): string;

    /**
     * Sets the name of the uploaded file.
     *
     * @param string $value File name (e.g.: name.ext)
     */
    public function setName( string $value ): void;

    /**
     * Returns the tmp location/tmp_name of the uploaded file.
     *
     * @return string Tmp location/tmp_name of the uploaded file
     */
    public function getTmpName(): string;


    /**
     * Sets the tmp location/tmp_name of the uploaded file.
     *
     * @param string $value File tmp location/tmp_name (e.g.: /tmp/tmpnamexyz)
     *
     * @throws Mumsys_Upload_Exception If directory of value not exists
     */
    public function setTmpName( string $value ): void;


    /**
     * Returns the type/ mimetype of the uploaded file.
     *
     * @return string Type/mimetype of the uploaded file. Default: unknown
     */
    public function getType(): string;

    /**
     * Sets the type/ mimetype of the uploaded file.
     *
     * @param string $value type/ mimetype (e.g.: text/plain)
     */
    public function setType( string $value ): void;


    /**
     * Returns the file size of the uploaded file.
     *
     * @return integer Filesize of uploaded file
     */
    public function getSize(): int;


    /**
     * Sets the file size of the uploaded file.
     *
     * @param integer $value File size for uploaded file
     */
    public function setSize( int $value ): void;


    /**
     * Returns the upload file error code.
     *
     * @return integer Upload file error code of uploaded file
     */
    public function getError(): int;


    /**
     * Sets the upload file error code of the uploaded file.
     *
     * @param integer $value Upload file error code for the uploaded file
     */
    public function setError( int $value ): void;

}
