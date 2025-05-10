<?php
session_start();

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$themeClass = $_SESSION['theme'] ?? 'light';

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if (!isset($_SESSION['email'])) {
  header('Location: auth.php');
  exit();
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newUsername = $_POST['username'] ?? $user['username'];
  $newBio = $_POST['bio'] ?? $user['bio'];

  if (!empty($_POST['delete_avatar'])) {
    if (!empty($user['avatar']) && file_exists($user['avatar']) && $user['avatar'] !== 'img/1 Page/Account.png') {
      unlink($user['avatar']);
    }

    $updateStmt = $pdo->prepare('UPDATE users SET username = ?, bio = ?, avatar = NULL WHERE email = ?');
    $updateStmt->execute([$newUsername, $newBio, $_SESSION['email']]);
    $_SESSION['avatar'] = null;

  } elseif (!empty($_FILES['avatar']['name'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $avatarName = uniqid() . '_' . basename($_FILES['avatar']['name']);
    $avatarPath = $uploadDir . $avatarName;

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
      $updateStmt = $pdo->prepare('UPDATE users SET username = ?, bio = ?, avatar = ? WHERE email = ?');
      $updateStmt->execute([$newUsername, $newBio, $avatarPath, $_SESSION['email']]);
      $_SESSION['avatar'] = $avatarPath;
    }
  } else {
    $updateStmt = $pdo->prepare('UPDATE users SET username = ?, bio = ? WHERE email = ?');
    $updateStmt->execute([$newUsername, $newBio, $_SESSION['email']]);
  }

  $_SESSION['username'] = $newUsername;

  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([$_SESSION['email']]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  $success = ""; // Место под сообщение об успехе
}

$avatarToShow = !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : 'img/account/account_logo.png';
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['checkout'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="account-page <?= $themeClass ?>-theme">

  <header>
    <div class="logo-block" id="logoLamp" onclick="highlightBrand()">
      <img src="img/index/logo.svg" alt="Logo">
      <span class="brand-name" id="brandText">DÉCOR</span>
    </div>

    <nav class="main-menu">
      <a href="index.php"><?= $lang['home'] ?></a>
      <a href="catalog.php"><?= $lang['catalog'] ?></a>
      <a href="cart.php"><?= $lang['cart'] ?></a>
      <a href="about.php"><?= $lang['about'] ?></a>
      <a href="reviews.php"><?= $lang['reviews'] ?></a>
      <a href="faq.php"><?= $lang['faq'] ?></a>
    </nav>

    <div class="header-right-blocks">
      <a href="account.php" class="user-account">
        <div class="user-circle">
          <img src="<?= $avatarToShow ?>" alt="User" class="user-icon" />
        </div>
      </a>
      <a href="logout.php" class="login-link"><?= $lang['logout'] ?></a>
    </div>
  </header>

  <div class="page-wrapper">
    <main>
      <section class="profile-section">
        <?php if (!empty($success)): ?>
          <p class="success-message"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
          <div class="profile-card">
            <label for="avatarInput" class="avatar-wrapper">
              <img src="<?= $avatarToShow ?>" alt="<?= $lang['avatar_alt'] ?>" id="avatarPreview">
              <span class="edit-icon-avatar">✍🏻</span>
            </label>
            <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*">

            <div class="profile-info">
              <div class="editable-block">
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                <span class="edit-icon">✍🏻</span>
              </div>

              <div class="editable-block">
                <textarea name="bio" required><?= htmlspecialchars($user['bio'] ?? $lang['default_bio']) ?></textarea>
                <span class="edit-icon">✍🏻</span>
              </div>

              <div class="delete-avatar-block">
                <label class="delete-avatar-label">
                  <input type="checkbox" name="delete_avatar" class="checkbox-hidden">
                  <span class="custom-checkbox"></span>
                  <span class="delete-avatar-text"><?= $lang['delete_avatar'] ?? 'Удалить аватар' ?></span>
                </label>
              </div>

              <button type="submit" class="save-btn"><?= $lang['save_changes'] ?></button>
            </div>
          </div>
        </form>
      </section>
    </main>
  </div>

  <footer class="footer">
    <div class="footer-left">
      <a href="index.php" class="footer-link"><?= $lang['scroll_up'] ?? 'Back to top' ?></a>
    </div>

    <div class="footer-center wechat-footer">
      <img src="img/index/wechat_logo.png" alt="WeChat">
      <span><?= $lang['wechat_message'] ?? 'Message us on WeChat' ?></span>
    </div>

    <div class="footer-right">
      © 2025 Decor. <?= $lang['rights'] ?? 'All rights reserved.' ?>
    </div>
  </footer>

  <div class="modal-overlay" id="wechatModal">
    <div class="wechat-modal">
      <img src="img/index/qr_wechat.jpg" alt="QR WeChat" class="qr-xhs">
      <p class="wechat-text">Сканируй QR-код и напиши нам в WeChat 💬</p>
      <button class="close-wechat">Закрыть</button>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>