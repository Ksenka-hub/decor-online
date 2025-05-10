<?php
session_start();
$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";

$theme = $_SESSION['theme'] ?? 'light';
$bodyClass = $theme === 'dark' ? 'password-dark' : 'password-light';

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

$email = $_SESSION['reset_email'] ?? '';
$message = '';

if (!$email) {
    $message = $lang['no_access'] ?? "❌ Нет доступа. Сначала введите код.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password && $password === $confirm) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE email = ?")->execute([$hashed, $email]);
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        unset($_SESSION['reset_email']);
        $message = ($lang['password_updated'] ?? "✅ Пароль обновлён.") . " <a href='auth.php'>" . ($lang['login_link'] ?? 'Войти') . "</a>";
    } else {
        $message = $lang['passwords_mismatch'] ?? "❗ Пароли не совпадают.";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['new_password_title'] ?? 'New Password' ?> | DÉCOR</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="reset-password.css">
</head>
<body class="<?= $bodyClass ?>">

  <div class="password-container">
    <h2>🔐 <?= $lang['new_password_title'] ?? 'New Password' ?></h2>

    <?php if (!$message): ?>
      <form method="POST">
        <input type="password" name="password" placeholder="<?= $lang['new_password_placeholder'] ?? 'New password' ?>" required>
        <input type="password" name="confirm_password" placeholder="<?= $lang['repeat_password_placeholder'] ?? 'Repeat password' ?>" required>
        <button type="submit"><?= $lang['btn_change_password'] ?? 'Change Password' ?></button>
      </form>
    <?php endif; ?>

    <?php if ($message): ?>
      <div class="password-message"><?= $message ?></div>
    <?php endif; ?>
  </div>

</body>
</html>