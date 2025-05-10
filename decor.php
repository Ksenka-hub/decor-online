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
  <title>DÉCOR | <?= $lang['mini_decor'] ?></title>
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
  <h1 class="section-title">🛋️ <?= $lang['decor_title'] ?> </h1>
  <div class="product-grid">

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/decor/1.png" alt="<?= $lang['decor_1_name'] ?>">
      <h3><?= $lang['decor_1_name'] ?></h3>
      <p class="price">1100 ₽</p>
      <p><?= $lang['decor_1_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 25×14 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['wood_glass_silicone'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['beige_pink'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="601">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_1_name']) ?>">
        <input type="hidden" name="price" value="1100">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/2.png" alt="<?= $lang['decor_2_name'] ?>">
      <h3><?= $lang['decor_2_name'] ?></h3>
      <p class="price">1700 ₽</p>
      <p><?= $lang['decor_2_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 15×13 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['pink_color'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="602">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_2_name']) ?>">
        <input type="hidden" name="price" value="1700">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/decor/3.png" alt="<?= $lang['decor_3_name'] ?>">
      <h3><?= $lang['decor_3_name'] ?></h3>
      <p class="price">950 ₽</p>
      <p><?= $lang['decor_3_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 18×18 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['resin_stone'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['white_color'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="603">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_3_name']) ?>">
        <input type="hidden" name="price" value="950">
        <input type="hidden" name="quantity" value="1">
      <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/4.png" alt="<?= $lang['decor_4_name'] ?>">
      <h3><?= $lang['decor_4_name'] ?></h3>
      <p class="price">1200 ₽</p>
      <p><?= $lang['decor_4_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 20×18 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['cream_color'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="604">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_4_name']) ?>">
        <input type="hidden" name="price" value="1200">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/decor/5.png" alt="<?= $lang['decor_5_name'] ?>">
      <h3><?= $lang['decor_5_name'] ?></h3>
      <p class="price">850 ₽</p>
      <p><?= $lang['decor_5_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 14×14 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['fruit_colors'] ?></li>
      </ul>
  <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="605">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_5_name']) ?>">
        <input type="hidden" name="price" value="850">
        <input type="hidden" name="quantity" value="1">
     <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/6.png" alt="<?= $lang['decor_6_name'] ?>">
      <h3><?= $lang['decor_6_name'] ?></h3>
      <p class="price">1400 ₽</p>
      <p><?= $lang['decor_6_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 17×10 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['matte_ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['white_color'] ?></li>
      </ul>
 <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="606">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_6_name']) ?>">
        <input type="hidden" name="price" value="1400">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/decor/7.png" alt="<?= $lang['decor_7_name'] ?>">
      <h3><?= $lang['decor_7_name'] ?></h3>
      <p class="price">1400 ₽</p>
      <p><?= $lang['decor_7_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 24×18 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['resin'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['cream_pink'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="607">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_7_name']) ?>">
        <input type="hidden" name="price" value="1400">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/8.png" alt="<?= $lang['decor_8_name'] ?>">
      <h3><?= $lang['decor_8_name'] ?></h3>
      <p class="price">890 ₽</p>
      <p><?= $lang['decor_8_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 20×12×9 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['purple_yellow'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="608">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_8_name']) ?>">
        <input type="hidden" name="price" value="890">
        <input type="hidden" name="quantity" value="1">
      <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/9.png" alt="<?= $lang['decor_9_name'] ?>">
      <h3><?= $lang['decor_9_name'] ?></h3>
      <p class="price">1500 ₽</p>
      <p><?= $lang['decor_9_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 15×12 см и 10×8 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['berry_color'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="609">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_9_name']) ?>">
        <input type="hidden" name="price" value="1500">
        <input type="hidden" name="quantity" value="1">
     <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card new">
      <div class="badge">new</div>
      <img src="img/decor/10.png" alt="<?= $lang['decor_10_name'] ?>">
      <h3><?= $lang['decor_10_name'] ?></h3>
      <p class="price">1300 ₽</p>
      <p><?= $lang['decor_10_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 20×12 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['pink_color'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="610">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_10_name']) ?>">
        <input type="hidden" name="price" value="1300">
        <input type="hidden" name="quantity" value="1">
     <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/11.png" alt="<?= $lang['decor_11_name'] ?>">
      <h3><?= $lang['decor_11_name'] ?></h3>
      <p class="price">990 ₽</p>
      <p><?= $lang['decor_11_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 18×10×9 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['plastic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['pink_yellow'] ?></li>
      </ul>
<form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="611">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_11_name']) ?>">
        <input type="hidden" name="price" value="990">
        <input type="hidden" name="quantity" value="1">
    <button type="submit" class="add-to-cart-btn"><?= $lang['add_to_cart'] ?></button>
    </form>
      <button class="preview-btn"><?= $lang['preview_btn'] ?></button>
    </div>

    <div class="product-card">
      <img src="img/decor/12.png" alt="<?= $lang['decor_12_name'] ?>">
      <h3><?= $lang['decor_12_name'] ?></h3>
      <p class="price">1700 ₽</p>
      <p><?= $lang['decor_12_desc'] ?></p>
      <ul class="characteristics">
        <li><?= $lang['size'] ?>: 18×10 см</li>
        <li><?= $lang['material'] ?>: <?= $lang['glass_ceramic'] ?></li>
        <li><?= $lang['color'] ?>: <?= $lang['milky_gold'] ?></li>
      </ul>
      <form class="add-to-cart-form" method="POST" action="add_to_cart.php" onsubmit="return checkLoginBeforeAdd();">
        <input type="hidden" name="product_id" value="612">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($lang['decor_12_name']) ?>">
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




