<?php
session_start();
$langCode = $_SESSION['language'] ?? 'ru';
$theme = $_SESSION['theme'] ?? 'light';
$bodyClass = $theme === 'dark' ? 'mail-dark' : 'mail-light';

$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';

    if (empty($email)) {
        echo "❌ {$lang['email_required']}";
        exit;
    }

    $code = random_int(100000, 999999);

    // Обновляем код сброса
    $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
    $pdo->prepare("INSERT INTO password_resets (email, code) VALUES (?, ?)")->execute([$email, $code]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kellylife.vlog@gmail.com';
        $mail->Password = 'vcnitrgfoaryfzdc'; // пароль приложения
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('kellylife.vlog@gmail.com', 'DÉCOR');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $lang['email_subject'];
        $mail->Body = "
            <h2>{$lang['email_heading']}</h2>
            <p>{$lang['email_instruction']}</p>
            <div style='font-size: 24px; font-weight: bold; margin: 20px 0;'>$code</div>
            <p style='font-size: 13px; color: #888;'>{$lang['email_warning']}</p>
        ";

        $mail->send();

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="$langCode">
        <head>
            <meta charset="UTF-8">
            <title>{$lang['check_mail_title']} | DÉCOR</title>
            <meta http-equiv="refresh" content="5;url=verify-code.php">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="mail-sent.css">
        </head>
        <body class="$bodyClass">
            <div class="mail-box">
                <h1>📨 {$lang['email_sent_to']} <br><strong>$email</strong></h1>
                <p class="note">{$lang['check_mail_instruction']}</p>
                <p class="note">{$lang['auto_redirect_note']}</p>
            </div>
        </body>
        </html>
        HTML;
    } catch (Exception $e) {
        echo "❌ {$lang['email_error']}: {$mail->ErrorInfo}";
    }
} else {
    echo "❌ {$lang['invalid_request']}";
}