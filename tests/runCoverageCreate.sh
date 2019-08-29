#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [phpunit options] <file or dir>";
echo "------------------------------------------";

_DIR=$(dirname "$0");

php $_DIR/../vendor/bin/phpunit  --colors --configuration $_DIR/phpunit-coverage.xml --bootstrap $_DIR/bootstrap.php $*
