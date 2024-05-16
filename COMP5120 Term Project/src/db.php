<?php
//DB login information
$host = 'sysmysql8.auburn.edu'; // //sysmysql8.auburn.edu; 
$data = 'nhn0001db'; //nhn0001db
$user = 'nhn0001'; //nhn0001
$pass = '7b5f5a594d4f7000000902'; //secret
$chrs = 'utf8mb4';
$attr = "mysql:host=$host;dbname=$data;charset=$chrs";
// $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]; //PHP 5.3 again

// connect() - return a connection object to connect PHP with MySQL for querying
function connect() {
    global $attr, $user, $pass, $opts;
    try {
        $key = '904186020';
        $decrypt_pass = xor_decrypt($key, pack('H*', $pass));
        $conn = new PDO($attr, $user, $decrypt_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //setAttribute instead because PHP 5.3
        return $conn;
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

//sanitize_string() - return a sanitized SQL string statement to be query in MySQL. For security reasons and PHP 5.3 compatibility
function sanitize_string($var) {
    if (get_magic_quotes_gpc()) $var = stripslashes($var); //Doesn't exist in PHP >=7 but needed because PHP 5.3
    $var = strip_tags($var);
    return $var;
}

//xor_decrypt() - decrypt my password because I'm not comfortable with showing passwords in plain text and also security reasons
function xor_decrypt($key, $value) {
    $decrypt = '';
    for ($i = 0; $i < strlen($value); $i++) {
        $decrypt .= $value[$i] ^ $key[$i % strlen($key)];
    }
    return $decrypt;
}