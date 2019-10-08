#!/bin/sh

PROGRAM='phpstan';

CUR_DIR="$(dirname $(readlink -f "$0"))";
# source BASEDIR, PHP_BIN, PHP_PHING locations
if [ -f "${CUR_DIR}/../.env" ] ;
then
    . "${CUR_DIR}/../.env";
else
    echo "Source default .env-dist please setup the ".env" file !!!";
    . "${CUR_DIR}/../.env-dist";
fi

# init program

if [ ! -f "${BASEDIR}/vendor/bin/${PROGRAM}" ];
then
    echo "${PROGRAM} not available";
    exit 1;
fi

${PHP_BIN} ${BASEDIR}/vendor/bin/${PROGRAM} $*
