<?php

/**
 * Mumsys_Parser_Logline
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Parser
 * @version     1.1.1
 * Created: 2015-08-11
 */


/**
 * Class to parse a log line into it's parts from a common log file like apache
 * or nginx or by given special format.
 *
 * Example:
 * <pre>
 * # apache vhost configuration
 * $logFormat = '%v:%p %h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"';
 * $logFile = '/tmp/other_vhosts_access.log';
 * // master patterns to be set or defaults take affect
 * $patterns = array();
 * $parser = new Mumsys_Parser_Logfile($logFormat, $patterns);
 * // is already default: $parser->setFilterCondition('AND');
 * // is already default: $parser->setShowFilterResults();
 * // show the opposite, all which not match to the filters
 * // $parser->setHideFilterResults();
 * // show only http code 304
 * $parser->addFilter('httpcode', '304', true);// note: "30" matches to 300 301...
 * // check only port 80
 * $parser->addFilter('port', '80', true);
 * $file = new SplFileObject($logFile);
 * while (!$file->eof())  {
 *      $line = $file->fgets();
 *      $record = $parser->parse($line);
 * </pre>
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Parser
 */
class Mumsys_Parser_Logline
{
    /**
     * Version ID information.
     */
    const VERSION = '1.1.1';

    /**
     * Default log format.
     *
     * E.g.: Check your server config for details of the format:
     * Apache e.g "vhost_combined", "combined", "common" or just "referer":
     * vhost_combined:   "%v:%p %h %l %u %t \"%r\" %>s %O "%{Referer}i" "%{User-Agent}i"
     * combined:        "%h %l %u %t \"%r\" %>s %O "%{Referer}i" "%{User-Agent}i"
     * common:          "%h %l %u %t \"%r\" %>s %O"
     * referer:         "%{Referer}i -> %U"
     * agent:            "%{User-agent}i"
     * @var string
     */
    private $_defaultLogFormat = '%h %l %u %t "%r" %>s %O';

    /**
     * Log format to be used internally
     * @var string
     */
    private $_logFormat;

    /**
     * List of filters
     * @var array
     */
    private $_filters = array();

    /**
     * Flag for the way the filter will perform.
     * By default (false) the filter is set as whitelist which means only matches of the filter will return.
     * @var boolean
     */
    private $_filteredHide = false;

    /**
     * Flag to change the filter/ search behavior.
     * By default and condition will be used (all filter must match for an entry)
     * @var boolean
     */
    private $_filtersInAndConditions = true;


    /**
     * Default pattern list.
     * List of key/value pairs to substitute the key with the regular
     * expression. These are common patterns to be used in apache or nginx.
     *
     * @var array
     */
    private $_patternsDefault = array(
        '%%' => '(?P<percent>\%)',
        '%a' => '(?P<remoteIP>(((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|([0-9A-Fa-f]{1,4}(?::[0-9A-Fa-f]{1,4}){7})|(([0-9A-Fa-f]{1,4})?(:[0-9A-Fa-f]{1,4}){0,7}:(:[0-9A-Fa-f]{1,4}){1,7}))',
        '%A' => '(?P<localIP>(((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|([0-9A-Fa-f]{1,4}(?::[0-9A-Fa-f]{1,4}){7})|(([0-9A-Fa-f]{1,4})?(:[0-9A-Fa-f]{1,4}){0,7}:(:[0-9A-Fa-f]{1,4}){1,7}))',

        '%h' => '(?P<host>[a-zA-Z0-9\-\._:]+)',

        '%m' => '(?P<method>OPTIONS|GET|HEAD|POST|PUT|DELETE|TRACE|CONNECT)',
        '%p' => '(?P<port>\d+)',
        '%r' => '(?P<request>(?:(?:[A-Z]+) .+? HTTP/1.(?:0|1))|-|)',

        '%u' => '(?P<user>(?:-|[\w-]+))',
        '%U' => '(?P<url>.+?)',
        '%v' => '(?P<serverName>([a-zA-Z0-9]+)([a-z0-9.-]*))',
        '%V' => '(?P<canonicalServerName>([a-zA-Z0-9]+)([a-z0-9.-]*))',

        '%>s' => '(?P<httpcode>\d{3}|-)',
        '%b' => '(?P<respBytes>(\d+|-))',
        '%T' => '(?P<reqTime>(\d+\.?\d*))',
        '%O' => '(?P<txBytes>[0-9]+)',
        '%I' => '(?P<rxBytes>[0-9]+)',

        '\%\{(?P<name>[a-zA-Z]+)(?P<name2>[-]?)(?P<name3>[a-zA-Z]+)\}i' => '(?P<Header\\1\\3>.*?)',
        '%D' => '(?P<timeServeRequest>[0-9]+)',
        '%t' => '\[(?P<time>\d{2}/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/\d{4}:\d{2}:\d{2}:\d{2} (?:-|\+)\d{4})\]',
        '%l' => '(?P<logname>(?:-|[\w-]+))',
    );

    /**
     * Patterns list to be used internally
     * @var array
     */
    private $_patterns = array();


