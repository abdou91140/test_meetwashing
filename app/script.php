<?php
$dsn = 'mysql:host=db;dbname=db_test_meetwatshing;charset=utf8mb4';
$user = 'username';
$password = 'password';

try {
    $pdo = new PDO($dsn, $user, $password);
    echo 'Database connection successful!';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
