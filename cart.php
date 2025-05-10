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

$stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = (SELECT id FROM users WHERE email = ?)');
$stmt->execute([$_SESSION['email']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPrice = 0;
foreach ($cartItems as $item) {
  $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['cart'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="cart-page <?= $themeClass ?>-theme">

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
    <h1 class="cart-title">🛒 <?= $lang['cart_title'] ?></h1>

    <?php if (empty($cartItems)): ?>
      <div class="empty-cart-message">
        <p><?= $lang['cart_empty'] ?> 😢</p>
        <a href="catalog.php" class="empty-cart-link"><?= $lang['go_to_catalog'] ?></a>
      </div>
    <?php else: ?>

      <?php foreach ($cartItems as $item): ?>
        <div class="cart-item">
          <div>
            <h3><?= htmlspecialchars($item['product_name']) ?></h3>
            <p><?= htmlspecialchars($item['price']) ?> ₽ <?= $lang['per_item'] ?></p>
          </div>

          <div class="cart-quantity-controls">
            <form method="POST" action="cart_update.php" style="display:inline;">
              <input type="hidden" name="action" value="decrease">
              <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
              <button type="submit" class="quantity-btn">➖</button>
            </form>

            <span class="quantity-number"><?= htmlspecialchars($item['quantity']) ?></span>

            <form method="POST" action="cart_update.php" style="display:inline;">
              <input type="hidden" name="action" value="increase">
              <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
              <button type="submit" class="quantity-btn">➕</button>
            </form>

            <form method="POST" action="cart_update.php" style="display:inline;">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
              <button type="submit" class="delete-btn">🗑️</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="cart-total">
        <h2><?= $lang['total'] ?>: <?= $totalPrice ?> ₽</h2>
        <a href="checkout.php" class="btn-checkout"><?= $lang['checkout'] ?></a>
      </div>

    <?php endif; ?>
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