#!/bin/sh

echo "----------------------------------------";
echo "usage: $0 [phpcs options] <path or file>";
echo "----------------------------------------";

php ../vendor/bin/phpcs --standard=../misc/coding/Mumsys2 $* 

