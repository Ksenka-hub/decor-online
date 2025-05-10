<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

// Если пользователь авторизован, получаем ID
$user_id = null;
if (isset($_SESSION['email'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $user_id = $result['id'];
    }
}

// 🔐 Смена пароля
if (isset($_POST['change_password']) && $user_id) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password'])) {
        $_SESSION['error'] = "❌ Неверный текущий пароль.";
    } elseif ($new !== $confirm) {
        $_SESSION['error'] = "⚠️ Новый пароль и подтверждение не совпадают.";
    } else {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$newHash, $user_id]);
        $_SESSION['success'] = "✅ Пароль успешно обновлён!";
    }
    header("Location: settings.php");
    exit();
}

// 🌍 Смена языка
if (isset($_POST['language'])) {
    $_SESSION['language'] = $_POST['language'];
    if ($user_id) {
        $stmt = $pdo->prepare("UPDATE users SET language = ? WHERE id = ?");
        $stmt->execute([$_POST['language'], $user_id]);
    }
    $_SESSION['success'] = "🌍 Язык интерфейса обновлён.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'settings.php'));
    exit();
}

// 🎨 Смена темы
if (isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    if ($user_id) {
        $stmt = $pdo->prepare("UPDATE users SET theme = ? WHERE id = ?");
        $stmt->execute([$_POST['theme'], $user_id]);
    }
    $_SESSION['success'] = "✨ Тема применена.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'settings.php'));
    exit();
}

// 📂 Общая кнопка
if (isset($_POST['save_settings'])) {
    $_SESSION['success'] = "📂 Настройки успешно сохранены.";
    header("Location: settings.php");
    exit();
}
?>