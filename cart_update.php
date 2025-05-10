<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=decor_site;charset=utf8', 'root', 'root');

// Проверка авторизации
if (!isset($_SESSION['email'])) {
    header('Location: auth.php');
    exit();
}

// Получаем ID пользователя
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && isset($_POST['item_id'], $_POST['action'])) {
    $itemId = (int)$_POST['item_id'];
    $action = $_POST['action'];

    if ($action === 'increase') {
        $stmt = $pdo->prepare('UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?');
        $stmt->execute([$itemId, $user['id']]);
    } elseif ($action === 'decrease') {
        // Получаем текущее количество
        $stmt = $pdo->prepare('SELECT quantity FROM cart WHERE id = ? AND user_id = ?');
        $stmt->execute([$itemId, $user['id']]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if ($item['quantity'] > 1) {
                $stmt = $pdo->prepare('UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND user_id = ?');
                $stmt->execute([$itemId, $user['id']]);
            } else {
                // Если после уменьшения будет 0 — удаляем товар
                $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
                $stmt->execute([$itemId, $user['id']]);
            }
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
        $stmt->execute([$itemId, $user['id']]);
    }
}

header('Location: cart.php');
exit();