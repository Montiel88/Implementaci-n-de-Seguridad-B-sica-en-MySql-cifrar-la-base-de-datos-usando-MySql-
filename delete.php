<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;

if ($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

header('Location: dashboard.php');
exit;
?>