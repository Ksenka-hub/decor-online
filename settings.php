<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$themeClass = $_SESSION['theme'] ?? 'light';

if (!isset($_SESSION['email'])) {
  header('Location: auth.php');
  exit();
}

function displayFlashMessages() {
  if (!empty($_SESSION['success'])) {
    echo '<div class="flash success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
  }

  if (!empty($_SESSION['error'])) {
    echo '<div class="flash error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
  }
}
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['checkout'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="settings-page <?= $themeClass ?>-theme">

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
      <?php if (isset($_SESSION['email'])): ?>
        <a href="account.php" class="user-account">
          <div class="user-circle">
            <img 
              src="<?= !empty($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'img/account/account_logo.png' ?>" 
              alt="User" 
              class="user-icon" 
            />
          </div>
        </a>
        <a href="logout.php" class="login-link"><?= $lang['logout'] ?></a>
      <?php else: ?>
        <a href="auth.php" class="login-link"><?= $lang['login'] ?></a>
      <?php endif; ?>
    </div>
  </header>

  <main>

    <?php displayFlashMessages(); ?>

    <form method="POST" action="update_settings.php" class="settings-form">
      <div class="settings-section">
        <h2>🔒 <?= $lang['change_password'] ?></h2>

        <div class="input-group">
          <label for="current_password"><?= $lang['current_password'] ?></label>
          <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="input-group">
          <label for="new_password"><?= $lang['new_password'] ?></label>
          <input type="password" id="new_password" name="new_password" required>
        </div>

        <div class="input-group">
          <label for="confirm_password"><?= $lang['confirm_password'] ?></label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" name="change_password" class="btn-save">
          <?= $lang['btn_change_password'] ?>
        </button>
      </div>
    </form>

    <form method="POST" action="update_settings.php" class="settings-form">
      <div class="settings-section">
        <h2>🌍 <?= $lang['language_interface'] ?></h2>

        <div class="input-group">
          <label for="language"><?= $lang['select_language'] ?></label>
          <select id="language" name="language" required>
            <option value="ru" <?= ($_SESSION['language'] ?? '') === 'ru' ? 'selected' : '' ?>>Русский 🇷🇺</option>
            <option value="en" <?= ($_SESSION['language'] ?? '') === 'en' ? 'selected' : '' ?>>English 🇬🇧</option>
            <option value="zh" <?= ($_SESSION['language'] ?? '') === 'zh' ? 'selected' : '' ?>>中文 🇨🇳</option>
          </select>
        </div>

        <button type="submit" name="change_language" class="btn-save">
          <?= $lang['btn_save_language'] ?>
        </button>
      </div>
    </form>

    <form method="POST" action="update_settings.php" class="settings-form">
      <div class="settings-section">
        <h2>🎨 <?= $lang['site_theme'] ?></h2>

        <div class="input-group">
          <label for="theme"><?= $lang['select_theme'] ?></label>
          <?php $currentTheme = $_SESSION['theme'] ?? 'light'; ?>
          <select id="theme" name="theme" required>
            <option value="light" <?= $currentTheme === 'light' ? 'selected' : '' ?>>
              <?= $lang['theme_light'] ?>
            </option>
            <option value="dark" <?= $currentTheme === 'dark' ? 'selected' : '' ?>>
              <?= $lang['theme_dark'] ?>
            </option>
            <option value="auto" <?= $currentTheme === 'auto' ? 'selected' : '' ?>>
              <?= $lang['theme_auto'] ?>
            </option>
          </select>
        </div>

        <button type="submit" name="change_theme" class="btn-save">
          <?= $lang['btn_save_theme'] ?>
        </button>
      </div>
    </form>

  </main>

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