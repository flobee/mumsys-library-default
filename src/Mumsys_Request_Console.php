<?php


/* {{{ */
/**
 * Mumsys_Request_Abstract
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Request
 * @filesource
 */
/* }}} */


/**
 * Abstract request class to get input parameters.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Request
 */
class Mumsys_Request_Console
    extends Mumsys_Request_Abstract
    implements Mumsys_Request_Interface
{


    /**
     * Version ID information
     */
    const VERSION = '1.0.0';


    /**
     * The current programm name
     * @var string
     */
    protected $_programName;

    /**
     * Default program key name for retrieving the program parameter
     * @var string
     */
    protected $_programNameKey = 'program';

    /**
     * The current controller name
     * @var string
     */
    protected $_controllerName;

    /**
     * Default controller key name for retrieving the controller parameter
     * @var string
     */
    protected $_controllerNameKey = 'controller';

    /**
     * The current action name
     * @var string
     */
    protected $_actionName;

    /**
     * Default action key name for retrieving the "action" parameter
     * @var string
     */
    protected $_actionKey = 'action';

    /**
     * Incomming request parameters
     * @var array
     */
    protected $_input = array();


    /**
     * Initialize the request object using.
     *
     * @param array $options Optional initial options e.g.:
     * 'programKey','controllerKey', 'actionKey',
     */
    public function __construct( array $options = array() )
    {
        if (isset($options['programKey'])) {
            $this->setProgramKey($options['programKey']);
        }

        if (isset($options['controllerKey'])) {
            $this->setControllerKey($options['controllerKey']);
        }

        if (isset($options['actionKey'])) {
            $this->setActionKey($options['actionKey']);
        }

        if (isset($_SERVER['argv']) && is_array($_SERVER['argv']))
        {
            foreach ($_SERVER['argv'] as $keyValue)
            {
                $tmp = explode('=', $keyValue);
                if (isset($tmp[1])) {
                    $list[$res[0]] = $res[1];
                } else {
                    $list[] = $res[0];
                }
            }

            $this->_input = $list;
        }
    }

}
