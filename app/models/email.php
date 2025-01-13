<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/../config/db.php');

require '../vendor/autoload.php';

class Email extends Db
{

    public function sendMail($password)
    {


        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'sandbox.smtp.mailtrap.io';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = '3ebf42a1a995fc';                 // SMTP username
            $mail->Password = '58f8c1f91e7dc7';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('a.guezadi@gmail.com');
            $mail->addAddress("a.guezadi@gmail.com");     // Add a recipient              // Name is optional



            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is Your Password';
            $mail->Body    = 'Here is Your Password for the user ' .  $password;

            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}
