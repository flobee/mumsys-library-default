<?php

require_once __DIR__ . '/../../src/Mumsys_Loader.php';
spl_autoload_register(array('Mumsys_Loader', 'autoload'));

// varnish:
// $logFormat = '%h %l %u %t \"%r\" %>s %O "%{Referer}i" "%{User-Agent}i';
// apache:
// $logFormat = '%v:%p %h %l %u %t "%r" %>s %O "%{Referer}i" "%{User-Agent}i"';


$logFile = __DIR__ . '/Mumsys_Parser_Logline.log';
$logFormat = 'time host prog msg';

// master patterns to be set or defaults take affect
$patterns = array();


$o = new Mumsys_Parser_Logline($logFormat, $patterns);

// add patters, the others map to the defaults
$o->setPattern('time', '(?P<time>(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{2} \d{2}:\d{2}:\d{2})');
$o->setPattern('host', '(?P<host>.+?)');
$o->setPattern('prog', '(?P<prog>.+?)');
$o->setPattern('msg', '(?P<message>.+?)');

// default: AND
$o->setFilterCondition('OR');

// default! show filtered lines
// $o->setShowFilterResults();

// show the opposite, all which not match to the filters
$o->setHideFilterResults();


// ignore CRON messages
$o->addFilter('prog', array('CRON'), true);
// other examples
//$o->addFilter('port', '80', true); // also matches to 800 8000 8080 ...
//$o->addFilter('HeaderUserAgent', array('google', 'bot', 'soap', 'crawler', 'spider'), false);



/**
 * Start the process.
 */

if (!$logFile || !$logFormat) {
    throw new Exception('Missing logfile or format');
}

$file = new SplFileObject($logFile);

$i=1;
while (!$file->eof())
{
    $line = $file->fgets();
    $item = $o->parse( trim($line) );

    if ($item)
    {
        // web logs
//        if (isset($item['request'])) {
//            $string = substr($item['request'], 4, -9);
//            $urlParts = parse_url($string);
//
//
//            if (isset($urlParts['query'])) {
//                parse_str($urlParts['query'], $queryParts);
//                $urlParts['query'] = $queryParts;
//            }
//
//            $item['request_parts'] = $urlParts;
//        }
//
//        if (!isset($item['port'])) {
//            $item['port'] = $defaultPort;
//        }

        //echo $i .':' . __FILE__ . ':' . __LINE__ . PHP_EOL;


        echo "Line: $line";
        echo 'Result: ';
        print_r($item);

    } else {
        //echo 'Ignore line (filtered or empty line): '.$i . PHP_EOL;
    }
    $i++;
}

