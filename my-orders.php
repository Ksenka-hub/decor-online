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

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();
$user_id = $user['id'] ?? null;

if (isset($_POST['delete_order_id'])) {
  $deleteStmt = $pdo->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?");
  $deleteStmt->execute([$_POST['delete_order_id'], $user_id]);
  $_SESSION['order_message'] = "🗑️ Заказ успешно удалён.";
  header('Location: my-orders.php');
  exit();
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

$message = $_SESSION['order_message'] ?? null;
unset($_SESSION['order_message']);
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['checkout'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="orders-page <?= $themeClass ?>-theme">
  <div class="page-wrapper-orders">

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
      <h1>💭 <?= $lang['my_orders_title'] ?></h1>

      <?php if ($message): ?>
        <p class="success-message"><?= $message ?></p>
      <?php endif; ?>

      <div class="orders-container">
        <?php if (count($orders) > 0): ?>
          <?php foreach ($orders as $order): ?>
            <div class="order">
              <h3><?= $lang['order_number'] ?> #<?= htmlspecialchars($order['id']) ?></h3>
              <p><strong><?= $lang['order_product'] ?>:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
              <p><strong><?= $lang['order_quantity'] ?>:</strong> <?= $order['quantity'] ?></p>
              <p><strong><?= $lang['order_date'] ?>:</strong> <?= date("d.m.Y H:i", strtotime($order['created_at'])) ?></p>
              <form method="POST" class="delete-form" onsubmit="return confirm('<?= $lang['delete_confirm'] ?>');">
                <input type="hidden" name="delete_order_id" value="<?= $order['id'] ?>">
                <button type="submit" class="delete-btn"><?= $lang['delete_btn'] ?></button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-orders"><?= $lang['no_orders_message'] ?></div>
        <?php endif; ?>
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

  </div>
  <script src="script.js"></script>
</body>
</html>