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
  <title>DÉCOR | <?= $lang['lighting'] ?></title>
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
  <h1 class="section-title">💡 <?= $lang['lighting_title'] ?></h1>
  <div class="product-grid">

    <div class="product-card">
      <img src="img/lighting/1.PNG" alt="<?= $lang['lamp_1_name'] ?>">
      <h3><?= $lang['lamp_1_name'] ?></h3>
      <p class="price">1800 ₽</p>
      <p><?= $lang['lamp_1_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 14×16 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['cream_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="301">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_1_name']) ?>">
        <input type="hidden" name="price" value="1800">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/lighting/2.PNG" alt="<?= $lang['lamp_2_name'] ?>">
      <h3><?= $lang['lamp_2_name'] ?></h3>
      <p class="price">4300 ₽</p>
      <p><?= $lang['lamp_2_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 135×40 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['metal_paper'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['beige_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="302">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_2_name']) ?>">
        <input type="hidden" name="price" value="4300">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/lighting/3.PNG" alt="<?= $lang['lamp_3_name'] ?>">
      <h3><?= $lang['lamp_3_name'] ?></h3>
      <p class="price">1600 ₽</p>
      <p><?= $lang['lamp_3_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 10×20 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['gold_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="303">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_3_name']) ?>">
        <input type="hidden" name="price" value="1600">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/lighting/4.PNG" alt="<?= $lang['lamp_4_name'] ?>">
      <h3><?= $lang['lamp_4_name'] ?></h3>
      <p class="price">990 ₽</p>
      <p><?= $lang['lamp_4_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 30×25 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['textile_ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['milk_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="304">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_4_name']) ?>">
        <input type="hidden" name="price" value="990">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/lighting/5.PNG" alt="<?= $lang['lamp_5_name'] ?>">
      <h3><?= $lang['lamp_5_name'] ?></h3>
      <p class="price">2100 ₽</p>
      <p><?= $lang['lamp_5_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 22×14 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['warm_white_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="305">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_5_name']) ?>">
        <input type="hidden" name="price" value="2100">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/lighting/6.PNG" alt="<?= $lang['lamp_6_name'] ?>">
      <h3><?= $lang['lamp_6_name'] ?></h3>
      <p class="price">1750 ₽</p>
      <p><?= $lang['lamp_6_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 30×20 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['metal'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['pink_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="306">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_6_name']) ?>">
        <input type="hidden" name="price" value="1750">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/lighting/7.PNG" alt="<?= $lang['lamp_7_name'] ?>">
      <h3><?= $lang['lamp_7_name'] ?></h3>
      <p class="price">1900 ₽</p>
      <p><?= $lang['lamp_7_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 15×16 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['orange_red_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="307">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_7_name']) ?>">
        <input type="hidden" name="price" value="1900">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/lighting/8.PNG" alt="<?= $lang['lamp_8_name'] ?>">
      <h3><?= $lang['lamp_8_name'] ?></h3>
      <p class="price">1700 ₽</p>
      <p><?= $lang['lamp_8_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 20×18 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['orange_color'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="308">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['lamp_8_name']) ?>">
        <input type="hidden" name="price" value="1700">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
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
