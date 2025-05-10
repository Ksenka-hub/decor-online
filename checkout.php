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

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();
$user_id = $user['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $details = $_POST['orderDetails'] ?? '';
  $order_id = uniqid("DC-");

  $cart = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
  $cart->execute([$user_id]);
  $items = $cart->fetchAll();

  foreach ($items as $item) {
    $insert = $pdo->prepare("INSERT INTO orders (user_id, product_name, quantity, order_id) VALUES (?, ?, ?, ?)");
    $insert->execute([$user_id, $item['product_name'], $item['quantity'], $order_id]);
  }

  $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

  $_SESSION['order_message'] = "🎉 Спасибо за заказ! Номер: $order_id";
  header('Location: my-orders.php');
  exit();
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
          <img src="<?= !empty($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'img/account/account_logo.png' ?>" alt="User" class="user-icon" />
        </div>
      </a>
      <a href="logout.php" class="login-link"><?= $lang['logout'] ?></a>
    <?php else: ?>
      <a href="auth.php" class="login-link"><?= $lang['login'] ?></a>
    <?php endif; ?>
  </div>
</header>
  
<div class="container">
  <h1><?= $lang['checkout_title'] ?></h1>

  <div class="order-summary">
    <h2><?= $lang['your_order'] ?></h2>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total = 0;
    $items = [];

    while ($row = $stmt->fetch()) {
      echo "<p>🕯️ {$row['product_name']} × {$row['quantity']}</p>";
      $items[] = "{$row['product_name']} × {$row['quantity']}";
      $total += $row['quantity'] * 1000; // Здесь можно позже сделать гибко
    }
    ?>
    <p class="total"><?= $lang['total'] ?>: <?= $total ?> ₽</p>
  </div>

  <div class="payment-section">
    <h2><?= $lang['payment'] ?></h2>
    <p><?= $lang['scan_qr'] ?></p>
    <img src="img/checkout/wechat_pay.JPG" alt="QR" class="qr-img">

    <form method="POST">
      <input type="text" name="name" placeholder="<?= $lang['your_name'] ?>" required>
      <input type="email" name="email" placeholder="Email" required>
      <textarea name="orderDetails" hidden><?= implode("\n", $items) ?></textarea>
      <button type="submit"><?= $lang['confirm_order'] ?></button>
    </form>
  </div>
</div>

<footer class="footer">
  <div class="footer-left">
    <a href="index.php" class="footer-link"><?= $lang['scroll_up'] ?? 'Back to top' ?></a>
  </div>

  <div class="footer-center wechat-footer">
    <img src="img/index/wechat_logo.png" alt="WeChat">
    <span><?= $lang['wechat_message'] ?? 'Message us on WeChat' ?></span>
  </div>

  <div class="footer-right">© 2025 Decor. <?= $lang['rights'] ?? 'All rights reserved.' ?></div>
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