<?php

/**
 * Test class for php class.
 */
class Mumsys_PhpTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Php
     */
    protected $object;

    protected $_testsDir;
    /**
     * Test are made vor version: ...
     * @var string
     */
    protected $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '3.1.1';

        $this->_testsDir = MumsysTestHelper::getTestsBaseDir();
        $this->object = new Mumsys_Php();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }


    public function test__get()
    {
        $this->assertEquals( strtoupper(substr(PHP_OS,0,3)) , Mumsys_Php::$os );
        $this->assertEquals( $this->object->os , Mumsys_Php::$os );
    }

    public function test__set()
    {
        if (PHP_VERSION_ID < 70000) {
            $get_magic_quotes_gpc = $this->object->get_magic_quotes_gpc;

            $this->assertEquals( 0 , $get_magic_quotes_gpc );

            $this->object->get_magic_quotes_gpc = true;
            $this->assertEquals( true , $this->object->get_magic_quotes_gpc );

            // set invalid, throw exception
            try {
                $this->object->unknownVariable = 'I\'m wrong at all';
                $this->fail("No Exception was thrown");
            } catch ( Exception $e ) {
                // all fine
            }

            $this->object->get_magic_quotes_gpc = $get_magic_quotes_gpc;
        } else {
            $actual = get_magic_quotes_gpc();
            $expected = false;
            $this->assertEquals($expected, $actual);

            $this->expectException('Mumsys_Php_Exception');
            $this->object->none = 123;
        }
    }

    public function testIs_int()
    {
        $this->assertTrue( Mumsys_Php::is_int( 0 ) );
        $this->assertTrue( Mumsys_Php::is_int( 12 ) );
        $this->assertTrue( Mumsys_Php::is_int( '12' ) );
        $this->assertTrue( Mumsys_Php::is_int( '1234' ) );
        $this->assertFalse( Mumsys_Php::is_int( 1.9 ) );
        $this->assertFalse( Mumsys_Php::is_int( '1.9' ) );
        $this->assertFalse( Mumsys_Php::is_int( '1.9999' ) );
        $this->assertFalse( Mumsys_Php::is_int( '1k' ) );
    }

    /**
     * test_floatval
     */
    public function test_floatval()
    {
        $this->assertEquals( 1.2 , Mumsys_Php::floatval( '1.2' ) );
        $this->assertEquals( 1234.56 , Mumsys_Php::floatval( '1.234,56' ) );
        $this->assertEquals( 12 , Mumsys_Php::floatval( '12' ) );
        $this->assertEquals( 0.12345 , Mumsys_Php::floatval( '0,12345' ) );
        $this->assertEquals( 1234.56 , Mumsys_Php::floatval( '1.234,56' ) );
        $this->assertEquals( 1234.56 , Mumsys_Php::floatval( '1.234,56ABC' ) );
        $this->assertEquals( 1.23456 , Mumsys_Php::floatval( '1,234.56' ) );
    }

    /**
     * test_file_exists
     */
    public function test_file_exists()
    {
        $url = 'http://php.net/';
        $this->assertTrue( Mumsys_Php::file_exists($url) );
        // this will use php's file_exists()
        $this->assertTrue( Mumsys_Php::file_exists(__FILE__) );
        // using fopen to test existense
        $this->assertTrue( Mumsys_Php::file_exists('file://'.__FILE__) );
        // empty url
        $this->assertFalse( Mumsys_Php::file_exists() );
        // not existing url
        $this->assertFalse( Mumsys_Php::file_exists('file://noWay') );
    }


    /**
     * @covers Mumsys_Php::ini_get
     * @runInSeparateProcess
     */
    public function test_ini_get()
    {
        $oldLimit = Mumsys_Php::ini_get('memory_limit');

        $c = ini_set('memory_limit', '32M');
        $this->assertEquals((32 * 1048576), Mumsys_Php::ini_get('memory_limit'));
//
//        $c = ini_set('memory_limit', '1G');
//        $this->assertEquals(1073741824, Mumsys_Php::ini_get('memory_limit'));
//        $c = ini_set('memory_limit', '1T');
//        $this->assertEquals(1099511627776, Mumsys_Php::ini_get('memory_limit'));
//
//        $c = ini_set('memory_limit', '1P');
//        $this->assertEquals(1125899906842624, Mumsys_Php::ini_get('memory_limit'));

        $this->assertEquals(ini_get('display_errors'), Mumsys_Php::ini_get('display_errors'));
        $this->assertNull(Mumsys_Php::ini_get('html_errors'));

        $this->assertEquals('', Mumsys_Php::ini_get('hä?WhatsThis?') );
        $this->assertNull( Mumsys_Php::ini_get('') );


        ini_set('memory_limit', $oldLimit);
    }


    /**
     * @covers Mumsys_Php::str2bytes
     */
    public function test_str2bytes()
    {
        $tests = array(
            -1 => -1,
            0 => 0,
            1024 => 1024,
            '1k' => 1024,
            '1m' => 1024 * 1024,
            '1g' => 1024 * 1024 * 1024,
            '1t' => 1024 * 1024 * 1024 * 1024,
            '1p' => 1024 * 1024 * 1024 * 1024 * 1024,
        );
        foreach ( $tests as $key => $expected ) {
            $actual = $this->object->str2bytes($key);
            $message = $key . ' doesn\'t match ' . $expected;
            $this->assertEquals($expected, $actual, $message);
        }

        $this->expectException('Mumsys_Php_Exception');
        $this->expectExceptionMessage('Detection of size failt for "X"');
        $actual = $this->object->str2bytes('1X');
    }


    public function testIn_string()
    {
       $str = 'ABCDEFG';
       $this->assertEquals('CDEFG', Mumsys_Php::in_string('CDE', $str, $insensitive=false));
       $this->assertEquals('CDEFG', Mumsys_Php::in_string('cDe', $str, $insensitive=true));
       $this->assertEquals('AB', Mumsys_Php::in_string('CDE', $str, $insensitive=false, $before_needle=true ));
       $this->assertEquals('AB', Mumsys_Php::in_string('cDe', $str, $insensitive=true, $before_needle=true ));
    }

    /**
     * test_htmlspecialchars
     */
    public function test_htmlspecialchars()
    {
        // ENT_QUOTES
        $this->assertEquals('&amp;', Mumsys_Php::htmlspecialchars('&', ENT_QUOTES));
        $this->assertEquals('&amp; &amp;', Mumsys_Php::htmlspecialchars('& &amp;', ENT_QUOTES));
        // ENT_COMPAT -> only " > and <
        $this->assertEquals('&lt;a href=\'test\'&gt;&amp; Test&lt;/a&gt;', Mumsys_Php::htmlspecialchars('<a href=\'test\'>& Test</a>', ENT_COMPAT));
        // ENT_NOQUOTES no quotes translation
        $this->assertEquals('&lt;a href=\'test\' id="123"&gt;&amp; Test&lt;/a&gt;', Mumsys_Php::htmlspecialchars('<a href=\'test\' id="123">& Test</a>', ENT_NOQUOTES));
        // php vs my php function
        $phpphp = htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES);
        $myphp = Mumsys_Php::htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES);
        $this->assertEquals('&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;',$phpphp);
        $this->assertEquals('&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;',$myphp);

        // difference between htmlspecialchars and Mumsys_Php::htmlspecialchars
        $phpphp = htmlspecialchars( '&copy; &#169; &#982; &forall; &#8704; &#dasgibtsnicht; &#x3B1;', ENT_QUOTES );
        $myphp = Mumsys_Php::htmlspecialchars( '&copy; &#169; &#982; &forall; &#8704; &#dasgibtsnicht; &#x3B1;', ENT_QUOTES );
        $this->assertEquals($phpphp,$myphp);
    }

    public function test_xhtmlspecialchars()
    {
        $this->assertEquals('&', Mumsys_Php::xhtmlspecialchars('&amp;'));
        $this->assertEquals('<', Mumsys_Php::xhtmlspecialchars('&lt;'));
        $this->assertEquals('>', Mumsys_Php::xhtmlspecialchars('&gt;'));
        $this->assertEquals('"', Mumsys_Php::xhtmlspecialchars('&quot;'));
        $this->assertEquals("'", Mumsys_Php::xhtmlspecialchars('&#039;'));

        $this->assertEquals('"""', Mumsys_Php::xhtmlspecialchars('"&quot;"', ENT_COMPAT) );
        $this->assertEquals('&"&quot;"', Mumsys_Php::xhtmlspecialchars('&amp;"&quot;"', ENT_NOQUOTES) );

    }

    public function testPhp_nl2br()
    {
        $this->assertEquals("x<br />", Mumsys_Php::nl2br("x\n", true));
        $this->assertEquals("x<br /><br />", Mumsys_Php::nl2br("x\n\n", true) );

        $str1 = "<br />\nnew line<br />\nnew line<br />\n";
        $str2 = '<br /><br />new line<br /><br />new line<br /><br />';
        $this->assertEquals($str2, Mumsys_Php::nl2br($str1, true) );

        $this->assertEquals("x<br>", Mumsys_Php::nl2br("x\n", false));
    }


    public function test_br2nl()
    {
        $string = "test<br />";
        $result = "test\n";
        $this->assertEquals($result , Mumsys_Php::br2nl($string, "\n") );
    }


    public function test_parseUrl()
    {
        $url = 'https://host/path/file.php?query=value#fragment';

        $actual = $this->object->parseUrl('file:///');
        $expected = parse_url('file:///');
        $this->assertEquals($expected, $actual);

        $expected = parse_url('file://');
        $this->assertFalse($expected, '"file://" should return false "file:///" should be valid');

        $actual = $this->object->parseUrl($url);
        $expected = parse_url($url);
        $this->assertEquals($expected, $actual);

        $actual = $this->object->parseUrl($url, PHP_URL_SCHEME);
        $expected = parse_url($url, PHP_URL_SCHEME);
        $this->assertEquals($expected, $actual);
        $this->assertEquals('https', $actual);

//        try {
//            $this->object->parseUrl('file://'); // raise exception
//        } catch (Mumsys_Php_Exception $expected) {
//            return;
//        }
//        $this->fail('Mumsys_Php_Exception: "file://" not allowed, use "file:///" three slashes "///"!');

        $this->expectException('Mumsys_Php_Exception');
        $this->object->parseUrl('file://'); // raise exception
    }

    public function test_parseStr()
    {
        $url = 'https://host/path/file.php?query=value#fragment';

        $actual1 = $this->object->parseStr('file:///');
        parse_str('file:///', $expected1);
        $this->assertEquals($expected1, $actual1);

        $actual1 = $this->object->parseStr('abcde');
        parse_str('abcde', $expected1);
        $this->assertEquals($expected1, $actual1);

        $actual1 = $this->object->parseStr('a=b&c=d&e');
        parse_str('a=b&c=d&e', $expected1);
        $this->assertEquals($expected1, $actual1);
        // keys are arrays, values not!
        $actual1 = $this->object->parseStr('a[]=b&c[]=d[]&e[]');
        parse_str('a[]=b&c[]=d[]&e[]', $expected1);
        $this->assertEquals($expected1, $actual1);

        // empty string will throw error, php will return array()
        $this->expectException('Mumsys_Php_Exception');
        $this->expectExceptionMessageRegExp('/(Mumsys_Php::parseStr\(\) failt)/i');
        $actual1 = $this->object->parseStr('');
        // parse_str('', $expected1);
        // $this->assertEquals($expected1, $actual1);
    }

    public function testNumberPad()
    {
        $actual1 = $this->object->numberPad(123, 6, '0');
        $expected1 = '000123';

        $actual2 = $this->object->numberPad(123, 2, '0');
        $expected2 = '123';

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @todo: to be checked! "by reference!"
     */
    public function test_current()
    {
        $result = array('a','b');
        $a = next($result);
        $array = array('a','b');
        $b = next($array);

        $php = current($result);
        $my = Mumsys_Php::current($array);
        // b=b
        $this->assertEquals( $php , $my );
    }


    public function test_compareArray()
    {
        $have1 =   array('flo'=>'was', 'bee'=>'here', array('in'=>'side'));
        $totest1 = array('flo'=>'was', 'bee'=>'here', array('in'=>'side'));
        $res1 = Mumsys_Php::compareArray( $have1, $totest1, 'vals');
        $this->assertEquals( array() , $res1 );

        $have2 = array('flo'=>'was', 'bee'=>'here', array('in'=>'side', 'in2'=>'side2'), 'flo'=>'flo', 'was'=>'was');
        $totest2 = array('flo', 'was', 'here', 'flo'=>'flo', 'was'=>'was');
        $res2 = Mumsys_Php::compareArray( $have2, $totest2, 'vals');

        $have2 = array('flo'=>'was', 'bee'=>'here', array('in'=>'side', 'in2'=>'side2'), $have1);
        $totest2 = array('flo', 'was', 'here', $have1);
        $res2 = Mumsys_Php::compareArray( $have2, $totest2, 'keys');



        // to check! $this->assertEquals( array('flo'=>'was','bee'=>'here',array('in'=>'side') ) , $res2 );
    }


    /**
     * @covers Mumsys_Php::array_keys_search_recursive_check
     */
    public function testArray_keys_search_recursive_check()
    {
        $bigarray = array(
            'key1' => array(
                'key2' => array(
                    'a' => array('text' => 'something'),
                    'b' => array('id' => 737),
                    'c' => array('name' => 'me'),
                ),
                0 => array(
                    'a' => array('text' => 'something3'),
                    'b' => array('id' => 3),
                    'c' => array('name' => 'me3'),
                ),
            )
        );

        $matchedKeys1 = Mumsys_Php::array_keys_search_recursive_check( 'key1', $bigarray);
        $matchedKeys2 = Mumsys_Php::array_keys_search_recursive_check( 'name', $bigarray);
        $notFound = Mumsys_Php::array_keys_search_recursive_check('noKey', $bigarray);

        $this->assertTrue($matchedKeys1);
        $this->assertTrue($matchedKeys2);
        $this->assertFalse($notFound);
    }


    /**
     * @todo method not really working
     */
    public function testArray_keys_search_recursive()
    {
        $bigarray = array(
            'key1' => array(
                'key2' => array(
                    'a' => array('text' => 'something'),
                    'b' => array('id' => 1),
                    'c' => array('name' => 'me'),
                ),
                'key3' => array(
                    'a' => array('text' => 'something2'),
                    'b' => array('id' => 2),
                    'c' => array('name' => 'me2'),
                ),
                'key4' => array(
                    'a' => array('text' => 'something3'),
                    'b' => array('id' => 3),
                    'c' => array('name' => 'me3'),
                ),
            ),
            'namex' => 1,
        );
        $matchedKeys1 = Mumsys_Php::array_keys_search_recursive('key1', $bigarray, true);
        $this->assertEquals(array($bigarray), $matchedKeys1);

        $matchedKeys2 = Mumsys_Php::array_keys_search_recursive('name', $bigarray, false);
        $this->assertEquals(array(0 => array('name' => 'me'), 1 => array('name' => 'me2'), 2 => array('name' => 'me3')), $matchedKeys2);

        $matchedKeys3 = Mumsys_Php::array_keys_search_recursive('text', $bigarray, true);
        $this->assertEquals(array(0 => array('text' => 'something')), $matchedKeys3);

        // check reference,
        $matchedKeys1[0]['name'] = 'new value';
        // print_r($bigarray['key1']['key2']);
        // print_r($matchedKeys1);
        $this->assertEquals($matchedKeys2[0]['name'], $bigarray['key1']['key2']['c']['name']);
    }


    public function testArrayMergeRecursive()
    {
        // simple arrays test
        $array1 = array('name' => 'flobee', 'id' => 1, 0 => 123);
        $array2 = array('company' => 'some company', 'id' => 2);
        $array3 = array('phone' => 666666);
        $actual1 = $this->object->array_merge_recursive($array1, $array2, $array3);
        $expected1 = array(0 => 123, 'name' => 'flobee', 'id' => 2, 'company' => 'some company', 'phone' => 666666);

        // array recursiv check
        $array1 = array('record' => array('id' => 1, 'name' => 'flobee'));
        $array2 = array('record' => array('id' => 2, 'name' => 'user2'));
        $array3 = array('record' => array('phone' => 666666));
        $actual2 = $this->object->array_merge_recursive($array1, $array2, $array3);
        $expected2 = array('record' => array('id' => 2, 'name' => 'user2', 'phone' => 666666));

        // empyt arrays
        $array1 = array();
        $array2 = array();
        $actual3 = $this->object->array_merge_recursive($array1, $array2);
        $expected3 = array();

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);

        // not an array argurment exception
        $array1 = array('Uta ruf');
        $array2 = 'foo';
        $message = '/(Mumsys_Php::array_merge_recursive given argument is not an array "foo")/i';
        $this->expectException('Mumsys_Exception');
        $this->expectExceptionMessageRegExp($message);
        $this->object->array_merge_recursive($array1, $array2);
    }


    public function testArrayMergeRecursiveExceptionNumArgs()
    {
        $message = '/(Mumsys_Php::array_merge_recursive needs at least two arrays as arguments)/i';
        $this->expectException('Mumsys_Exception');
        $this->expectExceptionMessageRegExp($message);
        $this->object->array_merge_recursive(array());
    }


    public function test__callStatic()
    {
        // PHP >= 5.3.0 !!
        if ( PHP_VERSION_ID >= 50300 ) {
            // Mumsys_Php::strstr not implemented in class!
            $this->assertEquals( '12345' , Mumsys_Php::strstr('12345', '123') );
        }
        if ( PHP_VERSION_ID < 50300 ) {
            $this->markTestIncomplete( 'PHP < 5.3.0; Can not be called.' );
        }
    }


    public function test__call()
    {
        // call by callback of a nativ php function
        $this->assertEquals( 'ABCDEF' , $this->object->strstr('ABCDEF', 'ABC') );
    }


    public function testVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Php::VERSION);
    }
}
