<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

// Проверка авторизации
if (!isset($_SESSION['email'])) {
    header('Location: auth.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $avatarPath = '';

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = 'uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }
        $avatarName = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $avatarPath = $uploadsDir . $avatarName;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath);
    }

    if (empty($avatarPath)) {
        // Если новое фото не выбрано — не меняем старое
        $stmt = $pdo->prepare('UPDATE users SET username = ?, bio = ? WHERE email = ?');
        $stmt->execute([$username, $bio, $_SESSION['email']]);
    } else {
        // Если новое фото выбрано — обновляем и аватар
        $stmt = $pdo->prepare('UPDATE users SET username = ?, bio = ?, avatar = ? WHERE email = ?');
        $stmt->execute([$username, $bio, $avatarPath, $_SESSION['email']]);
    }

    $_SESSION['username'] = $username;
    header('Location: account.php');
    exit();
}
?>