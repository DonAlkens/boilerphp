<?php

namespace App\Messages\Mail;

use App\Config\MailConfig;
use ErrorException;

class MailBuilder extends MailConfig
{

    public $header;

    public $subject;

    public $message;

    public $charset = "UTF-8";

    public $contentType = 'text/html';

    public $mime = "1.0";


    public function __construct()
    {
    }

    public function body()
    {

    }

    public function from($email, $name = '')
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // throw Invalid email address
        }

        $this->from = $email;
        if($name != '') {
            $this->fromName = $name;
        }
        
        return $this;
    }


    public function to($email, $name = '')
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // throw Invalid email address
        }

        $this->to = $email;
        $this->toName = $name;

        return $this;
    }


    public function message($text)
    {

        $this->message .= $text;
        return $this;
    }


    public function paragraph($text)
    {

        $this->message .= "<p>{$text}</p>";
        return $this;
    }


    public function setHeaders( $keys, $value = null)
    {
        if(is_array($keys))
        {
            foreach($keys as $key => $value)
            {
                $this->$key = $value;
            }
        }
        else 
        {
            $this->$keys = $value;
        }

        return $this;
    }


    public function subject($string)
    {

        $this->subject = $string;
        return $this;
    }

    public function template($data, $view = "")
    {

        if ($view == "") {
            $view = strtolower(get_class());
        }

        $this->message = mailPage($view, $data);
        return $this;
    }


}
