#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [phpunit options] <file or dir>";
echo "------------------------------------------";

php ../vendor/bin/phpunit  --colors --configuration phpunit-coverage.xml --bootstrap ./bootstrap.php $*
