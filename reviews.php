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
  <title>DÉCOR | <?= $lang['reviews'] ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="reviews-page <?= $themeClass ?>-theme">

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

  <section class="intro-section">
    <h1 class="page-title">✨ <?= $lang['reviews_title'] ?> ✨</h1>
    <p class="page-subtitle"><?= $lang['reviews_subtitle'] ?></p>
    <button class="add-review-btn"><?= $lang['leave_review_btn'] ?></button>
  </section>

  <section class="reviews-section">
    <?php
    $reviews = [
      ['img' => '1.png', 'text' => $lang['review_1'], 'name' => $lang['name_1']],
      ['img' => '2.png', 'text' => $lang['review_2'], 'name' => $lang['name_2']],
      ['img' => '3.png', 'text' => $lang['review_3'], 'name' => $lang['name_3']],
      ['img' => '4.png', 'text' => $lang['review_4'], 'name' => $lang['name_4']],
      ['img' => '5.png', 'text' => $lang['review_5'], 'name' => $lang['name_5']],
      ['img' => '6.png', 'text' => $lang['review_6'], 'name' => $lang['name_6']],
    ];

    foreach ($reviews as $r): ?>
      <div class="review-card">
        <img src="img/reviews/<?= $r['img'] ?>" alt="Client photo" class="review-photo">
        <div class="review-content">
          <p class="review-text"><?= $r['text'] ?></p>
          <p class="review-name"><?= $r['name'] ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <div class="modal-overlay" id="reviewFormModal">
    <div class="review-form-modal">
      <form action="https://formspree.io/f/xyzwzzvg" method="POST" id="submitReviewForm">
        <h3><?= $lang['modal_title'] ?></h3>

        <textarea name="message" maxlength="300" placeholder="<?= $lang['review_placeholder'] ?>" required></textarea>

        <div class="file-upload-wrapper">
          <input 
            type="hidden" 
            role="uploadcare-uploader" 
            name="photo"
            data-clearable 
            data-images-only 
            data-crop="free"
            data-tabs="file camera url" 
            data-lang="<?= $lang['ucare_lang'] ?>" 
            data-system-dialog 
          />
          <div class="file-name" id="fileName"><?= $lang['file_upload_note'] ?></div>
        </div>

        <input type="text" name="name" placeholder="<?= $lang['name_placeholder'] ?>" required />

        <div class="review-buttons">
          <button type="submit" class="submit-btn"><?= $lang['send_btn'] ?></button>
          <button type="button" class="close-review-form"><?= $lang['close_btn'] ?></button>
        </div>

        <p class="form-status hidden"></p>
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

  <script>
    UPLOADCARE_PUBLIC_KEY = '2bdc921815a8969778a5';
  </script>
  <script src="https://ucarecdn.com/libs/widget/3.x/uploadcare.full.min.js"></script>
  <script src="script.js"></script>
</body>
</html>