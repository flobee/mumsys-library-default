#!/bin/sh

echo '+-----------------';
echo '| Update code:    ';
git pull;

echo '+-----------------';
echo '| Updating ctags  ';
ctags-exuberant -f php.ctags --languages=+PHP,-JavaScript,-HTML,-Perl,-Sh,-Sql,-Scheme -R src/ tests/

echo 'done.';

