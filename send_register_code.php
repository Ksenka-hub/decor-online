<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        echo "<p style='color:red;'>❌ Заполни все поля.</p>";
        exit;
    }

    // 1. Хэшируем пароль
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 2. Сохраняем данные в сессию
    $_SESSION['reg_username'] = $username;
    $_SESSION['reg_password'] = $password; // сохраняем в чистом виде, чтобы можно было захешировать позже, если хочешь
    $_SESSION['register'] = [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    ];

    // 3. Генерируем 6-значный код
    $code = random_int(100000, 999999);

    // 4. Обновляем код подтверждения в БД
    $pdo->prepare("DELETE FROM email_verifications WHERE email = ?")->execute([$email]);
    $pdo->prepare("INSERT INTO email_verifications (email, code) VALUES (?, ?)")->execute([$email, $code]);

    // 5. Отправляем письмо
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kellylife.vlog@gmail.com';
        $mail->Password = 'vcnitrgfoaryfzdc'; // Убедись, что это app-password, а не обычный
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('kellylife.vlog@gmail.com', 'DÉCOR');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = '🔐 Код для подтверждения почты';
        $mail->Body = "<h2>Подтверди свою почту</h2><p>Твой код: <strong>$code</strong></p>";

        $mail->send();
        header('Location: verify-register-code.php');
        exit;
    } catch (Exception $e) {
        echo "❌ Ошибка при отправке письма: {$mail->ErrorInfo}";
    }
}