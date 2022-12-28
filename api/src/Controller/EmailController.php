<?php

class EmailController
{

    /**
    * @param array $emails Enter emails
    * @param string $subject The subject of the message
    * @param string $message The message that will be sent, it accept format HTML for the styles
    */
    public static function sendMessage(array $emails = [], string $subject, string $message)
    {
        $email = new Email();
        $email->email($subject, $message, $emails);
    }

}
