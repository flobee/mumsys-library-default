<?php

/**
 * Host: localhost
 */
return array(
    'config' => array(
        '# localhost',
        'Host' => 'localhost',
        'HostName' => 'localhost',
        'Port' => '22',
        'IdentityFile' => '~/.ssh/id_rsa',
        'PreferredAuthentications' => 'publickey',
        'Protocol' => '2',
    ),
    //
    // publish key files based on given host (this host file) to the following
    // target/s
    'deploy' => array(
//        // Examle:
//        'targethost' => array(
//            // if locations differ use key/value pairs eg:
//            '~/.ssh/my/id_rsa.pub' => '/home/other/.ssh/my/id_rsa.pub',
//            '~/.ssh/my/id_rsa' => '/home/other/.ssh/my/id_rsa',
//            //
//            // deploy the global IdentityFile and the IdentityFile.pub file
//            // given in the config section if 'IdentityFile' is set.
//            'IdentityFile',
//            // or:
//            'IdentityFile' => '/home/at/target/host/.ssh/my',
//
//            // This should copy all key files to the target server which
//            // exists in the path where the configured IndentityFile is
//            // located e.g. the account as your root mirror
//            '*',
//            // or (target path must exists):
//            '*' => '~/.ssh/keys/from/localhost',
//        ),
        'otherhost' => array(
            // deploy the "IdentityFile" and the "IdentityFile".pub file given
            // in config section of localhost (the config above)
            'IdentityFile',
            // simple test, copy this file
            '/simple/test/copy/this/file',
            // copy all files in ~/.ssh/ to the target
            '*',
            // WARNING: you dont really want this!? But maybe you do need it to
            //  share a private key as default for this target host
            '/this/keyfile' => 'IdentityFile', // incl. the .pub file
        ),
        'secondhost' => array(
            // deploy the "IdentityFile" and the "IdentityFile".pub file given
            // in config section of localhost (the config above)
            'IdentityFile' => '/goes/here',
            // also deploy some extra keys:
            '/this/id_rsa' => '/goes/there/id_key',
            '/this/id_rsa.pub' => '/goes/there/id_key.pub',
            // copy all to another location
            '*' => '~/.ssh/keys/from/localhost',
        ),
    ),
);
