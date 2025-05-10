<?php
session_start();
$langCode = $_SESSION['language'] ?? 'ru';
$theme = $_SESSION['theme'] ?? 'light';
$bodyClass = $theme === 'dark' ? 'verify-dark' : 'verify-light';

$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $code = $_POST['code'] ?? '';

    if (!empty($email) && !empty($code)) {
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND code = ?");
        $stmt->execute([$email, $code]);
        $result = $stmt->fetch();

        if ($result) {
            $_SESSION['reset_email'] = $email;
            header("Location: reset-password.php");
            exit;
        } else {
            $message = $lang['invalid_code'];
        }
    } else {
        $message = $lang['fill_email_code'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['verify_code_title'] ?> | DÉCOR</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="verify-code.css">
</head>
<body class="<?= $bodyClass ?>">
  <div class="verify-container">
    <h2>🔑 <?= $lang['verify_code_title'] ?></h2>
    <form action="verify-code.php" method="POST">
      <input type="email" name="email" placeholder="<?= $lang['email_placeholder'] ?>" required>
      <input type="text" name="code" placeholder="<?= $lang['code_placeholder'] ?>" required>
      <button type="submit"><?= $lang['btn_verify_code'] ?></button>
    </form>
    <?php if ($message): ?>
      <div class="verify-message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
  </div>
</body>
</html>