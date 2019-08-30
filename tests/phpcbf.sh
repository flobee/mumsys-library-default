#!/bin/sh

echo "----------------------------------------";
echo "usage: $0 [phpcbf options] <path or file>";
echo "----------------------------------------";

CUR_DIR="$(dirname "$0")";
. "${CUR_DIR}/phpcs-base.sh";
CS_BIN="${CUR_DIR}/../vendor/bin/phpcbf"
${PHP_BIN} ${CS_BIN} ${STANDARD} ${IGNORELINE} $*
