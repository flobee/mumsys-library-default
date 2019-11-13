#!/bin/sh


echo '+-----------------';
echo '| Update code:    ';
git pull;

# Comment the following lines if your are NOT using VIM
echo '+-----------------';
echo '| Updating ctags  ';
if [ -f .php.ctags ]; then
    rm -f .php.ctags;
fi
ctags-exuberant -f .php.ctags --languages=+PHP,-JavaScript,-HTML,-Perl,-Sh,-Sql,-Scheme -R src/ tests/


echo 'done.';

