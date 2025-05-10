<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if (!isset($_SESSION['user_id'])) {
    exit("Вход не выполнен");
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

echo "Корзина очищена";
?>