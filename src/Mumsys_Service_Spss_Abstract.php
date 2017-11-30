<?php

/**
 * Mumsys_Service_Spss_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 * @version     1.0.0
 * Created: 2017-11-30
 */


/**
 * Abstract class for SPSS reader/writer.
 *
 * @see https://github.com/flobee/spss
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 */
abstract class Mumsys_Service_Spss_Abstract
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Location of the file to read from/ write to.
     * @var string
     */
    private $_file = '';


    /**
     * Initialise the object.
     *
     * @param string $file Location of the file to read from/ write to.
     */
    public function __construct($file)
    {
        $this->_file = $file;
    }

}

