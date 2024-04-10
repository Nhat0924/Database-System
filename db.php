<?php

$host = 'localhost'; // //sysmysql8.auburn.edu; 
$data = 'publications'; //nhn0001db
$user = 'root'; //nhn0001
$pass = 'mysql'; //B*n***@****
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
$opts =
[
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false,
];

function connect() {
    global $attr, $user, $pass, $opts;
    try {
        $conn = new PDO($attr, $user, $pass, $opts);
        return $conn;
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function sanitize_string($var) {
    $var = strip_tags($var);
    $var = htmlentities($var);

    return $var;
}

function sanitize_mysql($pdo, $var) {
    $var = $pdo->quote($var);
    $var = sanitize_string($var);

    return $var;
}