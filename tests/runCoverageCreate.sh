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

${PHP_BIN} ${BASEDIR}/vendor/bin/phpunit  --colors \
    --configuration ${BASEDIR}/tests/phpunit-coverage.xml \
    --bootstrap ${BASEDIR}/tests/bootstrap.php $*
