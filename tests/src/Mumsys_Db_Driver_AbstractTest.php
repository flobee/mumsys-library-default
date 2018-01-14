<?php


class Mumsys_Db_Driver_Testdummy
    extends Mumsys_Db_Driver_Abstract
{


    public function _setError( $message, $code = null, $prev = null )
    {
        return [$message, $code, $prev];
    }


    public function close()
    {
        return true;
    }

}


/**
 * Mumsys_Db_Driver_Abstract Test
 */
class Mumsys_Db_Driver_AbstractTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Db_Driver_Testdummy
     */
    protected $_object;

    /**
     * @var Mumsys_Context
     */
    protected $_context;
    protected $_configs;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_context = new Mumsys_Context();
        $this->_configs = MumsysTestHelper::getConfigs();
        $this->_configs['database']['type'] = 'Testdummy'; //mysqli

        $this->_object = new Mumsys_Db_Driver_Testdummy($this->_context, $this->_configs['database']);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = NULL;
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::__construct
     */
    public function test__construct()
    {
        $x = new Mumsys_Db_Driver_Testdummy($this->_context, $this->_configs['database']);

        $this->assertInstanceOf('Mumsys_Db_Driver_Testdummy', $x);
        $this->assertInstanceOf('Mumsys_Db_Driver_Abstract', $x);
        $this->assertNotInstanceOf('Mumsys_Db_Driver_Interface', $x);
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::__destruct
     */
    public function test__destruct()
    {
        $expected = $this->_object->close();
        $this->assertTrue($expected);
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::escape
     */
    public function testEscape()
    {
        $this->assertEquals('abc', $this->_object->escape("abc"));
        $this->assertEquals('\\\'', $this->_object->escape("'"));
        $this->assertEquals("ab\'c", $this->_object->escape("ab'c"));
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::quote
     */
    public function testQuote()
    {
        $this->assertEquals('\'ab\'c\'', $this->_object->quote("ab'c"));
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getDbName
     */
    public function testGetDbName()
    {
        $this->assertEquals($this->_configs['database']['db'], $this->_object->getDbName());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getNumQuerys
     */
    public function testGetNumQuerys()
    {
        $this->assertEquals(0, $this->_object->getNumQuerys());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getQuery
     */
    public function testGetQuery()
    {
        $this->assertNull($this->_object->getQuery());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getQueryStmts
     */
    public function testGetQueryStmts()
    {
        $this->assertEquals(array(), $this->_object->getQueryStmts());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getQueryCompareValues
     */
    public function testgetQueryCompareValues()
    {
        $expected = array(
            'AND' => array(_CMS_AND, _CMS_AND),
            'OR' => array(_CMS_OR, _CMS_OR),
        );
        $this->assertEquals($expected, $this->_object->getQueryCompareValues());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::replaceQueryCompareValues
     */
    public function testReplaceQueryCompareValues()
    {
        $expected = array(
            'OR' => array(_CMS_OR, _CMS_OR),
            'AND' => array(_CMS_AND, _CMS_AND),
        );
        $this->_object->replaceQueryCompareValues($expected);

        $this->assertEquals($expected, $this->_object->getQueryCompareValues());

        $x = $this->_object->replaceQueryCompareValues(array(1, 2, 3, 4));
        $this->assertEquals('Invalid query compare value configuration', $x[0]);
        $this->assertNull($x[1]);
        $this->assertNull($x[2]);
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getQueryOperators
     * @covers Mumsys_Db_Driver_Abstract::replaceQueryOperators
     */
    public function testGetQueryOperators()
    {
        $expected1 = array(
            '=' => array('==', _CMS_ISEQUAL),
            '>' => array('&gt;', _CMS_ISGREATERTHAN),
            '<' => array('&lt;', _CMS_ISLESSTHAN),
            '>=' => array('&gt;=', _CMS_ISGREATERTHANOREQUAL),
            '<=' => array('&lt;=', _CMS_ISLESSTHANOREQUAL),
            '!=' => array('!=', _CMS_ISNOTEQUAL),
            'LIKE' => array(_CMS_CONTAINS, _CMS_CONTAINS),
            'NOTLIKE' => array(_CMS_CONTAINS_NOT, _CMS_CONTAINS_NOT),
            'xLIKE' => array(_CMS_ENDSWITH, _CMS_ENDSWITH),
            'xNOTLIKE' => array(_CMS_ENDSNOTWITH, _CMS_ENDSNOTWITH),
            'LIKEx' => array(_CMS_BEGINSWITH, _CMS_BEGINSWITH),
            'NOTLIKEx' => array(_CMS_BEGINSNOTWITH, _CMS_BEGINSNOTWITH),
        );
        $expected2 = array('=' => 'eg');

        $actual1 = $this->_object->getQueryOperators();
        $this->_object->replaceQueryOperators(array('=' => 'eg'));
        $actual2 = $this->_object->getQueryOperators();

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);

        $x = $this->_object->replaceQueryOperators(array(1, 2, 3, 4));
        $this->assertEquals('Invalid query operators configuration', $x[0]);
        $this->assertNull($x[1]);
        $this->assertNull($x[2]);
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getQuerySortations
     */
    public function testgetQuerySortations()
    {
        $expected = array(
            'ASC' => 'Ascending (a-z, 0-9)',
            'DESC' => 'Descending (z-a, 9-0)',
        );
        $this->assertEquals($expected, $this->_object->getQuerySortations());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getQuerySortations
     * @covers Mumsys_Db_Driver_Abstract::replaceQuerySortations
     */
    public function testReplaceQuerySortations()
    {
        $expected = array(
            'DESC' => 'Descending (z-a, 9-0)',
            'ASC' => 'Ascending (a-z, 0-9)',
        );
        $this->_object->replaceQuerySortations($expected);
        $this->assertEquals($expected, $this->_object->getQuerySortations());

        $x = $this->_object->replaceQuerySortations(array(1, 2, 3, 4));
        $this->assertEquals('Invalid query sortations configuration', $x[0]);
        $this->assertNull($x[1]);
        $this->assertNull($x[2]);
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getErrors
     */
    public function testGetErrors()
    {
        $this->assertEquals(array(), $this->_object->getErrors());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getErrorMessage
     */
    public function testGetErrorMessage()
    {
        $this->assertNull($this->_object->getErrorMessage());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getErrorCode
     */
    public function testGetErrorCode()
    {
        $this->assertNull($this->_object->getErrorCode());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getThrowErrors
     * @covers Mumsys_Db_Driver_Abstract::setThrowErrors
     */
    public function testGetSetThrowErrors()
    {
        $this->_object->setThrowErrors(1);
        $this->assertTrue($this->_object->getThrowErrors());

        $this->_object->setThrowErrors(0);
        $this->assertFalse($this->_object->getThrowErrors());
    }


    /**
     * @covers Mumsys_Db_Driver_Abstract::getDebugMode
     * @covers Mumsys_Db_Driver_Abstract::setDebugMode
     */
    public function testGetSetDebugMode()
    {
        $this->_object->setDebugMode(1);
        $this->assertTrue($this->_object->getDebugMode());

        $this->_object->setDebugMode(0);
        $this->assertFalse($this->_object->getDebugMode());
    }

}
