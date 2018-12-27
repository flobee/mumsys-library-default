#!/bin/sh

echo "----------------------------------------";
echo "usage: $0 [phpcs options] <path or file>";
echo "----------------------------------------";

_DIR=$(dirname "$0");

php $_DIR/../vendor/bin/phpcbf --ignore=data/* --standard=../misc/coding/Mumsys $*
