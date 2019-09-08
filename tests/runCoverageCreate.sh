#!/bin/sh

#echo 'running tests';

echo "------------------------------------------";
echo "usage: $0 [phpunit options] <file or dir>";
echo "------------------------------------------";

CUR_DIR="$(dirname $(readlink -f "$0"))";
# source BASEDIR, PHP_BIN, PHP_PHING locations
if [ -f "${CUR_DIR}/../.env" ] ;
then
    . "${CUR_DIR}/../.env";
else
    echo "Source default .env-dist please setup the ".env" file !!!";
    . "${CUR_DIR}/../.env-dist";
fi

# setup: $ ./phpunit --dump-xdebug-filter build/xdebug-filter.php
COVERAGE_SPEEDUP='';
if [ -f "${BASEDIR}/tests/build/xdebug-filter.php" ]; then
    COVERAGE_SPEEDUP='--prepend build/xdebug-filter.php';
    echo "Using also: '${COVERAGE_SPEEDUP}'";
fi

${PHP_BIN} ${BASEDIR}/vendor/bin/phpunit  --colors \
    --configuration ${BASEDIR}/tests/phpunit-coverage.xml \
    --bootstrap ${BASEDIR}/tests/bootstrap.php \
    ${COVERAGE_SPEEDUP} $*
