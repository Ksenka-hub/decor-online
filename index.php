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

  <section class="hero">
    <div class="hero-content">
      <h1 class="hero-title"><?= $lang['welcome_title'] ?></h1>
      <p class="hero-subtitle"><?= $lang['welcome_subtitle'] ?></p>
    </div>

    <div class="hero-divider">
      <blockquote class="hero-quote"><?= $lang['quote'] ?></blockquote>
    </div>

    <div class="floating-shapes">
      <span class="shape shape-1"></span>
      <span class="shape shape-2"></span>
      <span class="shape shape-3"></span>
      <span class="shape shape-4"></span>
      <span class="shape shape-5"></span>
      <span class="shape shape-6"></span>
    </div>
  </section>

  <section class="products">
    <h2 class="starfall"><?= $lang['popular_products'] ?></h2>

    <div class="card-container harmony">
      <div class="card small">
        <img src="img/index/1.png" alt="<?= $lang['lamp'] ?>">
        <p><?= $lang['lamp'] ?></p>
        <div class="card-overlay">
          <a href="lighting.php" class="card-link"><?= $lang['go_to'] ?></a>
        </div>
      </div>

      <div class="card medium">
        <img src="img/index/2.png" alt="<?= $lang['candles'] ?>">
        <p><?= $lang['candles'] ?></p>
        <div class="card-overlay">
          <a href="candles.php" class="card-link"><?= $lang['go_to'] ?></a>
        </div>
      </div>

      <div class="card big">
        <img src="img/index/3.png" alt="<?= $lang['cup'] ?>">
        <p><?= $lang['cup'] ?></p>
        <div class="card-overlay">
          <a href="cups.php" class="card-link"><?= $lang['go_to'] ?></a>
        </div>
      </div>

      <div class="card medium">
        <img src="img/index/4.png" alt="<?= $lang['plate'] ?>">
        <p><?= $lang['plate'] ?></p>
        <div class="card-overlay">
          <a href="cups.php" class="card-link"><?= $lang['go_to'] ?></a>
        </div>
      </div>
    </div>

    <div class="catalog-button">
      <a href="catalog.php" class="btn"><?= $lang['view_catalog'] ?></a>
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