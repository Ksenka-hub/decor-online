<?php
session_start();

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$themeClass = $_SESSION['theme'] ?? 'light';

if (!isset($_SESSION['email'])) {
  header('Location: auth.php');
  exit();
}

$username = $_SESSION['username'] ?? 'Гость';
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['checkout'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="<?= $themeClass ?>-theme">

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
    <section class="account-dashboard">
      <h1 class="dashboard-title">
        <?= $lang['welcome'] ?>, <?= htmlspecialchars($username) ?>!
      </h1>

      <div class="dashboard-cards">
        <a href="profile.php" class="dashboard-card">
          <img src="img/account/profile.png" alt="<?= $lang['account'] ?>">
          <h2><?= $lang['account'] ?></h2>
          <p><?= $lang['edit_profile'] ?></p>
        </a>

        <a href="my-orders.php" class="dashboard-card">
          <img src="img/account/orders.png" alt="<?= $lang['my_orders'] ?>">
          <h2><?= $lang['my_orders'] ?></h2>
          <p><?= $lang['view_orders'] ?></p>
        </a>

        <a href="settings.php" class="dashboard-card">
          <img src="img/account/settings.png" alt="<?= $lang['settings'] ?>">
          <h2><?= $lang['settings'] ?></h2>
          <p><?= $lang['manage_security'] ?></p>
        </a>
      </div>
    </section>
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