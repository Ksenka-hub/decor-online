<?php
session_start();

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $code = $_POST['code'] ?? '';
    $username = $_SESSION['reg_username'] ?? '';
    $password = $_SESSION['reg_password'] ?? '';

    if (!empty($email) && !empty($code)) {
        $stmt = $pdo->prepare("SELECT * FROM email_verifications WHERE email = ? AND code = ?");
        $stmt->execute([$email, $code]);
        $result = $stmt->fetch();

        if ($result) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $defaultAvatar = 'img/account/account_logo.png';

            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$username, $email, $hashedPassword, $defaultAvatar])) {
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['avatar'] = $defaultAvatar;
                $pdo->prepare("DELETE FROM email_verifications WHERE email = ?")->execute([$email]);
                unset($_SESSION['reg_username'], $_SESSION['reg_password']);
                header('Location: account.php');
                exit();
            } else {
                $message = $lang['error_account_create'] ?? "❌ Ошибка при создании аккаунта.";
            }
        } else {
            $message = $lang['invalid_code'] ?? "❌ Неверный код подтверждения.";
        }
    } else {
        $message = $lang['enter_email_code'] ?? "❗ Введите email и код.";
    }
}

$themeClass = $_SESSION['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['email_verification_title'] ?? 'Email Verification' ?> | DÉCOR</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="verify-email.css">
</head>
<body class="verify-page <?= $themeClass ?>-theme">
  <div class="verify-box">
    <h2>📨 <?= $lang['email_verification_title'] ?? 'Email Verification' ?></h2>
    <form method="POST">
      <input type="email" name="email" placeholder="<?= $lang['email_placeholder'] ?? 'Your email' ?>" required>
      <input type="text" name="code" placeholder="<?= $lang['code_placeholder'] ?? 'Verification code' ?>" required>
      <button type="submit"><?= $lang['btn_verify'] ?? 'Verify' ?></button>
    </form>
    <?php if ($message): ?>
      <div class="verify-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
  </div>
</body>
</html>