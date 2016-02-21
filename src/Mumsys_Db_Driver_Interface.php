<?php

/*{{{*/
/**
 * ----------------------------------------------------------------------------
 * Mumsys_Db_Driver_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @copyright Copyright (c) 2007 by Florian Blasel for FloWorks Company
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Db
 * @version     3.1.0
 * 0.1 - Created: 2010-12-29
 * -----------------------------------------------------------------------
 */
/* }}} */


/**
 * Database driver interface
 * Includes std connection and operation methodes base on prim. mysql behavior
 *
 * @todo query builder methodes should go to statment builder methodes
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Db
 */
interface Mumsys_Db_Driver_Interface
{
    /**
     * Version ID information
     */
    const VERSION = '3.1.0';

    /**
     * Initialization of database and features
     *
     * @param array $args Possible values:
     * - 'db' optional Database name
     * - 'username' optional Database username
     * - 'password' optional Database password
     * - 'host' optional Database hostname/ip
     * - 'port' optional Database port
     * - 'charset' client character set to be used. If given it will be set when connection will be made
     * - 'socket' boolean optional
     * - 'debug' boolean optional
     * - 'throw_errors' boolean optional default: true
     * - 'compress' boolean optional Deside to compress the connection or not.
     */
    public function __construct( array $args );

    /**
     * Destructor. Close current connection.
     *
     * @return boolean Return the status for closing the connection. True on success.
     */
    public function __destruct();

    /**
     * Escape a given string for the database query
     *
     * @see php.net/manual/en/function.addslashes.php
     * @param string $string String to be escaped
     * @return string Returns the escaped string
     */
    public function escape( $string = '' );

    /**
     * Quote string
     *
     * @param string $string String to be quoted
     * @param string $quote Quote type to be added eg: " or '
     * @return string The quoted string
     */
    public function quote( $string, $quote = '\'' );

    /**
     * Returns the name of the database.
     *
     * @return string Database name
     */
    public function getDbName();

    /**
     * Returns the number of querys.
     *
     * @return integer Number of querys this DB dirver has taken
     */
    public function getNumQuerys();

    /**
     * Returns the latest sql statment which was executed.
     *
     * @return string Latest sql statment which was executed.
     */
    public function getQuery();

    /**
     * Returns the list of sql statments.
     *
     * @return array List of sql statments.
     */
    public function getQueryStmts();

    /**
     * Returns the sql compare values.
     * <code>
     * array([key for the database. e.g: AND] => array(
     *    [public/translated key to map to for visualation, [translated value
     * description]
     * )
     * // e.g:
     * array('AND' => array('and', 'and operation')<br />
     * </code>
     * @return array List of compare values like: AND or OR in structure.
     */
    public function getSqlCompareValues();

    /**
     * Returns the sql operators.
     * Multi-dimensional array: <br />
     * array('internal key'=> array(
     *    'public/ translated key to map to' => 'translated value description')
     * )<br />
     * e.g:  array('=' => array( '==', _CMS_ISEQUAL )<br />
     * @return array List of operators
     */
    public function getSqlOperators();

    /**
     * Returns the list of errors the program has detected and collected.
     *
     * @return array List of errors with message=>error message, code=>error code
     */
    public function getErrors();

    /**
     * Returns the latest error message.
     *
     * @return string Error message
     */
    public function getErrorMessage();

    /**
     * Returns the latest error number/code (depending on DB driver).
     *
     * @return string Error message
     */
    public function getErrorCode();

    /**
     * Sets the flag for the error handling.
     *
     * @param boolean $flag True for throw errors or false to collect errors.
     */
    public function setThrowErrors( $flag );

    /**
     * Returns th status if throw errors is enabled or not.
     */
    public function getThrowErrors();

    /**
     * Sets the flag for the debug handling.
     *
     * @param boolean $flag True for enable debug mode.
     */
    public function setDebugMode( $flag );

    /**
     * Returns debug mode is enabled or not.
     *
     * @return boolean True if debug mode is on/enable otherwise false.
     */
    public function getDebugMode();


    // --- end db common abstract

    /**
     * Create a database connection.
     *
     * @return resource|false Retruns the database connection resource on succsess or
     * throws exception
     * @throws Mumsys_Db_Exception On connection error or if initial database can not be selected
     */
    public function connect();

