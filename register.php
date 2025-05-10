<?php
session_start();


$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        if ($stmt->execute([$username, $email, $hashedPassword])) {
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            header('Location: account.php');
            exit();
        } else {
            echo "❌ Ошибка при регистрации. Попробуй другой email.";
        }
    } else {
        echo "❗ Пожалуйста, заполните все поля.";
    }
}
?>