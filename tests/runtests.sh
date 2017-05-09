#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [verbose] [enableCodeCoverage] [debug]";
echo "------------------------------------------";

pathCodeCoverage='../docs/CodeCoverage/';
codeCoverage="";
verbose="";

target=/tmp/;

if [ "$1" != "" ]; then
	verbose="--verbose"
fi

if [ "$2" != "" ]; then
	codeCoverage="--coverage-html $pathCodeCoverage"
fi

php7.0 ../vendor/bin/phpunit  --colors --configuration phpunit.xml --bootstrap ./bootstrap.php $codeCoverage $verbose ./src/
