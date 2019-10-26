<?php


/**
 * Callback test class
 */
class Mumsys_Variable_Manager_DefaultTest_CBClass
{
    const VERSION = '0.0.0';

    public static function runA( Mumsys_Variable_Item_Interface $item,
        $data = null, $params = null )
    {
        unset( $item, $data, $params );
        return true;
    }


    public static function runB( Mumsys_Variable_Item_Interface $item,
        $data = null, $params = null )
    {
        unset( $item, $data, $params );
        return true;
    }


    public static function runC( Mumsys_Variable_Item_Interface $item,
        $data = null, $params = null )
    {
        unset( $item, $data, $params );
        return true;
    }


    public static function runFalse( Mumsys_Variable_Item_Interface $item,
        $data = null, $params = null )
    {
        unset( $item, $data, $params );
        return false;
    }

}


/**
 * Callback test function
 */
function Mumsys_Variable_Manager_DefaultTest_CBFunc( Mumsys_Variable_Item_Interface $item,
    $data = null, $params = null )
{
    unset( $item, $data, $params );
    return true;
}


/**
 * Mumsys_Variable_Manager_Default Test
 */
class Mumsys_Variable_Manager_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Variable_Manager_Default
     */
    protected $_object;

    /**
     * @var string
     */
    protected $_version = '1.3.4';

    /**
     * @var array
     */
    protected $_values = array();

    /**
     * @var array
     */
    protected $_config = array();


    protected function setUp(): void
    {
        $this->_config = array(
            'username' => array(
                'label' => 'Username',
                //type: string, array (list), email, numeric, float, integer, date, datetime, ipv4, ipv6
                'type' => 'string',
                'minlen' => 1,
                'maxlen' => 45,
                'allowEmpty' => false,
                'required' => true,
                'regex' => false,
                'default' => '',
                'value' => null,
                'errors' => array(),
            ),
        );
        $this->_values = array('username' => 'unittest');

        $this->_object = new Mumsys_Variable_Manager_Default( $this->_config, $this->_values );
    }


    protected function tearDown(): void
    {
        $this->_object = null;
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::__construct
     */
    public function test_construct()
    {
        // A
        $objectA = new Mumsys_Variable_Manager_Default( $this->_config, $this->_values );
        $this->_config['username']['name'] = 'username';
        $this->_config['username']['value'] = 'unittest';
        $expectedA = array(
            'username' => new Mumsys_Variable_Item_Default( $this->_config['username'] )
        );

        $this->assertInstanceOf( Mumsys_Variable_Manager_Default::class, $objectA );
        $this->assertInstanceOf( 'Mumsys_Variable_Manager_Interface', $objectA );
        $this->assertEquals( $expectedA, $this->_object->getItems() );

        // B
        $this->expectException( 'Mumsys_Variable_Manager_Exception' );
        $mesg = 'Item name "user" and item address "username" are not identical. '
            . 'Drop item "name" or "address" in config';
        $this->expectExceptionMessage( $mesg );
        $this->_config['username']['name'] = 'user';
        new Mumsys_Variable_Manager_Default( $this->_config, $this->_values );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validate
     */
    public function testValidate()
    {
        $item = $this->_object->getItem( 'username' );

        $actual1 = $this->_object->validate();

        $item->setValue( '' );
        $actual2 = $this->_object->validate();

        $this->assertTrue( $actual1 );
        $this->assertFalse( $actual2 );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateType
     */
    public function testValidateType()
    {
        $_types = array(
            'string' => 'test string',
            'array' => array('unittest'),
            'email' => 'thisisallowed@host.tld',
            'numeric' => '1234',
            'float' => 1.234,
            'integer' => 12345,
            'date' => '2000-12-31',
            'datetime' => '2000-12-31 23:58:59',
            'ipv4' => '127.0.0.1',
            'ipv6' => '::1',
            'unixtime' => '1234567890',
        );
        $item = $this->_object->getItem( 'username' );

        foreach ( $_types as $type => $value ) {
            $item->setType( $type );
            $item->setValue( $value );

            $actualA = $this->_object->validateType( $item );
            $this->assertTrue( $actualA, print_r( $item->getErrorMessages(), true ) );

            // generate failures
            switch ( $type ) {
                case 'string': $item->setValue( array($value) );
                    break;
                case 'array': $item->setValue( 'unittest' );
                    break;
                case 'email': $item->setValue( 'unittest@3' );
                    break;
                case 'numeric': $item->setValue( 'unittest' );
                    break;
                case 'float': $item->setValue( 'unittest' );
                    break;
                case 'integer': $item->setValue( 'unittest' );
                    break;
                case 'date': $item->setValue( 'unittest' );
                    break;
                case 'datetime':$item->setValue( 'unittest' );
                    break;
                case 'ipv4': $item->setValue( 'localhost' );
                    break;
                case 'ipv6': $item->setValue( 'localhost' );
                    break;
                case 'unixtime': $item->setValue( 'string' );
                    break;
            }
            $actualB = $this->_object->validateType( $item );
            $this->assertFalse( $actualB );
        }

        $this->expectExceptionMessageRegExp( '/(Type "unittest" not implemented)/i' );
        $this->expectException( 'Mumsys_Variable_Manager_Exception' );
        $item->setType( 'unittest' );
        $this->_object->validateType( $item );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateMinMax
     */
    public function testValidateMinMax()
    {
        $_types = array(
            'string' => 'test',
            'integer' => 4,
            'float' => 4.123,
            'numeric' => '4.123',
        );
        $item = $this->_object->getItem( 'username' );

        foreach ( $_types as $type => $value ) {
            $item->setType( $type );
            $item->setValue( $value );

            $item->setMinLength( 1 );
            $item->setMaxLength( 4.123 );

            $actualA = $this->_object->validateMinMax( $item );
            $this->assertTrue( $actualA );

            // generate failures
            $item->setMinLength( 5 );
            $item->setMaxLength( 1 );
            $actualB = $this->_object->validateMinMax( $item );
            $this->assertFalse( $actualB );
        }

        // for code coverage
        $itemC = $this->_object->createItem( array('value' => array('unittest', 'a'=>'b', 'c'=>'d')) );
        $actualC = $this->_object->validateMinMax( $itemC );
        $this->assertTrue( $actualC ); // no min/max set, just return

        $itemC->setType( 'array' );
        $itemC->setMinLength( 4 );
        $itemC->setMaxLength( 1 );
        $actualD = $this->_object->validateMinMax( $itemC );
        $this->assertFalse( $actualD );
    }

    /**
     * @covers Mumsys_Variable_Manager_Default::validateMinMax
     */
    public function testValidateMinMaxUnknownTypeError()
    {
        $this->_config['username']['type'] = 'unknowntype';
        $object = new Mumsys_Variable_Manager_Default( $this->_config, $this->_values );
        $item = $object->getItem( 'username' );

        $actualA = $this->_object->validateMinMax( $item );
        $actualB = $item->getErrorMessages();

        $this->assertFalse( $actualA );
        $this->assertTrue( ( count( $actualB ) === 1 ) );
        $this->assertTrue( ( key( $actualB )  === 'MINMAX_TYPE_ERROR' ) );
        $this->assertEquals(
            'Min/max type error "unknowntype". Must be "string", "integer", "numeric", "float" or "double"',
            reset( $actualB )
        );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateRegex
     */
    public function testValidateRegex()
    {
        $item = $this->_object->getItem( 'username' );
        $item->setValue( 'uNiTtEsT' );
        $item->setRegex( '/^(unittest)$/i' );

        $actual1 = $this->_object->validateRegex( $item );

        $item->setRegex( '/^(somtest)$/i' );
        $actual2 = $this->_object->validateRegex( $item );

        // regex error
        $displayErrors = ini_get( 'display_errors' );
        $errorReporting = ini_get( 'error_reporting' );

        $item->setRegex( '\d' ); // invalid regex / syntax error

        ini_set( 'display_errors', false );
        error_reporting( 0 );

        $actual3 = $this->_object->validateRegex( $item );

        ini_set( 'display_errors', $displayErrors );
        error_reporting( $errorReporting );

        $this->assertTrue( $actual1 );
        $this->assertFalse( $actual2 );
        $this->assertFalse( $actual3 );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateIpv4
     */
    public function testValidateIPv4()
    {
        $item = $this->_object->getItem( 'username' );
        $item->setType( 'ipv4' );
        $item->setValue( '11.22.33.44' );
        $actual1 = $this->_object->validateIpv4( $item );

        $item2 = clone $item;
        $item2->setValue( 'noipv4' );
        $actual2 = $this->_object->validateIpv4( $item2 );

        $this->assertTrue( $actual1 );
        $this->assertFalse( $actual2 );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::validateIpv6
     */
    public function testValidateIPv6()
    {
        $item = $this->_object->getItem( 'username' );
        $item->setType( 'ipv6' );
        $item->setValue( '::1' );
        $actual1 = $this->_object->validateIpv6( $item );

        $item2 = clone $item;
        $item2->setValue( 'noipv6' );
        $actual2 = $this->_object->validateIpv6( $item2 );

        $this->assertTrue( $actual1 );
        $this->assertFalse( $actual2 );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::isValid
     * @covers Mumsys_Variable_Manager_Default::validateType
     * @covers Mumsys_Variable_Manager_Default::validateMinMax
     * @covers Mumsys_Variable_Manager_Default::validateRegex
     */
    public function testIsValid()
    {
        $item = $this->_object->getItem( 'username' );
        $actual1 = $this->_object->isValid( $item );

        // generate failures for code coverage

        $item->setValue( '' );
        $item->setAllowEmpty( true );
        $actual2 = $this->_object->isValid( $item );

        $item->setValue( null );
        $item->setRequired( true );
        $item->setAllowEmpty( false );
        $actual3 = $this->_object->isValid( $item );

        $item->setRequired( false );
        $item->setAllowEmpty( false );
        $actual4 = $this->_object->isValid( $item );

        $item->setRegex( '/(\d)/' );
        $item->setValue( 'unittest' );
        $actual5 = $this->_object->isValid( $item );

        $this->assertTrue( $actual1 );
        $this->assertTrue( $actual2 );
        $this->assertFalse( $actual3 );
        $this->assertFalse( $actual4 );
        $this->assertFalse( $actual5 );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getItem
     * @covers Mumsys_Variable_Manager_Default::getItems
     */
    public function testGetItems()
    {
        $this->_config['username']['name'] = 'username';
        $this->_config['username']['value'] = 'unittest';
        $expected = array(
            'username' => new Mumsys_Variable_Item_Default( $this->_config['username'] ));
        $this->assertEquals( $expected, $this->_object->getItems() );
        $this->assertEquals( $expected['username'], $this->_object->getItem( 'username' ) );
        $this->assertFalse( $this->_object->getItem( 'unknown' ) );
    }


    public function testRegisterItem()
    {
        $item = $this->_object->getItem( 'username' );
        $this->_object->registerItem( 'user2', $item );

        $this->assertEquals( $item, $this->_object->getItem( 'user2' ) );

        $this->expectExceptionMessageRegExp( '/(Item "username" already set)/i' );
        $this->expectException( 'Mumsys_Variable_Manager_Exception' );
        $this->_object->registerItem( 'username', $item );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::createItem
     */
    public function testCreateItem()
    {
        $expected = new Mumsys_Variable_Item_Default( $this->_config['username'] );
        $actual = $this->_object->createItem( $this->_config['username'] );
        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getErrorMessages
     */
    public function testGetErrormessages()
    {
        $this->_config['username']['errors'] = array('REQUIRED_MISSING' => 'Missing required value');
        $item = new Mumsys_Variable_Item_Default( $this->_config['username'] );

        $this->_object->registerItem( 'testuser', $item );
        $actual = $this->_object->getErrorMessages();
        $expected = array('testuser' => array('REQUIRED_MISSING' => 'Missing required value'));

        $this->assertEquals( $expected, $actual );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::getMessageTemplates
     * @covers Mumsys_Variable_Manager_Default::setMessageTemplates
     */
    public function testGetSetMessageTemplates()
    {
        $expected = array(
            'REQUIRED_MISSING' => 'Missing required value',
            'ALLOWEMPTY_ERROR' => 'Missing value',
            'REGEX_FAILURE' => 'Value "%1$s" does not match the regex rule: "%2$s"',
            'REGEX_ERROR' => 'Error in regular expression',
            'TYPE_INVALID_STRING' => 'Value (json):"%1$s" is not a "string"',
            'TYPE_INVALID_ARRAY' => 'Value (json):"%1$s" is not an "array"',
            'TYPE_INVALID_EMAIL' => 'Value "%1$s" is not a valid value for type "email"',
            'TYPE_INVALID_NUMERIC' => 'Value (json):"%1$s" is not a "numeric" type',
            'TYPE_INVALID_FLOAT' => 'Value (json):"%1$s" is not a "float" type',
            'TYPE_INVALID_INT' => 'Value (json):"%1$s" is not an "integer" type',
            'TYPE_INVALID_DATE' => 'Value (json):"%1$s" is not a "date" type',
            'TYPE_INVALID_DATETIME' => 'Value (json):"%1$s" is not a "datetime" type',
            'MINMAX_TOO_SHORT_STR' => 'Value "%1$s" must contain at least "%2$s" characters',
            'MINMAX_TOO_LONG_STR' => 'Value "%1$s" must contain maximum of "%2$s" characters, "%3$s" given',
            'MINMAX_TOO_SHORT_NUM' => 'Value "%1$s" must be minimum "%2$s"',
            'MINMAX_TOO_LONG_NUM' => 'Value "%1$s" can be maximum "%2$s"',
            'MINMAX_TYPE_ERROR' => 'Min/max type error "%1$s". Must be "string", "integer", '
            . '"numeric", "float" or "double"',
        );

        $actual1 = $this->_object->getMessageTemplates();
        $actual2 = $actual1 = $this->_object->getMessageTemplates( $this->_object->setMessageTemplates( $expected ) );

        $this->assertEquals( $expected, $actual1 );
        $this->assertEquals( $expected, $actual2 );
        $this->assertEquals( count( $expected ), count( $actual1 ) );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::setMessageTemplate
     */
    public function testSetSingleMessage()
    {
        $this->_object->setMessageTemplate( 'unittest', 'Unittest template message' );
        $actual1 = $this->_object->getMessageTemplates();

        $this->assertEquals( $actual1['unittest'], 'Unittest template message' );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::setAttributes
     */
    public function testGetSetAttributes()
    {
        // value for some items
        $attributesA = array('values' => array('username' => 'unittest value'));
        $this->_object->setAttributes( $attributesA );

        $itemsA = $this->_object->getItems();
        foreach ( $itemsA as $item ) {
            $this->assertEquals( 'unittest value', $item->getValue() );
        }

        // value for all items
        $attributesB = array('value' => '2nd. unittest value');
        $this->_object->setAttributes( $attributesB );
        $itemsB = $this->_object->getItems();
        foreach ( $itemsB as $item ) {
            $this->assertEquals( '2nd. unittest value', $item->getValue() );
        }

        // labels for some items
        $attributesC = array('labels' => array('username' => 'unittest label'));
        $this->_object->setAttributes( $attributesC );
        $itemC = $this->_object->getItem( 'username' );
        $this->assertEquals( 'unittest label', $itemC->getLabel() );

        // "state" for all items
        $attributesD = array('state' => 'onSave');
        $this->_object->setAttributes( $attributesD );
        $itemD = $this->_object->getItem( 'username' );
        $this->assertEquals( 'unittest label', $itemD->getLabel() );

        $this->expectExceptionMessageRegExp( '/(Set item attributes for "unittest" not implemented)/i' );
        $this->expectException( 'Mumsys_Variable_Manager_Exception' );
        $attributesE = array('unittest' => 'throw an exception');
        $this->_object->setAttributes( $attributesE );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::toArray
     */
    public function testToArray()
    {
        $actual1 = $this->_object->toArray();
        $expected1 = array('username' => 'unittest');

        $itmProps = array(
            'name' => 'unittest2',
            'value' => 'Unittest 2',
        );
        $newItem = $this->_object->createItem( $itmProps );
        $this->_object->registerItem( 'unittest.unittest', $newItem );
        $actual2 = $this->_object->toArray( true );
        $expected2 = array('username' => 'unittest', 'unittest.unittest' => 'Unittest 2');

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
    }


    /**
     * Just 4 code coverage.
     * @covers Mumsys_Variable_Manager_Default::externalsApply
     */
    public function testExternalsApply()
    {
        $this->_object->externalsApply();

        $actual = $this->_object->getErrorMessages();
        $expected = array();
        $this->assertEquals( $expected, $actual );
    }


    /**
     * Just 4 code coverage.
     * @covers Mumsys_Variable_Manager_Default::filtersApply
     */
    public function testFiltersApply()
    {
        $this->_object->filtersApply();
        $actualA = $this->_object->getErrorMessages();

        $this->_config['username']['filters'] = array(
            'onView' => array(
                'is_array',
            )
        );

        $object = new Mumsys_Variable_Manager_Default( $this->_config, $this->_values );
        $object->filtersApply();
        $actualB = $object->getErrorMessages();
        $expectedB = array(
            'username' => array(
                'FILTER_ERROR' => 'Filter "is_array" failt for label/name: "Username"'
            )
        );

        $this->assertEquals( array(), $actualA );
        $this->assertEquals( $expectedB, $actualB );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::filterItem
     * @covers Mumsys_Variable_Manager_Default::_execExternal
     */
    public function testFilterItem()
    {
        $itmProps = array(
            'name' => 'unittest',
            'value' => ' unittest & test value ',
            'filters' => array(
                'onSave' => array(
                    'trim',
                    'htmlentities' => array('%value%'),
                    'htmlspecialchars' => '%value%',
                    'str_replace' => array(';amp', '', '%value%'),
                    'functionNotExistsError' => 'parameterAsString',
                ),
                'onEdit' => array(
                    'htmlspecialchars' => 'newresult',
                    'htmlentities'
                ),
            )
        );
        $newItem = $this->_object->createItem( $itmProps );
        $this->_object->registerItem( 'unittest', $newItem );

        // default state: "onView"
        $newItem->stateSet( 'onView' );
        $expected1 = ' unittest & test value ';
        $actual1 = $this->_object->filterItem( $newItem );
        $actual2 = $newItem->getValue();

        $expected2 = 'unittest &amp; test value';
        $newItem->stateSet( 'onSave' );
        $actual3 = $this->_object->filterItem( $newItem );
        $actual4 = $newItem->getValue();

        $actual5 = $this->_object->getErrorMessages();
        $expected5 = array(
            'unittest' => array(
                'FILTER_NOTFOUND' => 'Filter function "functionNotExistsError" not found for item: "unittest"')
        );

        $newItem->stateSet( 'onEdit' );
        $actual6 = $this->_object->filterItem( $newItem );
        $expected66 = 'newresult';
        $actual66 = $newItem->getValue();

        $newItem->setValue( false );
        $newItem->stateSet( 'onSave' );
        $newItem->filterAdd( 'onSave', 'is_object' );
        $actual7 = $this->_object->filterItem( $newItem );
        $expected7 = $newItem->getValue();
        $actual8 = $this->_object->getErrorMessages(); //$newItem->getValue();
        $expected8 = array(
            'unittest' => array(
                'FILTER_NOTFOUND' => 'Filter function "functionNotExistsError" not found for item: "unittest"',
                'FILTER_ERROR' => 'Filter "is_object" failt for label/name: "unittest"',
            )
        );

        $this->assertTrue( $actual1 );
        $this->assertEquals( $expected1, $actual2 );
        $this->assertFalse( $actual3 ); // err
        $this->assertEquals( $expected2, $actual4 ); // in=out on errors
        $this->assertEquals( $expected5, $actual5 );
        $this->assertTrue( $actual6 );
        $this->assertEquals( $expected66, $actual66 );
        $this->assertEquals( $expected7, $actual7 ); // casted to empty string
        $this->assertEquals( $expected8, $actual8 );
    }


    /**
     * Just 4 code coverage.
     * @covers Mumsys_Variable_Manager_Default::callbacksApply
     * @covers Mumsys_Variable_Manager_Default::callbackItem
     */
    public function testCallbacksApply()
    {
        $this->_object->callbacksApply();

        $itmProps = array(
            'name' => 'unittest',
            'value' => ' unittest & test value ',
            'callbacks' => array(
                'onView' => array(
                    'Mumsys_Variable_Manager_DefaultTest_CBFunc' => array('%value%', 12, 34, 56),
                    'Mumsys_Variable_Manager_DefaultTest_CBClass::runA' => '%value%',
                    'Mumsys_Variable_Manager_DefaultTest_CBClass::runB' => '123',
                    'Mumsys_Variable_Manager_DefaultTest_CBClass::runC',
                    'callbackNotExists',
                    'Mumsys_Variable_Manager_DefaultTest_CBClass::runFalse',
                ),
            )
        );
        $newItem = $this->_object->createItem( $itmProps );
        $this->_object->registerItem( 'unittest', $newItem );
        $actualA = $this->_object->callbackItem( $newItem );
        $expectedA = false;

        $actualB = $this->_object->getErrorMessages();
        $expectedB = array(
            'unittest' => array(
                'CALLBACK_NOTFOUND' => 'Callback function "callbackNotExists" not found for item: "unittest"',
                'CALLBACK_ERROR' => 'Callback "Mumsys_Variable_Manager_DefaultTest_CBClass::runFalse" '
                . 'for "unittest" failt for value (json): "true"',
            )
        );

        $actualC = $this->_object->callbacksApply();

        $this->assertEquals( $expectedA, $actualA );
        $this->assertEquals( $expectedB, $actualB );
        $this->assertFalse( $actualC );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::compare
     */
    public function testCompare()
    {
        $oItemA = $this->_object->getItem( 'username' );
        $oItemB = clone $oItemA;

        // genaral tests

        // == tests
        $actualA = $this->_object->compare( $oItemA, $oItemB, '==' );
        $actualB = $this->_object->compare( $oItemA, $oItemB, 'eq' );
        $actualC = $this->_object->compare( $oItemA, $oItemB, '===' );
        $actualD = $this->_object->compare( $oItemA, $oItemB, 'type_eq' );
        // != tests
        $actualE = $this->_object->compare( $oItemA, $oItemB, 'neq' );
        $actualF = $this->_object->compare( $oItemA, $oItemB, 'type_neq' );

        // <, > tests
        $actualG = $this->_object->compare( $oItemA, $oItemB, '<' );
        $actualH = $this->_object->compare( $oItemA, $oItemB, 'lt' );

        $actualI = $this->_object->compare( $oItemA, $oItemB, '<=' );
        $actualJ = $this->_object->compare( $oItemA, $oItemB, 'lte' );

        $actualK = $this->_object->compare( $oItemA, $oItemB, '>' );
        $actualL = $this->_object->compare( $oItemA, $oItemB, 'gt' );

        $actualM = $this->_object->compare( $oItemA, $oItemB, '>=' );
        $actualN = $this->_object->compare( $oItemA, $oItemB, 'gte' );

        //
        // comparisions

        $this->assertTrue( $actualA );
        $this->assertTrue( $actualB );
        $this->assertTrue( $actualC );
        $this->assertTrue( $actualD );

        $this->assertFalse( $actualE );
        $this->assertFalse( $actualF );

        $this->assertFalse( $actualG );
        $this->assertFalse( $actualH );

        $this->assertTrue( $actualI );
        $this->assertTrue( $actualJ );

        $this->assertFalse( $actualK );
        $this->assertFalse( $actualL );

        $this->assertTrue( $actualM );
        $this->assertTrue( $actualN );

        $this->expectException( 'Mumsys_Variable_Manager_Exception' );
        $this->expectExceptionMessage( 'Operator "XX" not implemented' );
        $this->_object->compare( $oItemA, $oItemB, 'XX' );
    }


    /**
     * @covers Mumsys_Variable_Manager_Default::compare
     */
    public function testCompareExceptionA()
    {
        $oItemA = $this->_object->getItem( 'username' );
        $oItemB = clone $oItemA;
        $oItemB->setType( 'integer' );

        $this->assertNotEquals( $oItemA, $oItemB );

        //$this->expectException('Mumsys_Variable_Manager_Exception');
        $this->expectExceptionMessage( 'Invalid types. Type of item A: "string", item B "integer"' );
        $this->_object->compare( $oItemA, $oItemB, '==' );
    }


    /**
     * Tests constants
     */
    public function testConstants()
    {
        $message = 'A new version exists. You should have a look at '
            . 'the code coverage to verify all code was tested and not only '
            . 'all existing tests where checked!';
        $this->assertEquals( $this->_version, Mumsys_Variable_Manager_Default::VERSION, $message );
    }

}
