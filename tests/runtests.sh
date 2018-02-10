#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [phpunit options] <file or dir>";
echo "------------------------------------------";

pathCodeCoverage='../docs/CodeCoverage/';
codeCoverage="--coverage-html $pathCodeCoverage";
verbose="--verbose";

php ../vendor/bin/phpunit  --colors --configuration phpunit.xml --bootstrap ./bootstrap.php $codeCoverage $verbose $*