    /**
     * Initialise the object with a format an if needed your own configuration
     * of patterns.
     *
     * @param string $format Format of a log line.
     * @param array $patterns Initial patterns to be set. Otherwise default patterns will be used
     */
    public function __construct($format='', array $patterns=array())
    {
        if ($patterns) {
            $this->_patterns = $patterns;
        } else {
            $this->_patterns = $this->_patternsDefault;
        }

        if ($format) {
            $this->setFormat($format);
        } else {
            $this->setFormat($this->_defaultLogFormat);
        }
    }


    /**
     * Set the format of a log line.
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->_logFormat = "#^{$format}$#";
    }


    /**
     * Returns the reqular expression based on format and patterns.
     */
    private function _getExpression()
    {
        $expr = $this->_logFormat;

        foreach ($this->_patterns as $key => $replace) {
            $expr = preg_replace("/{$key}/", $replace, $expr);
        }
        return $expr;
    }


    /**
     * Sets or adds a pattern to pattern list.
     *
     * Default list of key/value pairs to substitute the key with the regular
     * expression. These are common patterns to be used in apache or nginx:
     * @see $this->_patternsDefault
     *
     * @param string $key Pattern identifier
     * @param string $pattern The pattern/ expression
     */
    public function setPattern($key, $pattern)
    {
        $this->_patterns[$key] = $pattern;
    }


    /**
     * Set the filters to return all NONE matching items from the filters.
     *
     * Note: This works only if filters are set.
     */
    public function setHideFilterResults()
    {
        $this->_filteredHide = true;
    }


    /**
     * Set the filters to return all matches from the filters.
     * Hint: Search for matches by given filter rules.
     *
     * Note: This works only if filters are set.
     */
    public function setShowFilterResults()
    {
        $this->_filteredHide = false;
    }


    /**
     * Sets the filter condition mode. Apply all filters in AND or OR condition.
     *
     * @param string $orOrAnd Condition to be set: "AND" or "OR"; Default: "AND"
     * @throws Exception If incoming parameter is whether the string "AND" nor "OR"
     */
    public function setFilterCondition($orOrAnd)
    {
        $chk = strtoupper($orOrAnd);

        if ($chk == 'AND' || $chk == 'OR') {
            if ($chk == 'AND') {
                $this->_filtersInAndConditions = true;
            } else {
                $this->_filtersInAndConditions = false;
            }
        } else {
            throw new Mumsys_Parser_Exception('Invalid filter condition');
        }
    }


    /**
     * Adds a filter/ search rule.
     * Note: Don't forget to escape spesial chars for the regular expressions.
     *
     * @param string $key Keyword based on the pattern rules to be expected: eg.: httpcode, user, time ...
     * @param array|string $value Value or list of values to look/ search for. matching tests! not exact tests!
     * @param type $sensitive Flag to enable sensitive mode or not. Default: false (case insensitive)
     */
    public function addFilter($key, $value=array(), $sensitive=false)
    {
        if ( is_string($value) ) {
            $value = array($value);
        }

        foreach ($value as $i => &$raw) {
            $value[$i] = preg_quote($raw, '#');
        }

        $this->_filters[$key][] = array('values'=>$value, 'case' => $sensitive);
    }


    /**
     * Parse a log line and return its parts.
     *
     * @param string $line A line of the log file.
     *
     * @return array|false Returns array with found properties or empty array if
     * filters take affect or false for an empty line.
     * @throws Exception If format doesn't match the line format.
     */
    public function parse($line)
    {
        if (empty($line)) {
            return false;
        }

        $regex = $this->_getExpression();

        if (!preg_match($regex, $line, $matches)) {
            $message = sprintf(
                'Format of log line invalid (expected:"%1$s"); Line was "%2$s"; regex: "%3$s"',
                $this->_logFormat,
                $line,
                $regex
            );
            throw new Mumsys_Parser_Exception($message);
        }

        $result = array();

        foreach (array_filter(array_keys($matches), 'is_string') as $key) {
            if ('time' === $key && true !== $stamp = strtotime($matches[$key])) {
                $result['stamp'] = $stamp;
            }

            $result[$key] = $matches[$key];
        }

        $return = array();
        if ( ($ok=$this->_applyFilters($result)) ) {
            $return = $ok;
        }

        return $return;
    }


    /**
     * Apply filters.
     *
     * @param array $result list of parameters from the log line.
     * @return array Retruns an empty array if filters take affect otherwise the
     * it returns the incoming result array
     */
    private function _applyFilters($result)
    {
        if (!$this->_filters) {
            return $result;
        }

        $numMatches = 0;
        $itMatchesInOrCondition = false;

        foreach( $this->_filters as $key => $paramsList )
        {
            if (isset($result[$key]) && $result[$key] )
            {
                foreach( $paramsList as $i => $params )
                {
                    foreach( $params['values'] as $value )
                    {
                        $modifier = 'i';
                        if ( $params['case'] ) {
                            $modifier = '';
                        }

                        $regex = sprintf('/(%1$s)/%2$s', $value, $modifier);
                        if ( preg_match($regex, $result[$key]) ) {
                            $numMatches += 1;
                            $itMatchesInOrCondition = true;
                        }
                    }
                }
            }
        }

        if ($this->_filtersInAndConditions)
        {
            $itMatches = false;
            if (count($this->_filters) == $numMatches) {
                $itMatches = true;
            }
        } else {
            $itMatches = $itMatchesInOrCondition;
        }

        if ($itMatches === $this->_filteredHide) {
            return array();
        } else {
            return $result;
        }
    }

}

