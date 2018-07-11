#!/bin/sh

echo "----------------------------------------------";
echo "usage: $0 [phpunit options] <test dir or file>";
echo "----------------------------------------------";

phpunit_bin='../vendor/bin/phpunit';

php $phpunit_bin --colors --configuration phpunit.xml --bootstrap ./bootstrap.php $*
