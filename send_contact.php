<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // or adjust path as needed

function sendContactEmail($fromName, $fromEmail)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'test.adress9182@gmail.com';      // replace with real Gmail
        $mail->Password = 'nsip cisz cmxx gppw';        // replace with real App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress('kalenyoung03@gmail.com', 'Kalen');

        $mail->isHTML(false);
        $mail->Subject = 'New Contact Request';
        $mail->Body = "User Name: $fromName\nUser Email: $fromEmail\n\nMessage:\nUser is interested in getting in touch.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
