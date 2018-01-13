<?php

/**
 * Mumsys_Mail_PHPMailer Test
 */
class Mumsys_Mail_PHPMailerTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Mail_PHPMailer
     */
    protected $_object;

    /**
     * @var array
     */
    private $_config;

    /**
     * @var string
     */
    private $_version;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->markTestSkipped('New phpmailer version needs to be checked/ implemented');

        $this->_version = '3.0.1';

        $this->_config = array(
            'adapter' => 'mail',
            'username' => 'unit', // - login name for the mail server
            'password' => 'test', // - Password for the mailserver
            'hostname' => 'localhost', // - Hostname or IP of the mailserver
            'port' => '25', // - Port of the mail sever
            'smtp_auth' => false, // - boolean using smtp auth or not
            'smtp_keepalive' => true, // - boolean keep connection alive or not
            'smtp_debug' => 0, // - Debugging options, driver specific 0=Off,1=client,2=server and client
            'smtp_secure' => false, // - Sets the encryption system to use - ssl (deprecated) or tls (new)
            'smtp_options' => false, // - Futher smtp option driver specific
            'wordwrap' => 78, // - Mail text wordwrap. Leave it (default is 78) change it only
            //   if you know what you are doing,
            'mail_from_email' => // - Sender email address. Uses setFrom() on construction
            'unittest@localhost',
            'mail_from_name' =>
            'Unit Test', //- Sender name. Uses setFrom() on construction
            'xmailer' => 'unittest', // - X-Mailer header to replace.
            'charset' => 'utf-8', // - mail character set defaut: utf-8
            'certificate' => array(// - cert Options
                'cert' => false, //   - The location of your certificate file e.g '/path/to/cert.crt',
                'privateKey' => false, //   - The location of your private key file e.g: '/path/to/cert.key',
                'pass' => false, //   - The password you protected your private key with (not the Import
                //     Password! may be empty but parameter must not be omitted!)
                'chain' => false, //   - Optional path to chain certificateThe location to your
            //     chain file e.g.: '/path/to/certchain.pem'
            ),
        );

        $this->_object = new Mumsys_Mail_PHPMailer($this->_config);
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = NULL;
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::__construct
     */
    public function test_construct()
    {
        $this->assertInstanceOf('Mumsys_Mail_PHPMailer', $this->_object);
        $this->assertInstanceOf('Mumsys_Mail_Interface', $this->_object);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::getMailer
     */
    public function testGetMailer()
    {
        $actual = $this->_object->getMailer();

        $this->assertInstanceOf('PHPMailer\PHPMailer\PHPMailer', $actual);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::__call
     */
    public function test__call()
    {
        $this->assertNull($this->_object->isQmail());
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::addTo
     */
    public function testAddTo()
    {
        $email = 'root@localhost';
        $name = 'unittest to root @ localhost';
        $result = $this->_object->addTo($email, $name);

        $this->assertTrue($result);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::addCc
     */
    public function testAddCc()
    {
        $email = 'root@localhost';
        $name = 'unittest to root @ localhost';
        $result = $this->_object->addCc($email, $name);

        $this->assertTrue($result);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::addBcc
     */
    public function testAddBcc()
    {
        $email = 'root@localhost';
        $name = 'unittest to root @ localhost';
        $result = $this->_object->addBcc($email, $name);

        $this->assertTrue($result);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setFrom
     * @covers Mumsys_Mail_PHPMailer::setReturnTo
     */
    public function testSetFrom()
    {
        $email = 'root@localhost';
        $name = 'unittest to root @ localhost';
        $result = $this->_object->setFrom($email, $name, true);
        $this->_object->setReturnTo($email);

        $this->assertEquals($email, $this->_object->getMailer()->Sender);
        $this->assertTrue($result);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::addReplyTo
     */
    public function testAddReplyTo()
    {
        $email = 'unittest@localhost';
        $name = 'unittest to unittest @ localhost';
        $result = $this->_object->addReplyTo($email, $name, true);

        $this->assertTrue($result);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setSubject
     */
    public function testSetSubject()
    {
        $text = 'some subject';
        $this->_object->setSubject($text);
        $this->assertEquals($text, $this->_object->getMailer()->Subject);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setMessage
     * @covers Mumsys_Mail_PHPMailer::setMessageHtml
     */
    public function testSetMessage()
    {
        $text = "<html><body>some<br>message</body></html>";
        $actual = $this->_object->setMessage($text);
        $actual = $this->_object->setMessageHtml($text);
        $expected = $this->_object->getMailer()->Body;

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setMessageText
     */
    public function testSetMessageText()
    {
        $text = "some<br>message";
        $actual = $this->_object->setMessageText($text);
        $expected = $this->_object->getMailer()->AltBody;

        $this->assertEquals($expected, $actual);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::addAttachment
     * @covers Mumsys_Mail_PHPMailer::getAttachments
     */
    public function testAddAttachment()
    {
        $this->assertEquals(array(), $this->_object->getAttachments());
        $this->expectException('phpmailerException');
        $this->expectExceptionMessageRegExp('/(Could not access file: no\/location)/i');
        $this->_object->addAttachment('no/location', 'somename', 'base64', 'txt', 'attachment');
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setContentType
     */
    public function testSetContentType()
    {
        $this->_object->setContentType('html');
        $actual1 = $this->_object->getMailer()->ContentType;
        $expected1 = 'text/html';

        $this->_object->setContentType('text');
        $actual2 = $this->_object->getMailer()->ContentType;
        $expected2 = 'text/plain';

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setCharset
     */
    public function testSetCharset()
    {
        $actual1 = $this->_object->getMailer()->CharSet; // utf-8 on construction
        $this->_object->setCharset('iso-8859-1');
        $actual2 = $this->_object->getMailer()->CharSet;

        $this->assertEquals('utf-8', $actual1);
        $this->assertEquals('iso-8859-1', $actual2);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setTransportWay
     */
    public function testSetTransportWay()
    {
        $expected1 = 'mail';
        $this->_object->setTransportWay($expected1);
        $actual1 = $this->_object->getMailer()->Mailer;

        $expected2 = 'smtp';
        $this->_object->setTransportWay($expected2);
        $actual2 = $this->_object->getMailer()->Mailer;

        $expected3 = 'sendmail';
        $this->_object->setTransportWay($expected3);
        $actual3 = $this->_object->getMailer()->Mailer;

        // for code coverage
        $this->_object->setTransportWay('use default');

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setLanguage
     */
    public function testSetLanguage()
    {
        $this->assertTrue($this->_object->setLanguage('en'));
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::send
     */
    public function testSend()
    {
        $this->expectException('phpmailerException');
        $this->expectExceptionMessageRegExp('/(You must provide at least one recipient email address.)/i');
        $this->_object->send();
    }


    /**
     * Send a test email. Full function test
     */
    public function testSendEmail()
    {
        $to = 'root@localhost';
        $message = '<html>html string message dummy '
            . 'generated in: ' . __FILE__ . PHP_EOL
            . '<br><br>.</html>';

        $mail = $this->_object;
        $mail->addTo($to, 'php unit test mail');
        $mail->setSubject('some subject');
        $mail->setMessage($message, '', true);
        $mail->setMessageText('plain version .check html version for details:' . PHP_EOL . $message,
            '', true);
        $mail->setContentType('html');

        $actual = $mail->send();

        $this->assertTrue($actual);

        echo PHP_EOL . 'A test email to "' . $to . '" was set to the mail server '
            . 'sucessfully. Please check the mail!' . PHP_EOL;
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::setCertificate
     */
    public function testSetCert()
    {
        $this->assertTrue($this->_object->setCertificate('', '', '', ''));
    }


    /**
     * @covers Mumsys_Mail_PHPMailer::addCustomHeader
     * @covers Mumsys_Mail_PHPMailer::getCustomHeaders
     */
    public function testGetAddCustomHeader()
    {
        $actual1 = $this->_object->addCustomHeader('X-MailerTest', 'Unittest');
        $actual2 = $this->_object->getCustomHeaders();

        $this->assertTrue($actual1);
        $this->assertEquals(array(0 => array('X-MailerTest', 'Unittest')), $actual2);
    }


    /**
     * VERSION check
     */
    public function testVersion()
    {
        $this->assertEquals($this->_version, Mumsys_Mail_PHPMailer::VERSION);
    }

}
