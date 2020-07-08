<?php 

namespace App\Messages;
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once("PHPMailer/src/PHPMailer.php");
include_once("PHPMailer/src/SMTP.php");
include_once("PHPMailer/src/Exception.php");

class Mail {

    public $sender;

    public $sender_name = "APRILVINES";

    public $receiver;

    public $receiver_name;

    public $reply_mail;

    public $subject;
    
    public $message;
    
    public $header;

    public $is_smtp = true;

    public $port  = 587;

    public $smtp_host = "mail.aprilvines.com";

    public $smtp_user = "info@aprilvines.com";

    public $smtp_pass = "";

    public function __construct($sender = null, $receiver = null, $subject = null,$message = null, $header = null)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->subject = $subject;
        $this->message = $message;
        $this->header = $header;
    }

    public function send()
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   // Enable verbose debug output
            $mail->isSMTP();                                         // Send using SMTP
            // $mail->Host       = $this->smtp_host;                    // Set the SMTP server to send through
            // $mail->SMTPAuth   = $this->is_smtp;                      // Enable SMTP authentication
            // $mail->Username   = $this->smtp_user;                    // SMTP username
            // $mail->Password   = $this->smtp_pass;                    // SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            // $mail->Port       = $this->port;                         // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->SMTPAutoTLS = false; 
            $mail->Port = 25;

            //Recipients
            $mail->setFrom($this->sender, $this->sender_name);
            $mail->addAddress($this->receiver, $this->receiver_name);     // Add a recipient
            $mail->addAddress($this->sender);               // Name is optional
            $mail->addReplyTo($this->reply_mail, $this->sender_name);

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
            // echo $this->response;
            return true;
        } catch (Exception $e) {
            $this->response = "Failed: {$mail->ErrorInfo}";
            // echo $this->response;
            return false;
        }
        
    }
    
    public function send2()
    {
        $headers = "MIME-VERSION: 1.0" . " \r\n";
        $headers .= "Content-type: text/html; charset=UTF-8 "."\r\n";
        $headers .= "From: April Vines <$this->smtp_user> \r\n";
        $headers .= 'Reply-To: ' .$this->reply_mail . "\r\n";
        
        if(mail($this->receiver, $this->subject, $this->message, $headers)){
           return true;
        } else {
            return false;
        }
    }

    public function template($template, $data)
    {
        $template_content = file_get_contents($template);
        if($template_content !== ""){

            foreach($data as $key => $value){
                $pass_data = preg_replace('/\{\{'.$key.'\}\}/', $value,  $template_content);
                $template_content = $pass_data;
            }
            $message =  $pass_data;
            return $message;
        }
        return null;
    }


}

?>