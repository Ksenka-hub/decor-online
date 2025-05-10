<?php
session_start();

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";
$lang = is_array($lang) ? $lang : [];

$themeClass = $_SESSION['theme'] ?? 'light';
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if (!empty($email) && !empty($password)) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['email'] = $user['email'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['avatar'] = $user['avatar'] ?? 'img/account/account_logo.png';
      header('Location: account.php');
      exit();
    } else {
      $error = "❌ Неверный email или пароль.";
    }
  } else {
    $error = "❗ Пожалуйста, заполните все поля.";
  }
}
?>

<!DOCTYPE html>
<html lang="<?= $langCode ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DÉCOR | <?= $lang['checkout'] ?></title>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="catalog-page <?= $themeClass ?>-theme">
  <div class="page-wrapper-orders">

    <header>
      <div class="logo-block" id="logoLamp" onclick="highlightBrand()">
        <img src="img/index/logo.svg" alt="Logo" />
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
          <span class="welcome-text">Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
          <a href="logout.php" class="login-link">Выйти</a>
        <?php else: ?>
          <a href="auth.php" class="login-link"><?= $lang['login_register'] ?></a>
        <?php endif; ?>
      </div>
    </header>

    <main>
      <div class="auth-background">
        <div class="auth-container">

          <div class="settings-panel">
            <div class="settings-item">
              <button class="settings-main-btn" onclick="toggleDropdown('langDropdown')">
                🌍 <span><?= $lang['interface_language'] ?? 'Язык интерфейса' ?></span>
              </button>
              <div id="langDropdown" class="settings-dropdown">
                <button type="button" onclick="setLanguage('ru')">🇷🇺 Русский</button>
                <button type="button" onclick="setLanguage('en')">🇬🇧 English</button>
                <button type="button" onclick="setLanguage('zh')">🇨🇳 中文</button>
              </div>
            </div>

            <div class="settings-item">
              <button class="settings-main-btn" onclick="toggleDropdown('themeDropdown')">
                🎨 <span><?= $lang['site_theme'] ?? 'Тема сайта' ?></span>
              </button>
              <div id="themeDropdown" class="settings-dropdown">
                <button type="button" onclick="setTheme('light')">☀️ <?= $lang['theme_light'] ?? 'Светлая' ?></button>
                <button type="button" onclick="setTheme('dark')">🌙 <?= $lang['theme_dark'] ?? 'Тёмная' ?></button>
                <button type="button" onclick="setTheme('auto')">🧘🏼‍♂️ <?= $lang['theme_auto'] ?? 'Авто' ?></button>
              </div>
            </div>
          </div>

          <div class="auth-header">
            <h1><?= $lang['welcome_title'] ?? 'Добро пожаловать!' ?></h1>
          </div>

          <div class="auth-tabs">
            <button class="tab-btn active" id="loginTab"><?= $lang['login_tab'] ?? 'Войти' ?></button>
            <button class="tab-btn" id="registerTab"><?= $lang['register_tab'] ?? 'Регистрация' ?></button>
          </div>

          <div class="auth-card">

            <div class="tab-content" id="loginForm">
              <form method="POST">
                <input type="email" name="email" placeholder="<?= $lang['placeholder_email'] ?? 'Твой email' ?>" required />
                <input type="password" name="password" placeholder="<?= $lang['placeholder_password'] ?? 'Твой пароль' ?>" required />
                <button type="submit" class="submit-btn" name="login"><?= $lang['login_btn'] ?? 'Войти в мир уюта' ?></button>
              </form>
              <a href="forgot-password.php" class="forgot-password-link"><?= $lang['forgot_password'] ?? 'Забыл пароль?' ?></a>
            </div>

            <div class="tab-content hidden" id="registerForm">
              <form action="send_register_code.php" method="POST">
                <input type="text" name="username" placeholder="<?= $lang['placeholder_name'] ?? 'Твоё имя' ?>" required />
                <input type="email" name="email" placeholder="<?= $lang['placeholder_email'] ?? 'Твой email' ?>" required />
                <input type="password" name="password" placeholder="<?= $lang['placeholder_create_password'] ?? 'Придумай пароль' ?>" required />
                <button type="submit" class="submit-btn"><?= $lang['register_btn'] ?? 'Создать аккаунт' ?></button>
              </form>
            </div>

            <?php if (isset($error)): ?>
              <p class="error-text"><?= $error ?></p>
            <?php endif; ?>
          </div>

          <div class="auth-footer">
            <p><?= $lang['auth_footer'] ?? '✨ Укрась свою жизнь вместе с нами' ?></p>
          </div>

        </div>
      </div>
    </main>

    <footer class="footer">
      <div class="footer-left">
        <a href="index.php" class="footer-link"><?= $lang['scroll_up'] ?? 'Back to top' ?></a>
      </div>
      <div class="footer-center wechat-footer">
        <img src="img/index/wechat_logo.png" alt="WeChat" />
        <span><?= $lang['wechat_message'] ?? 'Message us on WeChat' ?></span>
      </div>
      <div class="footer-right">
        © 2025 Decor. <?= $lang['rights'] ?? 'All rights reserved.' ?>
      </div>
    </footer>

    <form id="languageForm" method="POST" action="update_settings.php" style="display: none;">
      <input type="hidden" name="language" id="languageInput" />
    </form>

    <form id="themeForm" method="POST" action="update_settings.php" style="display: none;">
      <input type="hidden" name="theme" id="themeInput" />
    </form>

    <script src="script.js"></script>
  </div>
</body>
</html>