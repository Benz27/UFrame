<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/functions.php';
require_once __DIR__.'/config.php';



// Basic check to make sure the form was submitted.
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // echo("The form must be submitted with POST data.");
}

// Do some validation, check to make sure the name, email and message are valid.
// if (empty($_POST['g-recaptcha-response'])) {
//     redirectWithError("Please complete the CAPTCHA.");
// }

// $recaptcha = new \ReCaptcha\ReCaptcha(CONTACTFORM_RECAPTCHA_SECRET_KEY);
// $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_REQUEST['REMOTE_ADDR']);

// if (!$resp->isSuccess()) {
//     $errors = $resp->getErrorCodes();
//     $error = $errors[0];

//     $recaptchaErrorMapping = [
//         'missing-input-secret' => 'No reCAPTCHA secret key was submitted.',
//         'invalid-input-secret' => 'The submitted reCAPTCHA secret key was invalid.',
//         'missing-input-response' => 'No reCAPTCHA response was submitted.',
//         'invalid-input-response' => 'The submitted reCAPTCHA response was invalid.',
//         'bad-request' => 'An unknown error occurred while trying to validate your response.',
//         'timeout-or-duplicate' => 'The request is no longer valid. Please try again.',
//     ];

//     $errorMessage = $recaptchaErrorMapping[$error];
//     redirectWithError("Please retry the CAPTCHA: ".$errorMessage);
// }

if (empty($_POST['name'])) {
    echo("Please enter your name in the form.");
}

if (empty($_POST['email'])) {
   echo("Please enter your email address in the form.");
}

if (empty($_POST['subject'])) {
    echo("Please enter your message in the form.");
}

if (empty($_POST['message'])) {
    echo("Please enter your message in the form.");
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo("Please enter a valid email address.");
}

if (strlen($_POST['message']) < 10) {
    echo("Please enter at least 10 characters in the message field.");
}

// Everything seems OK, time to send the email.

$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = CONTACTFORM_SMTP_HOSTNAME;
    $mail->SMTPAuth = true;
    $mail->Username = CONTACTFORM_SMTP_USERNAME;
    $mail->Password = CONTACTFORM_SMTP_PASSWORD;
    $mail->SMTPSecure = CONTACTFORM_SMTP_ENCRYPTION;
    $mail->Port = CONTACTFORM_SMTP_PORT;

    // Recipients
    $mail->setFrom(CONTACTFORM_FROM_ADDRESS, CONTACTFORM_FROM_NAME);
     $mail->addAddress($_POST['email'], $_POST['name']);
    // $mail->addReplyTo(CONTACTFORM_FROM_ADDRESS, CONTACTFORM_FROM_NAME);

    // Content
    $mail->Subject =$_POST['subject'];
    $mail->Body    = <<<EOT
{$_POST['message']}

EOT;

    $mail->send();
 
} catch (Exception $e) {
   echo ("An error occurred while trying to send your message: ".$mail->ErrorInfo);
}
