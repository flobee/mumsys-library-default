#!/bin/sh

# ../vendor/squizlabs/php_codesniffer/CodeSniffer/Standards/
#
# for phing
# (blacklist):
#    php vendor/bin/phpcs \
#    -n \
#    # --extensions=php \
#    --ignore=data/*,externals/*,helper/*,tmp/*,vendor/*,misc/* \
#    --standard=misc/coding/Mumsys \
#    <path>
#
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
CS_BIN="${BASEDIR}/vendor/bin/phpcs";
${PHP_BIN} ${CS_BIN} ${STANDARD} ${IGNORELINE} $*

echo "--------------------------------------------------------------------------------";
echo "Give maximum attention to ERRORS try to fix WARNINGS!";
echo "--------------------------------------------------------------------------------";
