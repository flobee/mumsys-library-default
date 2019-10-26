<?php

/**
 * Test constants used for unit tests
 */

define( '_NL', PHP_EOL );
define( 'MUMSYS_REGEX_AZ09', '/^([0-9a-zA-Z])+$/i' );
define( 'MUMSYS_REGEX_ALNUM', '/^([_]|[0-9a-zA-Z])+$/i' );
define( 'MUMSYS_REGEX_AZ09X', '/^([_-]|[0-9a-zA-Z])+$/i' );

/**
 * Datetime string like mysql uses it eg: YYYY-MM-DD HH:ii:ss
 */
define( 'MUMSYS_REGEX_DATETIME_MYSQL', '/^(\d{4})-(\d{2})-(\d{2} (\d{1,2}):(\d{1,2}):(\d{1,2}))$/' );
define( 'MUMSYS_REGEX_AZ09X_', '/^([ ]|[_-]|[0-9a-zA-Z])+$/i' );
