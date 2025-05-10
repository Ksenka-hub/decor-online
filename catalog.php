<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

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
  <title>DÉCOR | <?= $lang['catalog'] ?></title>
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

  <main class="catalog">
    <h1 class="catalog-title"><?= $lang['catalog_title'] ?></h1>
    <p class="catalog-subtitle"><?= $lang['catalog_subtitle'] ?></p>

    <div class="catalog-grid">

      <div class="catalog-card">
        <img src="img/catalog/1.png" alt="<?= $lang['candles_title'] ?>">
        <h3><?= $lang['candles_title'] ?></h3>
        <p><?= $lang['candles_desc'] ?></p>
        <a href="candles.php" class="catalog-btn"><?= $lang['view_button'] ?></a>
      </div>

      <div class="catalog-card">
        <img src="img/catalog/2.png" alt="<?= $lang['cups_title'] ?>">
        <h3><?= $lang['cups_title'] ?></h3>
        <p><?= $lang['cups_desc'] ?></p>
        <a href="cups.php" class="catalog-btn"><?= $lang['view_button'] ?></a>
      </div>

      <div class="catalog-card">
        <img src="img/catalog/3.png" alt="<?= $lang['textiles_title'] ?>">
        <h3><?= $lang['textiles_title'] ?></h3>
        <p><?= $lang['textiles_desc'] ?></p>
        <a href="textiles.php" class="catalog-btn"><?= $lang['view_button'] ?></a>
      </div>

      <div class="catalog-card">
        <img src="img/catalog/4.png" alt="<?= $lang['lighting_title'] ?>">
        <h3><?= $lang['lighting_title'] ?></h3>
        <p><?= $lang['lighting_desc'] ?></p>
        <a href="lighting.php" class="catalog-btn"><?= $lang['view_button'] ?></a>
      </div>

      <div class="catalog-card">
        <img src="img/catalog/5.png" alt="<?= $lang['organizers_title'] ?>">
        <h3><?= $lang['organizers_title'] ?></h3>
        <p><?= $lang['organizers_desc'] ?></p>
        <a href="organizers.php" class="catalog-btn"><?= $lang['view_button'] ?></a>
      </div>

      <div class="catalog-card">
        <img src="img/catalog/6.png" alt="<?= $lang['decor_title'] ?>">
        <h3><?= $lang['decor_title'] ?></h3>
        <p><?= $lang['decor_desc'] ?></p>
        <a href="decor.php" class="catalog-btn"><?= $lang['view_button'] ?></a>
      </div>

    </div>
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