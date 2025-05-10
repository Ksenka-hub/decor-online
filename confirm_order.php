<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if (!isset($_SESSION['email'])) {
  header('Location: auth.php');
  exit();
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();

if (!$user) exit('Ошибка авторизации.');

$user_id = $user['id'];

$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();

if (!$cartItems) {
  header('Location: cart.php');
  exit();
}

foreach ($cartItems as $item) {
  $insert = $pdo->prepare("INSERT INTO orders (user_id, product_name, quantity, created_at) VALUES (?, ?, ?, NOW())");
  $insert->execute([$user_id, $item['product_name'], $item['quantity']]);
}

// Очистка корзины
$pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

header('Location: my-orders.php');
exit();