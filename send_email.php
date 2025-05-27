<?php
// send_email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendVerificationEmail($toEmail, $code)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP config
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';              // Replace if not Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'test.adress9182@gmail.com';   // Your SMTP username
        $mail->Password = 'nsip cisz cmxx gppw';       // Use app password, not normal one
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your_gmail@gmail.com', 'Your Site');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Email Verification Code';
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="UTF-8" />
        <title>Email Verification</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 600px; margin: auto; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <tr>
            <td style="padding: 30px; text-align: center; border-bottom: 1px solid #eaeaea;">
                <h2 style="color: #0d6efd; margin: 0;">Verify Your Email</h2>
            </td>
            </tr>
            <tr>
            <td style="padding: 30px; color: #333;">
                <p style="font-size: 16px; line-height: 1.5;">
                Hello,
                </p>
                <p style="font-size: 16px; line-height: 1.5;">
                Thank you for registering. To complete your signup, please use the verification code below:
                </p>
                <p style="font-size: 24px; font-weight: bold; text-align: center; margin: 30px 0; color: #0d6efd; letter-spacing: 4px;">
                ' . htmlspecialchars($code) . '
                </p>
                <p style="font-size: 16px; line-height: 1.5;">
                If you did not request this, please ignore this email.
                </p>
                <p style="font-size: 16px; line-height: 1.5; margin-top: 40px;">
                Cheers,<br />
                The Team
                </p>
            </td>
            </tr>
        </table>
        </body>
        </html>
        ';


        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
