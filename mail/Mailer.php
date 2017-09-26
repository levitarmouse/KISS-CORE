<?php

namespace levitarmouse\common_tools\mail;

include_once './PHPMailer/PHPMailer.php';
include_once './PHPMailer/PHPMailerException.php';
include_once './PHPMailer/SMTP.php';

class Mailer
{
    protected $Username;
    protected $Password;
    protected $Host;
    protected $Port;
    protected $From;
    protected $FromName;
    protected $SMTPAuth;

    private $_mail;
    private $_subject;
    private $_body;
    private $_name;

    public function __construct($conf,
                                $Username = '', $Password = '',
                                $Host = '', $Port = '',
                                $From = '', $FromName = '',
                                $SMTPAuth = true)
    {
        if ($conf) {
            $this->Username = $conf->Username;
            $this->Password = $conf->Password;
            $this->Host     = $conf->Host;
            $this->Port     = $conf->Port;
            $this->From     = $conf->From;
            $this->FromName = $conf->FromName;
            $this->SMTPAuth = $conf->SMTPAuth;
        } else {
            $this->Username = $Username;
            $this->Password = $Password;
            $this->Host     = $Host;
            $this->Port     = $Port;
            $this->From     = $From;
            $this->FromName = $FromName;
            $this->SMTPAuth = $SMTPAuth;
        }

        $this->cleanRecipient();

    }

    public function cleanRecipient() {
        $this->_mail = '';
        $this->_subject = '';
        $this->_body = '';
        $this->_name = '';
        return;
    }

    public function setMail($mail = '') {
        $this->_mail = $mail;
    }

    public function setName($name = '') {
        $this->_name = $name;
    }

    public function setSubject($subject = '') {
        $this->_subject = $subject;
    }

    public function setBody($body = '') {
        $this->_body = $body;
    }

    public function prepare($mail = '', $subject = '', $body = '', $name = '') {
        $this->setMail($mail);
        $this->setSubject($subject);
        $this->setBody($body);
        $this->setName($name);
    }

    public function send() {

        $result = false;
        if (empty($this->_mail) || empty($this->_subject) || empty($this->_body)) {
            return false;
        } else {
            $result = $this->BESend();
        }
        return $result;
    }

    protected function BESend() {
        $mail = new \PHPMailer\PHPMailer();

        $mail->IsHTML(true);

        $mail->IsSMTP();
        $mail->SMTPAuth = $this->SMTPAuth;
        $mail->Username = $this->Username;
        $mail->Password = $this->Password;
        $mail->Host = $this->Host;
        $mail->Port = $this->Port;
        $mail->From = $this->From;
        $mail->FromName = $this->FromName;

        $mail->Subject = $this->_subject;

        $mail->AddAddress($this->_mail);

        $mail->WordWrap = 50;

        $mail->Body = $this->_body;

        if(!$mail->Send()){
//            echo "No se pudo enviar el Mensaje.";
        }else{
//            echo "Mensaje enviado";
        }
    }
}