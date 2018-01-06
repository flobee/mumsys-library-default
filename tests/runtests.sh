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

if [ -f $* ]; then
    cript="./$*";
else 
    cript="./";
fi

php7.0 -d memory_limit=128M ../vendor/bin/phpunit  --colors --configuration phpunit.xml --bootstrap ./bootstrap.php $codeCoverage $verbose $cript

