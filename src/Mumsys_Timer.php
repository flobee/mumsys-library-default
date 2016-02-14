<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Timer
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright (c) 2006 by Florian Blasel
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Timer
 * @version     3.2.0
 * V1 - Created 2006-01-12
 * @since       File available since Release 2
 * @filesource
 * -----------------------------------------------------------------------
 */
 /*}}}*/


/**
 * Track the duration between a start and end time.
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Timer
 */
class Mumsys_Timer extends Mumsys_Abstract
{
    /**
     * Version ID information
     */
    const VERSION = '3.2.0';

    private $_start = 0;
    private $_stop = 0;
    private $_elapsed = 0;


    /**
     * Initialize the object.
     *
     * @param boolean $startNow Flag to enable "start now" function.
     * If true time recording starts now
     */
    public function __construct( $startNow=false )
    {
        if ( $startNow ) {
            $this->start();
        }
    }


    /**
     * Start the timer
     */
    public function start()
    {
        $this->_start = $this->_microtimeGet();
    }


    /**
     * Stop the timer and calculate the time between start and stop time.
     *
     * @return string Returns the number of seconds, micro secconds
     */
    public function stop()
    {
        $this->_stop = $this->_microtimeGet();
        $this->_elapsed = $this->_stop - $this->_start;

        return $this->_elapsed;
    }


    /**
     * Returns the start time.
     *
     * @return float|0 Returns start timestamp in microtime format.
     */
    public function startTimeGet()
    {
        return $this->_start;
    }


    /**
     * Returns the stop time.
     *
     * @return float|0 Returns stop timestamp in microtime format.
     */
    public function stopTimeGet()
    {
        return $this->_stop;
    }


    /**
     * Returns the elapsed time.
     *
     * @return float|0 Returns elapsed timestamp in microtime format.
     */
    public function elapsedTimeGet()
    {
        return $this->_elapsed;
    }


    /**
     * Get microtime.
     *
     * @return float Returns the current timestamp in microtime format.
     */
    protected function _microtimeGet()
    {
        return microtime(1);
    }


    /**
     * Stops the timer and returns the duration as string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->stop();
    }

}
