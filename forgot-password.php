<?php
session_start();
$langCode = $_SESSION['language'] ?? 'ru';
$themeClass = $_SESSION['theme'] ?? 'light';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$message = '';
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['forgot_password_title'] ?> | DÉCOR</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="reset-page <?= $themeClass ?>-theme">

  <div class="reset-box">
    <h2>🔒 <?= $lang['forgot_password_heading'] ?></h2>
    <form action="send_email.php" method="POST">
      <input type="email" name="email" placeholder="<?= $lang['email_placeholder'] ?>" required>
      <button type="submit">📨 <?= $lang['send_link_btn'] ?></button>
    </form>
  </div>

</body>
</html>