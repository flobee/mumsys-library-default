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

/usr/bin/php -d memory_limit=128M -d include_path=".:/usr/share/php:../src" /usr/bin/phpunit --colors --configuration phpunit.xml $codeCoverage $verbose ./
