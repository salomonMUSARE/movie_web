<?php
// config.php

// 1) Database connection settings
$host     = 'localhost';
$dbname   = 'movie_app';
$username = 'slm';
$password = 'Business@me1';
$charset  = 'utf8mb4';

// 2) Data Source Name
$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

// 3) PDO options for better error handling & security
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // use native prepares if possible
];

try {
    // 4) Create the PDO instance
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // 5) If connection fails, show error and stop
    echo 'Database Connection Failed: ' . htmlspecialchars($e->getMessage());
    exit;
}
