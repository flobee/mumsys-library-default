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

CUR_DIR="$(dirname "$0")";
. "${CUR_DIR}/phpcs-base.sh";
CS_BIN="${CUR_DIR}/../vendor/bin/phpcs";
${PHP_BIN} ${CS_BIN} ${STANDARD} ${IGNORELINE} $*

echo "--------------------------------------------------------------------------------";
echo "Give maximum attention to ERRORS try to fix WARNINGS!";
echo "--------------------------------------------------------------------------------";
