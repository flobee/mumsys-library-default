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

cmd="${PHP_BENCH} run "$*" --report=aggregate --progress=dots --store --bootstrap=${BASEDIR}/benchmarks/bootstrap.php --config=${BASEDIR}/benchmarks/phpbench.json"
echo "Running:\n---\n$cmd\n---\n";
$cmd;