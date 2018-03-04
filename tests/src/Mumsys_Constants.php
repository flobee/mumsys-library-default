<?php

/**
 * Constants Test
 * Test for required constants in mumsys
 */
class Mumsys_ConstantsTest
    extends Mumsys_Unittest_Testcase
{


    protected function setUp()
    {

    }


    protected function tearDown()
    {

    }


    public function testIncludedConstants()
    {
        $this->assertEquals( MUMSYS_REGEX_AZ09, '/^([0-9a-zA-Z])+$/i' );
        $this->assertEquals( MUMSYS_REGEX_ALNUM, '/^([_]|[0-9a-zA-Z])+$/i' );
        $this->assertEquals( MUMSYS_REGEX_AZ09X, '/^([_-]|[0-9a-zA-Z])+$/i' );
        $this->assertEquals( MUMSYS_REGEX_DATETIME_MYSQL, '/^(\d{4})-(\d{2})-(\d{2} (\d{1,2}):(\d{1,2}):(\d{1,2}))$/' );
        $this->assertEquals( MUMSYS_REGEX_AZ09X_, '/^([ ]|[_-]|[0-9a-zA-Z])+$/i' );

        $this->assertEquals( _NL, PHP_EOL );
    }

}
