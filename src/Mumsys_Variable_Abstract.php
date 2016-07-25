<?php

/*{{{*/
/**
 * Mumsys_Variable_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2006 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 * Created: 2006 based on Mumsys_Field, renew 2016 PHP >= 7
 */
/*}}}*/


/**
 * Abstact class for common features for the item and/or manager
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Variable
 */
abstract class Mumsys_Variable_Abstract extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '1.1.1';

    /**
     * Variable / validation types.
     *
     * PHP types and optional additional types for the item to set, for the
     * manger to be implemented.
     * @var array List of types
     * @
     */
    const TYPES = array(
        'string', 'char', 'varchar', 'text', 'tinytext', 'longtext',
        'int', 'integer', 'smallint',
        'float', 'double',
        'numeric',
        'boolean', 'array', 'object',
        'date',
        'datetime', 'timestamp',
        'email',
        'ipv4', 'ipv6',
        'unittest'
    );
}

