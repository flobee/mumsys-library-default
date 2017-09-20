#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [path] [bool:verbose] [bool:codeCoverage]";
echo "------------------------------------------";

pathCodeCoverage='../docs/CodeCoverage/';
codeCoverage="";
verbose="";

target=/tmp/;

if [ "$2" != "" ]; then
	verbose="--verbose"
fi

if [ "$3" != "" ]; then
	codeCoverage="--coverage-html $pathCodeCoverage"
fi

/usr/bin/php -d memory_limit=128M -d include_path=".:/usr/share/php:../src" /usr/bin/phpunit --colors --no-configuration --bootstrap ./bootstrap.php $codeCoverage $verbose ./$3
