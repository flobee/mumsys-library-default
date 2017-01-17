<?php

/**
 * Mumsys_Service_Vdr
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2015 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 * @version     1.0.0
 * 0.1 Created: 2015-10-08
 */


/**
 * Wrapper class to deal with svdrpsend command from vdr project (Simple VDR Protocol).
 *
 * SVDRP give you the possibility to run the svdrpsend commands in php context.
 * So it is easy now to work with vdr in php. E.g: Dump the EPG to
 * the mysql database, List channels, recording, epg details and so on.
 *
 * Check also the svdrp.php from vdr-tools project to be used as demo and to run
 * as an extra shell script which will be more easy to understand svdrpsend
 * commands.
 *
 * Example:
 * <code>
 * </code>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Service
 *
 * @uses Mumsys_Logger Logger mobejct in context item
 */
class Mumsys_Service_Vdr
    extends Mumsys_Abstract
{
    /**
     * Version ID information.
     */
    const VERSION = '1.0.0';

    /**
     * Context item for dependency injection.
     * @var Mumsys_Context_Item
     */
    private $_context;

    /**
     * Logger object.
     * @var Mumsys_Logger_Interface
     */
    private $_logger;

    /**
     * Hostname / IP to connect to (default: localhost)
     * @var string
     */
    private $_host;

    /**
     * Port to connect to (default: 6419)
     * @var integer
     */
    private $_port;

    /**
     * Timeout in seconds (default: 5)
     * @var integer
     */
    private $_timeout;

    /**
     * Flag about the connection status
     * @var boolean
     */
    private $_connection;

    /**
     * Flag of the debug status
     * @var boolean
     */
    private $_debug;

    /**
     * List of channels
     * @var array
     */
    private $_channels = array();

    /**
     * List of times
     * @var array
     */
    private $_timers = array();

    /**
     * List of recordings
     * @var arry
     */
    private $_recordings = array();


    /**
     * Initialise the object.
     *
     * @param Mumsys_Context $context Context object to get the Logger object
     * @param string $host Host or ip to the server. Default: localhost
     * @param integer $port Port to connect to
     * @param integer $timeout
     * @param boolean $debug Flag to enable debugging.
     */
    public function __construct( Mumsys_Context_Item $context, $host = 'localhost', $port = 6419,
        $timeout = 5, $debug = false )
    {
        $this->_context = $context;

        $this->_logger = $context->getLogger();

        if ( $debug === true ) {
            $this->_logger->logLevel = 7;
            $this->_logger->msglogLevel = 7;
            $this->_logger->msgEcho = true;
        }

        $this->_host = (string)$host;
        $this->_port = (int)$port;
        $this->_timeout = (int)$timeout;
        $this->_debug = (bool)$debug;
        $this->_connection = false;
        /*
          $config = array(
          'commands' => array(
          //'getchannels' => 'svdrpsend -d ' . $globalConfig['hostname'] . ' lstc | /usr/bin/cut -f1 -d';' | tr -d \'\r\n\'',
          'getchannels' => 'svdrpsend -d ' . $hostname . ' lstc',
          'gettimers' => 'svdrpsend -d ' . $hostname . ' lstt',
          )

          );
         */

        if ( $this->_host && $this->_port && $this->_timeout && $context ) {
            $this->connect();
        }
    }


    /**
     * Destuction. Disconnect and reset connection status.
     */
    public function __destruct()
    {
        return $this->disconnect();
    }


    /**
     * Connect to the vdr service.
     *
     * @return boolean Returns true on success or connection already exists
     *
     * @throws Mumsys_Service_Exception If connection fails
     */
    public function connect()
    {
        try
        {
            if ( $this->isOpen() ) {
                return true;
            }

            $errno = 0;
            $errstr = '';
            $this->_connection = @fsockopen(
                $this->_host, $this->_port, $errno, $errstr, $this->_timeout
            );

            if ( $this->_connection === false ) {
                $message = 'Connection to server "' . $this->_host . '" failt: ' . $errstr . ' ('.$errno .')';
                $this->_logger->log($message, 3);

                throw new Mumsys_Service_Exception($message, Mumsys_Exception::ERRCODE_500);
            }

            $this->_logger->log('Connection to vdr server: ' . $this->_host, 7);

            $result = fgets($this->_connection, 128);

            if ( empty($result) || $result == "timeout\n" || !preg_match("/^220 /", $result) )
            {
                $message = 'Connection failure. Expected code 220; Result was "' . $result . '"';
                $this->_logger->log($message, 3);
                $this->disconnect();

                throw new Mumsys_Service_Exception($message, 1);
            }
        }
        catch ( Exception $e ) {
            throw $e;
        }

        return true;
    }


    /**
     * Disconnect and reset reset connection status.
     *
     * @return boolean Returns the status of the close command. True on success
     */
    public function disconnect()
    {
        if (!$this->isOpen()) {
            return true;
        }

        $this->execute('QUIT');
        $return = fclose($this->_connection);

        $this->_isOpen = false;

        return $return;
    }


    /**
     * Execute given command.
     *
     * @param string $command Command to be executed
     * @param string $parameters Optional parameters to pipe the the command
     *
     * @return array|false Returns a list of records in raw format or false if the connection is missing
     *
     * @thows Mumsys_Service_Exception If a result code do not match any spec
     */
    public function execute( $command, $parameters = '' )
    {
        $this->_logger->log(__METHOD__ . ' command: "' . $command . '", params: "' . $parameters . '"', 7);

        if (!$this->isOpen()) {
            throw new Mumsys_Service_Exception('Not connected');
        }

        /** @todo check cmd in the future. speedup things */
        $cmdlist = array(
            'default' => array(
                'CHAN', 'CLRE', 'DELC', 'DELR', 'DELT',
                'EDIT', 'GRAB', 'HELP', 'HITK', 'LSTC',
                'LSTE', 'LSTR', 'LSTT', 'MESG', 'MODC',
                'MODT', 'MOVC', 'NEWC', 'NEWT', 'NEXT',
                'PLAY', 'PLUG', 'PUTE', 'REMO', 'SCAN',
                'STAT', 'UPDT', 'UPDR', 'VOLU', 'QUIT',
            ),
            // e.g: 'PLUG EPGSEARCH LSTS' ?
            'EPGSEARCH' => array(
                'LSTS', 'NEWS', 'DELS', 'EDIS', 'MODS',
                'UPDS', 'UPDD', 'SETS', 'FIND', 'QRYS',
                'QRYF', 'LSRD', 'LSTC', 'NEWC', 'EDIC',
                'DELC', 'RENC', 'LSTB', 'NEWB', 'DELB',
                'EDIB', 'LSTE', 'SETP', 'LSTT', 'NEWT',
                'DELT', 'EDIT', 'DEFT', 'LSCC', 'MENU',
                'UPDT',
                ),
        );

        $command = strtoupper($command);

        if (!in_array($command, $cmdlist['default'])) {
            throw new Mumsys_Service_Exception('Command unknown. Exiting');
        }

        $cmd = $command;
        if ($parameters) {
            $cmd = $cmd . ' ' . stripslashes($parameters);
        }

        fputs($this->_connection, $cmd . "\n");

        $records = $record = $channel = array();

        while ($raw = fgets($this->_connection, 2048)) {
            if (!preg_match('/^(\d{3})( |-)(.*)$/i', $raw, $data)) {
                continue;
            }

            $this->_logger->log('data: "' . print_r($raw, true) . '"', 7);

            /*
              214 Hilfetext
              215 EPG Eintrag
              216 Image grab data (base 64)
              220 VDR-Service bereit
              221 VDR-Service schließt Sende-Kanal
              250 Angeforderte Aktion okay, beendet
              354 Start senden von EPG-Daten
              451 Angeforderte Aktion abgebrochen: lokaler Fehler bei der Bearbeitung
              500 Syntax-Fehler, unbekannter Befehl
              501 Syntax-Fehler in Parameter oder Argument
              502 Befehl nicht implementiert
              504 Befehls-Parameter nicht implementiert
              550 Angeforderte Aktion nicht ausgeführt
              554 Transaktion fehlgeschlagen
             */
            switch ( $data[1] ) {
                case '250':
                    $records[] = trim($data[3]);
                    break;

                // EPG record
                case '215':
                    // $records[] = trim($data[3]);

                    $line = trim($data[3]);
                    switch ( $line[0] )
                    {
                        case 'c':   // end of a channel
                            break;

                        case 'C':   // begin of a channel
                            // eg: C T-8468-514-514 ZDF (T) (T)
                            $channel = array();
                            if ( preg_match('/^C ([^ ]+) *(.*)/', $line, $channels) ) {
                                $channel['key'] = $channels[1];
                                $channel['name'] = $channels[2];
                            }
                            break;

                        case 'D':
                            $record['description'] = substr($line, 2);
                            break;

                        case 'e':   // end of a record
                            $records[] = $record;
                            unset($record, $channel, $channels, $extras);
                            break;

                        case 'E':   // begin of a record/event
                            //reset prev. results first and fill with defaults
                            $record = array(
                                'channel_key' => $channel['key'],
                                'channel_name' => $channel['name'],
                                'event_id' => '',
                                'timestamp' => '',
                                'duration' => '',
                                'e_tableid' => '',
                                'e_version' => '',
                                'description' => '',
                                'subtitle' => '',
                                'title' => '',
                                'advisory' => 0,
                                'vps' => '',
                                'genre' => '',
                                'stream_kind' => '',
                                'stream_type' => '',
                                'stream_lang' => '',
                                'stream_desc' => ''
                            );

                            if ( preg_match('/^E (.*?) (.*?) (.*?) (.*?) (.*)/', $line, $event) ) {
                                $record['event_id'] = $event[1];
                                $record['timestamp'] = $event[2];
                                $record['duration'] = $event[3];
                                $record['e_tableid'] = @$event[4];
                                $record['e_version'] = @$event[5];
                            }
                            break;

                        case 'G':
                            $record['genre'] = substr($line, 2); // raw format
                            break;

                        case 'R': // age check, advisory
                            $record['advisory'] = substr($line, 2);
                            break;

                        case 'S':
                            $record['subtitle'] = substr($line, 2);
                            break;

                        case 'T':
                            $record['title'] = substr($line, 2);
                            break;

                        case 'V':
                            $record['vps'] = substr($line, 2);
                            break;

                        case 'X':
                            if ( preg_match('/^X (.*?) (.*?) (.*?) (.*)/', $line, $extras) ) {
                                $record['stream_kind'] = $extras[1];
                                $record['stream_type'] = $extras[2];
                                $record['stream_lang'] = $extras[3];
                                $record['stream_desc'] = $extras[4];
                            }
                            break;

                        // in recordings:
                        case 'F':
                            $record['framerate'] = substr($line, 2);
                            break;
                        case 'P':
                            $record['priority'] = substr($line, 2);
                            break;
                        case 'L':
                            $record['lifetime'] = substr($line, 2);
                            break;
                        case '@':
                            //$record['notes'] = substr($line,2);
                            $records[] = $record;
                            break;

                        default:
                            throw new Mumsys_Service_Exception(
                                'Input error. If you see this, something went wrong. '
                                . 'Input was: "' . $line . '"'
                            );
                            break;
                    }

                    break; // end epg list

                case '220':
                case '221':
                    break;

                case '501':
                    // also for "Not found"
                    break;

                default:
                    throw new Mumsys_Service_Exception('None catchable exception: ' . $data[3]);
                    break;
            }

            // the last record, break the loop
            if (trim($data[2]) != '-') {
                break;
            }

            //$this->_logger->log('$records: "' . print_r($records, true) . '"', 7);
        }
        unset($raw, $data);

        return $records;
    }


    /**
     * Return the list of available channels.
     *
     * Example:<pre>
     * id title;[semikolon]bouquet/transp:
     * id Name ;bouquet :Frequenz :Parameter          :Signalquelle:Symbolrate:VPID :APID     :TPID:CAID:SID  :NID :TID :RID
     * 30 NDR 2;ZDFmobil:674000000:B8C23D12G4M16S0T8Y0:R           :0         :673=2:674=deu@3:679 :0   :16426:8468:4097:0</pre>
     *
     * @return array|false Returns list of key/value pair where the key is the
     * channels ID and the value the channels name
     */
    public function channelsGet()
    {
        return $this->_channelsGet('');
    }


    /**
     * Returns a list of channels.
     *
     * @param string|integer $key String to search for channels or integer for
     * a specific channel id to return.
     *
     * @return array List of channel items found. Item keys are:
     *      'vdr_id'
     *      'name'
     *      'bouquet'
     *      'frequency'
     *      'parameter'
     *      'source'
     *      'symbolrate'
     *      'VPID'
     *      'APID'
     *      'TPID'
     *      'CAID'
     *      'SID'
     *      'NID'
     *      'TID'
     *      'RID'
     */
    private function _channelsGet( $key )
    {
        $channelList = array();
        $records = $this->execute('LSTC', $key);

        while ( list(, $line) = each($records) )
        {
            $parts = explode(':', $line);
            $posStart = strpos($parts[0], ' ');
            $posEnd = strpos($parts[0], ';');

            $recordId = substr($parts[0], 0, $posStart);
            $names = explode(';', (substr($parts[0], $posStart + 1)));
            $recordName = str_replace('|', ':', $names[0]);
            $recordTransponder = $names[1];

            $channelList[$recordId] = array(
                'vdr_id' => $recordId,
                'name' => $recordName,
                'bouquet' => $recordTransponder,
                'frequency' => trim($parts[1]),
                'parameter' => trim($parts[2]),
                'source' => trim($parts[3]),
                'symbolrate' => trim($parts[4]),
                'VPID' => trim($parts[5]),
                'APID' => trim($parts[6]),
                'TPID' => trim($parts[7]),
                'CAID' => trim($parts[8]),
                'SID' => trim($parts[9]),
                'NID' => trim($parts[10]),
                'TID' => trim($parts[11]),
                'RID' => trim($parts[12]),
            );
        }

        if (empty($key)) {
            $this->_channels = $channelList;
        }

        return $channelList;
    }


    /**
     * Returns the channel item by given id.
     *
     * @param integer $id Channel ID to return
     *
     * @return array Channel item
     */
    public function channelGet($id=null)
    {
        $id = (int) $id;

        if (!isset($this->_channels[$id])) {
            $this->_channels[$id] = $this->_channelsGet($id);
        }

        return $this->_channels[$id];
    }


    /**
     * Returns list of channels.
     *
     * @param string|integer $key Keyword to search for or integer for a specific channel id
     *
     * @return array List of channel items
     *
     * @throws Mumsys_Service_Exception
     */
    public function channelsSearch( $key = null )
    {
        if ( is_string($key) ) {
            $search = (int) $key;
        } else if ( is_numeric($key) ) {
            $search = trim($key);
        } else {
            throw new Mumsys_Service_Exception('Invalid search parameter');
        }

        return $this->_channelsGet($search);
    }


    /**
     * Return the list of timers.
     *
     * @param integer $timerID Optional timer ID to get only this timer details
     * @return array|false Returns list of key/value pair where the key is the
     * timer ID and the value is a key/value pair of timer properties
     */
    public function timersGet( $timerID = null )
    {
        $this->_logger->log(__METHOD__, 7);

        if (!$this->_timers) {
            $records = $this->execute('LSTT', (int)$timerID);
            while (list(, $line) = each($records)) {

                $options = $this->timerString2RecordGet($line);

                $this->_timers[$options['id']] = $options;
            }
        }
        unset($options, $recordId, $parts, $posStart, $line);

        return $this->_timers;
    }


    /**
     * Returns key/value pair of a timer record.
     *
     * @param integer $activ Aktiv (1) or inactiv (0)
     * @param integer $channelid Channel ID
     * @param integer $day Day of the month
     * @param integer $timeStart Number of the start time eg.: 2015
     * @param integer $timeEnd Number of the ending of the recording eg.: 2231
     * @param integer $priority Recording priority 0-99
     * @param integer $lifetime Lifetime 0-99
     * @param string $title Title/ name of the timer
     * @param string $notes Additional Informations, optional
     * @param integer $id ID of the timer. Null for a new one or id to modify/update
     *
     * @return array Timer record
     */
    public function timerRecordGet( $activ, $channelid, $day, $timeStart,
        $timeEnd, $priority, $lifetime, $title, $notes, $id = null )
    {
        $this->_logger->log(__METHOD__, 7);

        $record = array(
            'activ' => $activ,
            'channelid' => $channelid,
            'day' => $day,
            'time_start' => $timeStart,
            'time_end' => $timeEnd,
            'priority' => $priority,
            'lifetime' => $lifetime,
            'title' => str_replace('|', ':', $title),
            'notes' => $notes,
        );

        if (!is_null($id)) {
            $record['id'] = $id;
        }

        return $record;
    }

    /**
     * Returns the timer string to be used to set or update a timer.
     *
     * Api note: [nummer] aktiv:Kanalnummer:Tag_des_Monats:Startzeit:Endzeit:Priorität:Dauerhaftigkeit:Titel:
     *
     * @param integer $activ Aktiv (1) or inactiv (0)
     * @param integer $channelID Channel ID
     * @param integer $dayOfMonth Day of the month
     * @param integer $timeStart Number of the start time eg.: 2015
     * @param integer $timeEnd Number of the ending of the recording eg.: 2231
     * @param integer $priority Recording priority 0-99
     * @param integer $lifetime Lifetime 0-99
     * @param string $title Title/ name of the timer
     * @param string $notes Additional Informations, optional
     * @param integer $id ID of the timer. Null for a new one or id to modify/update
     *
     * @return string Timer string to be used to add or update a timer
     */
    public function timerStringGet( $activ, $channelID, $dayOfMonth, $timeStart,
        $timeEnd, $priority, $lifetime, $title, $notes, $id = null )
    {
        $this->_logger->log(__METHOD__, 7);

        $timerString = sprintf(
            '%10$s %1$s:%2$s:%3$s:%4$s:%5$s:%6$s:%7$s:%8$s:%9$s',
            $activ,
            $channelID,
            $dayOfMonth,
            $timeStart,
            $timeEnd,
            $priority,
            $lifetime,
            str_replace(':', '|', $title),
            $notes,
            $id
        );

        return $timerString;
    }


    /**
     * Returns a timer record from given timer string format.
     *
     * @param string $timerString The timer string from svdrp program
     * @return array|false Returns the timer record or false if record ID is missing.
     */
    public function timerString2RecordGet( $timerString=null )
    {
        $this->_logger->log(__METHOD__, 7);

        $line = trim($timerString);

        $parts = explode(':', $line);

        $posStart = strpos($parts[0], ' ');

        $recordId = substr($parts[0], 0, ($posStart));

        $record = $this->timerRecordGet(
            substr($parts[0],($posStart+1)),
            $parts[1],
            $parts[2],
            $parts[3],
            $parts[4],
            $parts[5],
            $parts[6],
            $parts[7],
            $parts[8],
            $recordId
        );

        return $record;
    }


    /**
     * Returns a timer string format from given timer record to set/ update the timer.
     *
     * @param string $timerString The timer string from svdrp program
     * @return string Returns the timer string.
     */
    public function timerRecord2StringGet( array $record = array() )
    {
        $this->_logger->log(__METHOD__, 7);

        if (empty($record['id'])) {
            $record['id'] = null;
        }

        $timerString = $this->timerStringGet(
            $record['activ'],
            $record['channelid'],
            $record['day'],
            $record['time_start'],
            $record['time_end'],
            $record['priority'],
            $record['lifetime'],
            $record['title'],
            $record['notes'],
            $record['id']
        );

        return $timerString;
    }


    /**
     * Returns the list of recording.
     *
     * @return array|false Returns list of key/value pair where the key is the
     * internal recording ID and the value is a list of key/value pairs of
     * recording properties
     */
    public function recordingsGet( $recordingID=null )
    {
        $this->_logger->log(__METHOD__, 7);

        if (true) {
            $records = $this->execute('LSTR', $recordingID);

            if (!$recordingID) {
                while (list(, $line) = each($records))
                {
                    $line = trim($line);
                    $partA = explode(' ', substr($line, 0, 23));
                    $partB = substr($line, 23, 1);

                    $title = str_replace('|', ':', substr($line, 25) );
                    $date = explode('.', $partA[1]);
                    $dateString = sprintf('20%1$s-%2$s-%3$s', $date[2], $date[1], $date[0]);
                    $options = array(
                        'id' => $partA[0],
                        'date' => $dateString,
                        'time_start' => $partA[2],
                        'duration' => substr($partA[3], 0, 5),
                        'new' => (($partB == '*') ? 1 : 0),
                        'title' => $title,
                    );

                    $this->_recordings[$partA[0]] = $options;
                }
            } else {
                $records[0]['id'] = $recordingID;
                $this->_recordings = $records;
            }
        }
        unset($records, $options, $recordId, $partA, $partB, $line);

        return $this->_recordings;
    }


    /**
     * Test if connect() was called successfully.
     *
     * @return boolean Returns true if connection was opend successfully or
     * false on failure or the connection was closed
     */
    public function isOpen()
    {
        if (is_resource($this->_connection)) {
            return true;
        }

        return false;
    }




}
