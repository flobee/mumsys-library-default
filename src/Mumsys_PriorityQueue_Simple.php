<?php

/* {{{ */
/**
 * ----------------------------------------------------------------------------
 * Mumsys_PriorityQueue_Simple
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * @copyright Copyright (c) 2016 by Florian Blasel for FloWorks Company
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_PriorityQueue
 * @version     1.0.0
 * Created: 2016-03-20
 * @filesource
 */
/* }}} */


/**
 * Simple priority Queue using priority names to place to order of items.
 * With "befor" and "after" keys given priroities can be set in the order you
 * which to use. Altn. hav a look into the SplPriorityQueue.
 *
 * Example:
 * A => prio99 <- curently higest prio
 * B => prio88 <- lower prio
 * C => befor prio99
 * Result: C, A, B
 *
 */
class Mumsys_PriorityQueue_Simple
{
    /**
     * Stack of the priority
     * @var array
     */
    private $_stack;


    /**
     * Initialize the object with an optional List of Key/ID => value pairs to
     * be set as initial and ready to go stack.
     * Example: array(
     *  array('default' => mixed content),
     *  array('afterdefault1 => mixed content),
     *  array('afterdefault2 => mixed content),
     * )
     *
     * @param array $stack Optional; Predefined and ready to go list of items.
     */
    public function __construct( array $stack = array() )
    {
        $this->_stack = $stack;
    }


    /**
     * Adds a key/value pair to the queue stack.
     *
     * By default its a simple array where you can add content and new values
     * will be added. If this happens you dont nee this class.
     * If the sortion should be change this can be made by given position way
     * (before/after) and the key/id the addition belongs to.
     *
     * Example: You want to add an item before the first element or inbetween.
     * Or befor dispatching you need to resort some stuff to execute your
     * functions in the right order.
     * e.g. You have several configs you load but you can not manage
     * directly the order of the load. When the configs are needed you want to
     * have the latest loaded config at the second position so that something
     * gets in place where you need it. Catches FIFO vs LIFO confusions
     * <code>
     * $object->add('default', 'some values')
     * // $object->add('default', 'some new values'); // throws exception
     * $object->add('custom', 'some values');
     * $object->add('new', 'some values', before', 'default');
     * result: new, default, custom
     * </code>
     *
     * @param string|integer $identifier Unique key/ID for the value to add
     * @param mixed $value Value to add
     * @param string $positionWay String "before" | "after" (default)
     * @param string $positionID Name of the key/ID where to set (before/
     * after) this new entrys
     *
     * @throws Mumsys_Exception If Key/ID already exists
     */
    public function add( $identifier, $value, $positionWay = 'after', $positionID = null )
    {
        if (isset($this->_stack[$identifier])) {
            $message = sprintf('Identifier "%1$s" already set', $identifier);
            throw new Mumsys_Exception($message);
        }

        if (isset($this->_stack[$positionID])) {
            $pos = $this->_getPos($positionID, $positionWay);
            $part = array_splice($this->_stack, 0, $pos);
            $this->_stack = array_merge($part, array($identifier => $value), $this->_stack);
        } else {
            $this->_stack[$identifier] = $value;
        }
    }


    /**
     * Returns the new position of the given key depending on the position.
     *
     * @param string $posKey Name of the key/ID where to set (before/
     * after) this new entrys
     * @param string $posWay String "before" | "after" (default)
     * @return integer Number of the position the found key is placed
     * @throws Mumsys_Exception
     */
    private function _getPos( $posKey, $posWay = 'after' )
    {
        $i = 0;
        $pos = false;
        while (list($key) = each($this->_stack)) {
            if ($posKey === $key) {
                $pos = $i;
                break;
            }
            $i++;
        }

        switch ($posWay) {
            case 'before':
                //$pos = $pos;
                break;
            case 'after':
                $pos = $pos + 1;
                break;
            default:
                $message = sprintf('Position way "%1$s" not implemented', $posWay);
                throw new Mumsys_Exception($message);
        }

        return $pos;
    }


    /**
     * Return the priority queue.
     *
     * @return array Returns the list of key/value pairs
     */
    public function getQueue()
    {
        return $this->_stack;
    }

}
