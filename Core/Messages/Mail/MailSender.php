<?php

namespace App\Messages\Mail;

use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use ErrorException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class MailSender extends MailBuilder
{


    public function getMailAttributes()
    {
        $app_config = json_decode(file_get_contents("appsettings.json"));

        $this->driver = $app_config->mailAttributes->driver;

        $this->checkMailAttributes(
            ["smtpHost", "smtpUsername", "smtpPassword", "smtpPort"],
            $app_config->mailAttributes->smtp
        );

        $this->smtpHost = $app_config->mailAttributes->smtp->smtpHost;
        $this->smtpUsername = $app_config->mailAttributes->smtp->smtpUsername;
        $this->smtpPassword = $app_config->mailAttributes->smtp->smtpPassword;
        $this->smtpPort = $app_config->mailAttributes->smtp->smtpPort;
    }

    public function checkMailAttributes(array $variables, object $mailAttributes)
    {
        foreach ($variables as $variable) {
            if (!isset($mailAttributes->$variable)) {
                throw new ErrorException($variable . " not found in mail attribites: appsettings.json.");
            }
        }
    }


    public function createTransport()
    {
        return $this->transport = (new Swift_SmtpTransport($this->smtpHost, $this->smtpPort))
            ->setUsername($this->smtpUsername)
            ->setPassword($this->smtpPassword);
    }


    public function createMailer() 
    {
        // Create the Mailer using your created Transport
        return $this->mailer = new Swift_Mailer($this->transport);
    }

    public function buildMessage()
    {
        // Create a message
        $this->mail = (new Swift_Message($this->subject))
            ->setFrom(array($this->from => $this->fromName))
            ->setTo(array($this->to => $this->toName))
            ->setContentType($this->contentType)
            ->setCharset($this->charset)
            ->setBody($this->message);

            return $this->mail;
    }

    public function PHPMailer() 
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = 1; // SMTP::DEBUG_SERVER;                   // Enable verbose debug output
            $mail->isSMTP();                                         // Send using SMTP
            $mail->Host       = $this->smtpHost;                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                      // Enable SMTP authentication
            $mail->Username   = $this->smtpUsername;                    // SMTP username
            $mail->Password   = $this->smtpPassword;                    // SMTP password
            $mail->SMTPSecure = "ssl"; //PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = $this->smtpPort;                         // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($this->from, $this->fromName);
            $mail->addAddress($this->to, $this->toName);     // Add a recipient
            $mail->addReplyTo($this->replyTo, $this->replyName);

            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body    = $this->message;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $this->response = 'sent';
            return true;
        } 
        catch (Exception $e) 
        {
            $this->response = "Failed: {$mail->ErrorInfo}";
            return false;
        }

    }

    public function defaultMailer()
    {
        $headers = "MIME-VERSION: $this->mime" . " \r\n";
        $headers .= "Content-type: $this->contentType; charset=$this->charset "."\r\n";
        $headers .= "From: $this->fromName $this->from \r\n";
        $headers .= 'Reply-To: ' .$this->replyTo. "\r\n";

        if(mail($this->to, $this->subject, $this->message, $headers)) {
            return true;
        }

        return false;
    }

    public function sendMail()
    {
        // Send the message
        $this->getMailAttributes();

        if($this->driver == "swiftmailer")
        {
            if($this->createTransport())
            {
                $this->createMailer();
                return $this->mailer->send($this->buildMessage());
            }
        }
        else if($this->driver == "phpmailer")
        {
            return $this->PHPMailer();
        }
        else if($this->driver == "default")
        {
            return $this->defaultMailer(); 
        }

        return false;
    }

}
