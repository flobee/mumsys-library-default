<?php

/**
 * Host: secondhost
 */
return array(
    'config' => array(
        '# secondhost',
        'Host' => 'secondhost',
        'Port' => '22',
// disabled for unit tests
//        'IdentityFile' => '/goes/there/id_key',
        'PreferredAuthentications' => 'publickey',
        'Protocol' => '2',
    ),
);