    /**
     * Closes the db connection.
     *
     * This methode will be called at least when __destuct event occur.
     *
     * @return bool
     */
    public function close();

    /**
     * Sets the client character set.
     *
     * @param string $charset A valid character set name
     * @return boolean True on success or false on failure
     * @throws Mumsys_Db_Exception if throw errors was set
     */
    public function setCharset( $charset );

    /**
     * Returns the client character set.
     *
     * @return string|object|false Name of the character or false on error
     * @throws Mumsys_Db_Exception if throw errors was set
     */
    public function getCharset();

    /**
     * Select a database
     * On failure the connection will be closed
     *
     * @param string $database Name of the database
     * @return boolean Returns true on success
     */
    public function selectDB( $database );

    /**
     * Get a list of databases
     *
     * @return array Returns an associative array with the database-name as key
     * and as value or false on failure
     */
    public function showDBs();

    /**
     * Fetch tabel names from current database and their connection information
     *
     * @param string $db Database name
     * @return array assoc array which the tables as key and as array value or
     * false on failure
     */
    public function showTables();

    /**
     * Show propertys of a table or a given column.
     *
     * @todo More examples for expected return values
     *
     * @param string $table Table to show the columns from
     * @param string $field Optional columne to get informations from
     *
     * @return array|false The columns propertys with lower case array keys
     * @throws Mumsys_Db_Exception Throws exception on error
     */
    public function showColumns($table='', $field=null);

    /**
     * Get current system status for uptime, threads, queries, open tables,
     * flush tables and queries per second.
     *
     * @return string|false Returns a string or false
     */
    public function stat();

    /**
     * Retruns the server info and version string.
     * Requires an activ connection on mysql.
     *
     * Returns for example:
     *  10.0.17-MariaDB-log
     *  5.5.5-10.0.17-MariaDB-log
     *  5.5.5-MySQL-log
     * @return string Version string in n.n.n[-xy] format including server name
     */
    public function getServerInfo();

    /**
     * Execute a given sql statement.
     *
     * If connection was not made befor it will be created.
     * General info: For SELECT, SHOW, DESCRIBE, EXPLAIN and other
     * statements returning resultset, query() returns a
     * Mumsys_Db_Driver_Result_Interface on success, or FALSE on error.
     * For other type of SQL statements, INSERT, UPDATE, DELETE, DROP, etc.
     * query() returns TRUE on success or FALSE on error. If throw errors is
     * set (default) Mumsys_Db_Exception will be thrown.
     *
     * @param string $sql Query to be executed
     * @param boolean $unbuffered Flag to executed an unbuffered query
     * default: false
     *
     * @return Mumsys_Db_Driver_Result_Interface|false Mumsys_Db_Driver_Mysql_Result
     * object or false on error.
     * @throws Mumsys_Db_Exception Throws exception if
     * - database connection was not made and fails
     * - on empty sql statement (if throw errors was set)
     * - on query error (if throw errors was set)
     */
    public function query($sql=false, $unbuffered=false);

    /**
     * Test if given resource from a query is in error state
     *
     * @param resource|Mumsys_DB_Driver_Result_Interface $res The driver
     * result interface
     *
     * @return boolean return true on error, false on no error
     */
    public function isError( $res );

    /**
     * Update data from database.
     *
     * @param array $params Parametes as follow:<br />
     * [fields] required Fields to update by given key=>value pairs<br />
     * [table] required Table to update<br />
     * [where] required Array key=>value pairs for the where clause;
     * Note: only AND conditions will be made<br />
     * [updateall] optional if set an empty where parameter will be accepted
     * to update ALL existing data without any restrictions. be careful to
     * use it<br/>
     * [limit] optional array containing the offset (the start value), limit
     * count or just the limit count e.g. array(limit count).<br />
     *
     * @return Mumsys_Db_Driver_Result_Interface|false
     * Mumsys_Db_Driver_Result_Interface object or false on error
     */
    public function update( array $params = array() );

