<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            header('Location: account.php');
            exit();
        } else {
            header('Location: auth.php?error=invalid_credentials');
            exit();
        }
    } else {
        header('Location: auth.php?error=empty_fields');
        exit();
    }
} else {
    header('Location: auth.php');
    exit();
}
?>