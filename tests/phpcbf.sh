#!/bin/sh

echo "----------------------------------------";
echo "usage: $0 [phpcbf options] <path or file>";
echo "----------------------------------------";

CUR_DIR="$(dirname $(readlink -f "$0"))";
# source BASEDIR, PHP_BIN, PHP_PHING locations
if [ -f "${CUR_DIR}/../.env" ] ;
then
    . "${CUR_DIR}/../.env";
else
    echo "Source default .env-dist please setup the ".env" file !!!";
    . "${CUR_DIR}/../.env-dist";
fi

. "${BASEDIR}/tests/phpcs-base.sh";
CS_BIN="${BASEDIR}/vendor/bin/phpcbf"
${PHP_BIN} ${CS_BIN} ${STANDARD} ${IGNORELINE} $*
