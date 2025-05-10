<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

$langCode = $_SESSION['language'] ?? 'ru';
$langFile = __DIR__ . "/lang/{$langCode}.php";
$lang = file_exists($langFile) ? include $langFile : include __DIR__ . "/lang/ru.php";

if (!isset($_SESSION['email'])) {
    header('Location: auth.php');
    exit();
}

$product_id = $_POST['product_id'] ?? null;
$product_name = $_POST['product_name'] ?? 'Товар';
$price = $_POST['price'] ?? null;
$quantity = (int)($_POST['quantity'] ?? 1);

if ($product_id !== null && $price !== null && $quantity > 0) {
    
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$_SESSION['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['id'];

        $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $update = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ?');
            $update->execute([$newQty, $existing['id']]);
        } else {
            $insert = $pdo->prepare('INSERT INTO cart (user_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)');
            $insert->execute([$user_id, $product_id, $product_name, $price, $quantity]);
        }
    }
}

header('Location: catalog.php');
exit();

