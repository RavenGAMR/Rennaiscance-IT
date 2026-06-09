<?php
session_start();
require_once __DIR__ . '/db.php';

function registerUser($username, $password, $role = 'user', $email = null){
    $pdo = getPDO();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username,email,password,role) VALUES (?, ?, ?, ?)");
    try {
        return $stmt->execute([$username, $email, $hash, $role]);
    } catch (PDOException $e) {
        // Duplicate username or email will trigger an exception (integrity constraint).
        // Return false so the caller can handle the error gracefully.
        return false;
    }
}

function loginUser($username, $password){
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function currentUser(){
    if(empty($_SESSION['user_id'])) return null;
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT id,username,email,role FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function requireLogin(){
    if(!currentUser()){
        $ret = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: /login-DB/login.php?return=' . urlencode($ret));
        exit;
    }
}

function requireAdmin(){
    $u = currentUser();
    if(!$u || $u['role'] !== 'admin'){
        header('Location: /Index.php');
        exit;
    }
}

function userHasPurchase($user_id, $product){
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ? AND product = ?");
    $stmt->execute([$user_id, $product]);
    return $stmt->fetchColumn() > 0;
}
