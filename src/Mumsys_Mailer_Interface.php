<?php

/* {{{ */
/**
 * Mumsys_Mailer_Interface
 * for MUMSYS Library for Multi User Management System (MUMSYS)
 * ----------------------------------------------------------------------------
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright Copyright (c) 2014 by Florian Blasel for FloWorks Company
 * @author Florian Blasel <flobee.code@gmail.com>
 * ----------------------------------------------------------------------------
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mailer
 * @version     1.0.0
 * Created on 01.12.2006 improved since 2016, init interface
 * $Id: class.mailsys.php 2369 2011-12-08 22:02:37Z flobee $
 */
/* }}} */


/**
 * Mumsys mailer interface as part of MailSYS newsletter system.
 *
 * This is the interface which uses MUMSYS at all.
 * Calling the driver class directly is not prohibited
 * and possible but: dont use it! Improve the interface then!
 *
 * @category    Mumsys
 * @package     Mumsys_Library
 * @subpackage  Mumsys_Mailer
 */
interface Mumsys_Mailer_Interface
{


    /**
     * Call a mail driver method which is not implemented at the interface.
     *
     * @note Dont use it! update the interface would be the best choice!
     *
     * @param string $name Name of the methode to call
     * @param mixed $params Parameter/s to pipe the the methode
     */
    public function __call( $name, $params );


    /**
     * Adds a recipient address and name.
     *
     * @param string $email Email address to add
     * @param string $name Name of the person the email belongs to
     */
    public function addTo( $email, $name );


    /**
     * Adds a "copy to" recipient address and name.
     *
     * @param string $email Email address to add
     * @param string $name Name of the person the email belongs to
     */
    public function addCc( $email, $name );


    /**
     * Adds a "blind copy to" recipient address and name.
     *
     * @param string $email Email address to add
     * @param string $name Name of the person the email belongs to
     */
    public function addBcc( $email, $name );


    /**
     * Adds a "Reply-To" address and name.
     *
     * @param string $email The email address to reply to
     * @param string $name Name of the person the email belongs to
     *
     * @return boolean True on success, false if address already used or invalid
     */
    public function addReplyTo( $email, $name = '' );


    /**
     * Sets the "from" and "from name" properties.
     *
     * @param string $email Email address
     * @param string $name Name of the person the email belongs to
     * @param boolean $auto On true (default) sets the Sender address (Return
     * -Path) of the message
     */
    public function setFrom( $email, $name = '', $auto = true );


    /**
     * Sets the (Return-Path) of the message.
     *
     * The Sender email (Return-Path) will be sent via -f to sendmail or as
     * 'MAIL FROM' in smtp mode. For more @see setFrom() "auto" property
     *
     * @param string $email Return-Path email address
     */
    public function setReturnTo( $email );


    /**
     * Sets the mail message as html code.
     *
     * @param string $htmlCode Html code to set as message
     * @param string $pathInlineAttachments Path to the files to add images as
     * inline attachments if given/set in html code
     */
    public function setMessageHtml( $htmlCode, $pathInlineAttachments = null );


    /**
     * Sets the mail message as text format.
     *
     * @param string $text Plain text to set as message
     */
    public function setMessageText( $text );


    /**
     * Adds an attachment from a path on the filesystem.
     *
     *
     * @param string $location Location to the attachment on the filesystem.
     * @param string $encoding File encoding (e.g base64)
     * @param string $type File extension (MIME) type.
     * @param string $disposition Disposition to use
     *
     * @return boolean Returns false if the file could not be found or read.
     */
    public function addAttachment( $path, $encoding = 'base64', $type = '', $disposition = 'attachment' );


    /**
     * Return the list of attachments.
     * @return array
     */
    public function getAttachments();


    /**
     * Sends a mail message.
     *
     * @return boolean  True on success or false on error
     */
    public function send();


    /**
     * Set certificates for private/public key authentication e.g. for S/MIME.
     *
     * @param string $certFile Location to certificate file e.g. "/etc/certs/cert.crt"
     * @param string $privateKeyFile Location to private key file
     * @param string $keyPwd Password for private key if set/needed
     * @param string $chain Optional path to chain certificate
     */
    public function setCertificates( $certFile, $privateKeyFile, $keyPwd = null, $chain = '' );


    /**
     * Adds a custom header.
     *
     * @param string $name Header name
     * @param string $value Header value
     */
    public function addCustomHeader( $name, $value = null );


    /**
     * Returns all custom headers.
     *
     * @return array List of all headers
     */
    public function getCustomHeaders();

}