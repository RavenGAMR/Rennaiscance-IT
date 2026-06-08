<?php
session_start();
require_once __DIR__ . '/db.php';

function registerUser($username, $password, $role = 'user'){
    $pdo = getPDO();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username,password,role) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $hash, $role]);
}

function loginUser($username, $password){
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
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
    $stmt = $pdo->prepare("SELECT id,username,role FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function requireLogin(){
    if(!currentUser()){
        header('Location: login.php');
        exit;
    }
}

function requireAdmin(){
    $u = currentUser();
    if(!$u || $u['role'] !== 'admin'){
        header('Location: Index.php');
        exit;
    }
}

function userHasPurchase($user_id, $product){
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM purchases WHERE user_id = ? AND product = ?");
    $stmt->execute([$user_id, $product]);
    return $stmt->fetchColumn() > 0;
}
