#!/bin/sh

#echo 'running benchmarks';

echo "------------------------------------------";
echo "usage: $0 [phpbench options] <file or dir>";
echo "------------------------------------------";

CUR_DIR="$(dirname $(readlink -f "$0"))";
# source BASEDIR, PHP_BIN, PHP_PHING, PHP_BENCH locations
if [ -f "${CUR_DIR}/../.env" ] ;
then
    . "${CUR_DIR}/../.env";
else
    echo "Source default .env-dist please setup the ".env" file !!!";
    . "${CUR_DIR}/../.env-dist";
fi


# --store

cmd="${PHP_BENCH} run "$*" --report=aggregate --bootstrap=${BASEDIR}/tests/bootstrap.php --config=${BASEDIR}/tests/phpbench.json"
echo "Running:\n----------\n$cmd\n----------\n";
$cmd;