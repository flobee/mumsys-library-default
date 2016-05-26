#!/bin/sh

# hints:
# ~/scripts/lib/pear/PhpDocumentor-1.4.3/phpdoc
# phpdoc 1.4.4
# phpdoc --title "Mumsys library" -o "HTML:frames:earthli" -t ../docs/API/ \
#   --directory ../src/ --ignore "Zend/*,Smarty/*,getid3/*,phpthumb/*,graph/*"
#
# phpdoc >= 2.8:
# --template responsive-twig|clean
#
#cmd=/usr/bin/phpdoc;
#echo 'building API';
#
# --parseprivate
#$cmd \
#--title "Mumsys library" \
#--template="responsive" \
#-t ../docs/API/ \
#--directory ../src/ \
#--ignore "Zend/*,Smarty/*,getid3/*,phpthumb/*,graph/*"


# Using ApiGen
# cd ./
# apigen generate --source ../src/ --destination ../docs/API/
php5 ../../phpDocumentor.phar run -d ../src -t ../docs/API
