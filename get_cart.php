<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($items);
?>