    /**
     * Select data from the database.
     *
     * @param array $params Parameters to be set:<br/>
     * - [fields] required Fields to update or insert by a given array with key=>value pairs
     * - [table] required Table to update<br/>
     * - [where] required Array key=>value construct for the where clause; Note: only AND conditions will be made
     * - [order] optional; Set the order for select or update statements. List of key/value pairs (columne/ASC,DESC)
     * - [limit] optional array containing the offset (the start value), limit count for selects or just the limit
     * count e.g. array(limit count) for select, delete, updates.
     *
     * @return Mumsys_Db_Driver_Mysql_Result Object or false on error
     * @return Mumsys_Db_Driver_Result_Interface Returns
     * Mumsys_Db_Driver_Result_Interface object or false on error
     */
    public function select( array $params = array() );

    /**
     * Insert data to the storage.
     *
     * @param array $params Parameters to be set:<br/>
     * [fields] required Fields to set in the insert command<br/>
     * [table] required Table to insert<br/>
     * @return integer|false Return the last insert ID or false on error
     */
    public function insert( array $params = array() );


    /**
     * Replace existing data.
     *
     * @param array $params Parameters to be set/ replaced:<br/>
     * [fields] required Fields to set in the replace command<br/>
     * [table] required Table to insert<br/>
     * @return integer|false Returns number of affected rows or false on error
     */
    public function replace( array $params = array() );

    /**
     * Delete data from storage.
     *
     * @param array $params Parameters as follow:<br/>
     * [table] required Table to delete from<br/>
     * [where] required Array key=>value pairs for the where clause; <br/>
     * Note: only AND conditions will be made to delete entrys
     * [where] required Array key=>value construct for the where clause;
     * Note: only AND conditions will be made<br/>
     * [updateall] optional if set an empty where parameter will be accepted
     * to update or delete ALL existing data without any restrictions.
     * be careful to use it<br/>
     * [limit] optional array containing the offset (the start value), limit
     * count or just the limit count e.g. array(limit count)
     * @return Mumsys_Db_Driver_Mysql_Result|false Returns false on error
     */
    public function delete( array $params = array() );


    /**
     * Retruns a single sql expression basicly made for a sql where clause.
     * E.g.: WHERE ( `a` LIKE '%b%' )
     * An expression looks like: array('operator'=>array('column' => 'value'))
     * @see $_sqlOperators array keys of possilble operators.
     * Speacial operators:
     * - '_' string|array Can be used for unescaped and unquoted special
     * comparisons.
     * <b>Important:</b> If array values given:
     * - All values will NOT be escaped and NOT quoted. (security problem! Be
     * sure you know what you are doing)
     * - All values will be set as AND comparison.
     * - Array keys will be ignored.
     * - '=' array key/value pair or key/list of values. If list of values
     * given in mysql IN () operator will be used. Values can be numeric or
     * string. If values contains sttings they will be quoted.
     *
     * Examples for '_' operator:
     * <code>
     * array('_'=> array('date < now() AND date > \'2000-12-31 00:00:00\')
     * array('_'=> 'date < now() AND date > \'2000-12-31 00:00:00\')
     * // ( a > 'b' ) AND ( c < 3 )
     * array('_' => array('a > \'b\'', 'c < 3')),
     * </code>
     *
     * Examples for '=' operator to switch to mysql IN () operator:
     * <code>
     * // default usage: WHERE name = 'value'
     * array('=' => array('name' => 'value'))
     * // WHERE ( list IN (1, 'string to be quoted', 3, 4) )
     * array('=' => array('list' => array(1,'string to be quoted', 3, 4)))
     * </code>
     *
     * @param array $expression
     * @return string|boolean Returns the created expression or false on error
     * @throws Mumsys_Db_Exception Throws exception on errors if throw errors was set
     */
    public function compileSqlExpression( array $expression );

    /**
     * tested but not in use, compile* Methodes are used in @see _save() method
     */
    public function compileQuery(array $opts=array( ));

