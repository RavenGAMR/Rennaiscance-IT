<?php
function getPDO(){
    static $pdo = null;
    if ($pdo) return $pdo;
    $host = '127.0.0.1';
    $db   = 'rennaiscance_it';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try{
        $pdo = new PDO($dsn, $user, $pass, $opt);
        return $pdo;
    }catch(PDOException $e){
        die('DB connection failed: '.$e->getMessage());
    }
}
