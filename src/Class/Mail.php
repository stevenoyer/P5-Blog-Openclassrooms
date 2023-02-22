<?php 

namespace So\Blog\Class;

use Config;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private string $name;
    private string $sender;
    private string $receiver;
    private string $receiverName;
    private string $replyTo;
    private string $replyToName;
    private string $subject;
    private string $body;
    private bool $html;
    private $config;

    /**
     * Class constructor.
     */
    public function __construct(string $subject, array $infos, string $body, bool $html = true)
    {
        $this->config = new Config;

        $this->subject = $subject;
        $this->name = $this->config->name;
        $this->sender = $this->config->from;
        $this->replyTo = $this->config->replyTo;
        $this->replyToName = $this->config->replyToName;
        $this->name = $this->config->name;
        $this->html = $html;
        $this->body = $body;

        $this->receiver = $infos['email'];
        $this->receiverName = $infos['name'];
    }

    /**
     * Send mail with PHPMailer
     */
    public function send(): bool
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $this->config->smtp_host;
        $mail->Port = $this->config->smtp_port;
        $mail->Username = $this->config->smtp_mail;
        $mail->Password = $this->config->smtp_password;
        $mail->CharSet = 'UTF-8';
        $mail->From = $this->sender;
        $mail->FromName = $this->name;
        $mail->isHTML($this->html);
        $mail->addReplyTo($this->replyTo, $this->replyToName);
        $mail->addAddress($this->receiver, $this->receiverName);
        $mail->Subject = $this->subject;
        $mail->Body = $this->body;

        if ($this->config->smtp_debug)
        {
            $mail->SMTPDebug = $this->config->smtp_debug;
        }

        if ($this->config->smtp_secure)
        {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        if (!$mail->send())
        {
            if ($this->config->debug)
            {
                echo 'Erreur : ' . $mail->ErrorInfo . '<br />';
            }

            return false;
        }
        
        return true;
    }

}
