<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/config/load_env.php');
require 'vendor/autoload.php';

function sendEmail($toEmail, $toName, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';            
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER') ?: 'placeholder@gmail.com';      
        $mail->Password   = getenv('SMTP_PASS') ?: '';        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('SMTP_PORT') ?: 587;

        $mail->setFrom(getenv('SMTP_USER') ?: 'placeholder@gmail.com', 'FashionStore Atelier');
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

