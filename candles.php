<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$themeClass = $_SESSION['theme'] ?? 'light';

$cartItems = [];
$totalPrice = 0;

if (isset($_SESSION['email'])) {
    $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = (SELECT id FROM users WHERE email = ?)');
    $stmt->execute([$_SESSION['email']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cartItems as $item) {
        $totalPrice += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DÉCOR | <?= $lang['candles'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="<?= $themeClass ?>-theme" data-logged-in="<?= isset($_SESSION['email']) ? 'true' : 'false' ?>">

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

<main>
  <h1 class="section-title">🛋️ <?= $lang['candles_title'] ?></h1>
  <div class="product-grid">

     <div class="product-card new">
  <div class="badge">new</div>

  <img src="img/сandles/1.png" alt="<?= $lang['candle_1_name'] ?>">
  <h3><?= $lang['candle_1_name'] ?></h3>
  <p class="price">1200 ₽</p>
  <p><?= $lang['candle_1_desc'] ?></p>

  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 7×9 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['soy_wax'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['lavender_color'] ?></li>
  </ul>
  
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="1">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_1_name']) ?>">
    <input type="hidden" name="price" value="1200">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card new">
  <div class="badge">new</div>
  <img src="img/сandles/2.png" alt="<?= $lang['candle_2_name'] ?>">
  <h3><?= $lang['candle_2_name'] ?></h3>
  <p class="price">1100 ₽</p>
  <p><?= $lang['candle_2_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 6×8 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['paraffin'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['cream_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="2">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_2_name']) ?>">
    <input type="hidden" name="price" value="1100">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card">
  <div class="badge">new</div>
  <img src="img/сandles/3.png" alt="<?= $lang['candle_3_name'] ?>">
  <h3><?= $lang['candle_3_name'] ?></h3>
  <p class="price">1000 ₽</p>
  <p><?= $lang['candle_3_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 6×7 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['beeswax'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['orange_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="3">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_3_name']) ?>">
    <input type="hidden" name="price" value="1000">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card">
  <img src="img/сandles/4.png" alt="<?= $lang['candle_4_name'] ?>">
  <h3><?= $lang['candle_4_name'] ?></h3>
  <p class="price">1300 ₽</p>
  <p><?= $lang['candle_4_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 7×10 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['soy_wax'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['gray_green_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="4">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_4_name']) ?>">
    <input type="hidden" name="price" value="1300">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card new">
  <div class="badge">new</div>
  <img src="img/сandles/5.png" alt="<?= $lang['candle_5_name'] ?>">
  <h3><?= $lang['candle_5_name'] ?></h3>
  <p class="price">1250 ₽</p>
  <p><?= $lang['candle_5_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 6×9 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['paraffin'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['pink_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="5">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_5_name']) ?>">
    <input type="hidden" name="price" value="1250">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card">
  <img src="img/сandles/6.png" alt="<?= $lang['candle_6_name'] ?>">
  <h3><?= $lang['candle_6_name'] ?></h3>
  <p class="price">950 ₽</p>
  <p><?= $lang['candle_6_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 5×8 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['wax_mix'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['white_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="6">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_6_name']) ?>">
    <input type="hidden" name="price" value="950">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card">
  <img src="img/сandles/7.png" alt="<?= $lang['candle_7_name'] ?>">
  <h3><?= $lang['candle_7_name'] ?></h3>
  <p class="price">1400 ₽</p>
  <p><?= $lang['candle_7_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 7×11 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['paraffin'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['blue_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="7">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_7_name']) ?>">
    <input type="hidden" name="price" value="1400">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
</div>

<div class="product-card">
  <img src="img/сandles/8.png" alt="<?= $lang['candle_8_name'] ?>">
  <h3><?= $lang['candle_8_name'] ?></h3>
  <p class="price">1100 ₽</p>
  <p><?= $lang['candle_8_desc'] ?></p>
  <ul class="characteristics">
    <li><?= $lang['size'] ?>: 6×9 см</li>
    <li><?= $lang['material'] ?>: <?= $lang['soy_wax'] ?></li>
    <li><?= $lang['color'] ?>: <?= $lang['brown_color'] ?></li>
  </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
    <input type="hidden" name="product_id" value="8">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['candle_8_name']) ?>">
    <input type="hidden" name="price" value="1100">
    <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
  <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
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

  <div class="footer-right">© 2025 Decor. <?= $lang['rights'] ?? 'All rights reserved.' ?></div>
</footer>

<div class="modal-overlay" id="wechatModal">
  <div class="wechat-modal">
    <img src="img/index/qr_wechat.jpg" alt="QR WeChat" class="qr-xhs">
    <p class="wechat-text">Сканируй QR-код и напиши нам в WeChat 💬</p>
    <button class="close-wechat">Закрыть</button>
  </div>
</div>

<!-- Плавающая корзина -->
<a href="cart.php" class="floating-cart" id="floatingCart">
  <img src="img/catalog/shopping-cart.png" alt="<?= $lang['cart_alt'] ?>">
  <span id="cartCount">0</span>
</a>

<!-- Модальное окно "Посмотреть поближе" -->
<div class="modal-overlay" id="modal">
  <div class="modal-content">
    <span class="modal-close" id="modal-close">×</span>
    <img id="modal-image" src="" alt="<?= $lang['preview_modal_alt'] ?>">
  </div>
</div>

<script src="script.js?v=1.0"></script>

</body>
</html>
