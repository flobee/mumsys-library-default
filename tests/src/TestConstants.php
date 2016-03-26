<?php

/**
 * Constants Test
 * Tests if constats exists.
 */
class ConstantsTest extends MumsysTestHelper
{

    public function testIncludedConstants()
    {
        $this->assertEquals('MUMSYS_REGEX_ALNUM', '/^([_]|[0-9a-zA-Z])+$/i');
        $this->assertEquals('MUMSYS_REGEX_AZ09X', '/^([_-]|[0-9a-zA-Z])+$/i');
        $this->assertEquals('MUMSYS_REGEX_DATETIME_MYSQL', '/^(\d{4})-(\d{2})-(\d{2} (\d{1,2}):(\d{1,2}):(\d{1,2}))$/');
        //$this->assertEquals($expected, $actual);
    }