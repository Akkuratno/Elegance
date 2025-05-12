<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'elegance_auth');


function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    
    if ($conn->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => "Connection failed: " . $conn->connect_error
        ]));
    }
    
    
    $conn->set_charset("utf8mb4");
    
    return $conn;
}


function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function checkAuth() {
    return [
        'authenticated' => isset($_SESSION['user_id']),
        'user_name' => $_SESSION['user_name'] ?? null
    ];
}