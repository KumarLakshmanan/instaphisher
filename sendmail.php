<?php
include_once('vendor/phpmailer/class.phpmailer.php');
include_once('vendor/phpmailer/class.smtp.php');
function sendmail()
{
    $mail = new PHPMailer();
    $mail->IsSMTP();                                      // set mailer to use SMTP
    $mail->SMTPAuth = true;     // turn on SMTP authentication
    $mail->SMTPSecure = "tls";
    $mail->Host = "smtp.gmail.com";  // specify main and backup server
    $mail->Port = 587; // Set the SMTP port i tried and 457
    $mail->Username = 'instagram.car.inc@gmail.com';                // SMTP username
    $mail->Password = 'newmailpass';                  // SMTP password

    $mail->SMTPDebug  = 1;
    // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only

    $mail->From = 'from@yahoo.com';
    $mail->FromName = 'From';
    $mail->AddAddress('to@gmail.com', 'To');  // Add a recipient

    $mail->IsHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <strong>in bold!</strong>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->Send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
    }

    echo 'Message has been sent';
}
sendmail();
