<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$config = require 'config.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $config['email'];
    $mail->Password = $config['password']; // пароль приложения
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($config['email'], 'SMTP Tester');
    $mail->addAddress($config['email']);
    $mail->Subject = '✅ SMTP работает!';
    $mail->Body = 'Письмо отправлено через Gmail SMTP. Всё работает!';

    $mail->send();
    echo "✅ Письмо успешно отправлено.";
} catch (Exception $e) {
    echo "❌ Ошибка: " . $mail->ErrorInfo;
}