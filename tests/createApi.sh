#!/bin/sh

cmd=/usr/bin/phpdoc; # ~/scripts/lib/pear/PhpDocumentor-1.4.3/phpdoc
echo 'building API';

# --parseprivate
$cmd \
--title "Mumsys library" \
-o HTML:frames:earthli \
-t ../docs/API/ \
--directory ../src/ \
--ignore \
Zend/* \
Smarty/*,\
getid3/*,\
phpthumb/*,\
graph/*

