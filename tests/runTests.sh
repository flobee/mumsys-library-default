#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [phpunit options] <file or dir>";
echo "------------------------------------------";

php ../vendor/bin/phpunit  --colors --configuration phpunit.xml --bootstrap ./bootstrap.php --no-coverage $*