    /**
     * Returns select statment by given configuration list.
     * E.g.:
     * - *, t2.name, DATE_FORMAT(t1.time_start, \'%d.%m.%Y %H:%i:%s\') AS time
     * - cfg.*, UNIX_TIMESTAMP(cfg.`time_lastupdate`) AS unix_lastupdate
     *
     * Usage for the configuration input:
     * - array list of columns to select (as array values)
     * - array list or key/value pairs where key is the alias and the value the
     * column e.g.: array('alias'=>'column') -> "column AS alias"
     *
     * Special operations:
     * - '_' string|array Can be used for un-escaped and un-quoted input. The
     * input will be used as is which is a security problem! Be sure you know
     * what you are doing!.
     * E.g:
     * <code>
     * array('_' => 'if (col=2, \'yes\', \'no\')')
     * array('_' => 'UNIX_TIMESTAMP(cfg.time_lastupdate)')
     * </code>
     *
     * List of special operations:
     * <code>
     * array('_' => 'count(*)')
     * // count(*) AS cnt,`thisId` AS id,`name`
     * array('_', array('count(*) AS cnt', 'id' => 'thisId', 'name'))
     * </code>
     *
     * @param array $fields List of fields to select.
     *
     * @return string|flase Column list for the select statment or false on error
     * @throws Mumsys_Db_Exception Throws exception on errors if throw errors was set
     */
    public function compileQuerySelect( array $fields );

    /**
     * Returns set statement for insert or update statement by given configuration
     * list.
     * Example:
     * <code>
     * array('text' => 'textaNew', 'textb' => null, 'textc' => 'now()');
     * </code>
     *
     * @param array $set List of key/value pairs for the set statement
     *
     * @return string Returns the full set statement
     * @throws Mumsys_Db_Exception Throws exception on errors when escaping the
     * values.
     */
    public function compileQuerySet( array $set = array() );

    /**
     * Retruns complex sql expression basicly made for a sql where clause.
     *
     * The configuration input looks as follows: A compare value (see array
     * key of $_sqlCompareValues) followed by a list of expressions the
     * expressions should be compared with.
     *
     * E.g: array('[AND|OR]' => array( [list of expressions])).
     *
     * An expression looks like array('[operator] => array('key' => 'value')).
     * @see $_sqlOperators Array keys of it.
     *
     * Operator '_' can be used for special expressions. For more
     * @see compileSqlExpression() This belongs to security problems.
     *
     * Simple mode:
     * Only a list of key/value pairs are used as input. All values are
     * compared as AND condition. All operators will be '='. This is
     * useful for update statments and speed up things and reduce code.
     *
     * Example:
     * <code>
     * $array = array(
     *      // the following expressions as AND condition...
     *      'AND' => array(
     *          array('=' => array('name' => 'value')),
     *          // `name` LIKE '%value%'
     *          array('LIKE' => array('name >= 'value')),
     *      ),
     * );
     * </code>
     *
     * Example for the simple mode:
     * <code>
     * // `name`='mum sys' AND `thatid`=123
     * $array = array('name' => 'mum sys', 'thatid' => 123);
     * </code>
     *
     * @param array $where Configuration list for the where statment.
     *
     * @return string|false Returns the expression string or false for error if
     * throw errors was set to false
     * @throws Mumsys_Db_Exception Throws exception on invalid input and if
     * throw errors was set
     */
    public function compileQueryWhere( array $where = array() );

    /**
     * Returns the 'group by' clause sql statement.
     *
     * @param array $groupby List of columns to set the 'group by' clause.
     *
     * @return string The created group by clause
     */
    public function compileQueryGroupBy( array $groupby = array() );

    /**
     * Returns the 'order by' clause sql statement.
     *
     * @param array $orderby List of key/value pairs where 'key' is the column
     * and the 'value' the sortation way. If key is not given the value will be
     * used and column and the sortation will be 'ASC'
     *
     * @return string Returns the created order by clause
     */
    public function compileQueryOrderBy( array $orderby );

    /**
     * Returns the 'limit, offset' clause sql statement.
     *
     * Usage: Array containing one or two values: The offset (startpoint to
     * select) and the limit (limit count, number of rows to select).
     * If both values are given e.g.:  array(0, 10)
     * - array key 0 belongs to the offset (0)
     * - array key 1 belongs to the limit count (10)
     * If only one value is given:
     * - array key 0 belongs to the limit count (10)
     * Empty array returns an empty string.
     *
     * @param array $limit array, see description
     * @return string Returns the created limit, offset clause or empty string
     */
    public function compileQueryLimit( array $limit );

}