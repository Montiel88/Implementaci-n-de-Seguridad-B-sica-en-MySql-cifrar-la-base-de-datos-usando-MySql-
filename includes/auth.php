<?php
session_start();
require_once __DIR__ . '/../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

function login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

function register($username, $email, $password) {
    global $pdo;
    
    // Verificar si ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return false; // Usuario o email ya existe
    }
    
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hashed]);
}

function logout() {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>