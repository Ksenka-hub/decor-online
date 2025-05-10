<?php
session_start();

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$themeClass = $_SESSION['theme'] ?? 'light';
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['about'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="about-page <?= $themeClass ?>-theme">

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
    <section class="about">
      <div class="about-header">
        <h1 class="about-title"><?= $lang['about_title'] ?></h1>
        <p class="about-subtitle"><?= $lang['about_subtitle'] ?></p>
      </div>

      <div class="about-block">
        <p><?= $lang['about_text_1'] ?></p>
        <p><?= $lang['about_text_2'] ?></p>
      </div>

      <div class="about-trust">
        <div class="trust-item">
          <span class="icon">💜</span>
          <p><?= $lang['about_trust_1'] ?></p>
        </div>
        <div class="trust-item">
          <span class="icon">✔️</span>
          <p><?= $lang['about_trust_2'] ?></p>
        </div>
        <div class="trust-item">
          <span class="icon">🛋️</span>
          <p><?= $lang['about_trust_3'] ?></p>
        </div>
      </div>

      <div class="about-contacts">
        <p>Email: <a href="mailto:hello@decor.com">hello@decor.com</a></p>
        <p>
          <?= $lang['about_xhs_link'] ?>
          <a href="#" class="xiaohongshu-link"><?= $lang['about_xhs_text'] ?></a>
        </p>
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

  <div class="modal-overlay" id="xiaohongshuModal">
    <div class="modal-xhs-content">
      <img src="img/about/qrcode_xiaohongshu.jpg" alt="QR Xiaohongshu" class="qr-xhs">
      <p class="xhs-text"><?= $lang['about_xhs_modal'] ?></p>
      <button class="close-xhs"><?= $lang['modal_close'] ?></button>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>