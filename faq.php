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
  <title>DÉCOR | <?= $lang['home'] ?></title>
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

  <section class="faq">

    <div class="faq-header">
      <h1 class="faq-title"><?= $lang['faq_title'] ?></h1>
      <p class="faq-subtitle"><?= $lang['faq_subtitle'] ?></p>
    </div>

    <div class="faq-list">
      <details>
        <summary><?= $lang['q1'] ?></summary>
        <p><?= $lang['a1'] ?></p>
      </details>
      <details>
        <summary><?= $lang['q2'] ?></summary>
        <p><?= $lang['a2'] ?></p>
      </details>
      <details>
        <summary><?= $lang['q3'] ?></summary>
        <p><?= $lang['a3'] ?></p>
      </details>
      <details>
        <summary><?= $lang['q4'] ?></summary>
        <p><?= $lang['a4'] ?></p>
      </details>
    </div>

    <div class="faq-form">
      <h2 class="form-title"><?= $lang['form_title'] ?></h2>
      <form action="https://formspree.io/f/xyzwzzvg" method="POST" id="contactForm">
        <div class="form-vertical">
          <input type="text" name="name" placeholder="<?= $lang['placeholder_name'] ?>" required>
          <input type="email" name="email" placeholder="<?= $lang['placeholder_email'] ?>" required>
          <textarea name="message" placeholder="<?= $lang['placeholder_message'] ?>" required></textarea>
          <button type="submit"><?= $lang['submit_btn'] ?></button>
        </div>
      </form>
      <p id="form-response" class="hidden"><?= $lang['thank_you'] ?></p>
    </div>

  </section>

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