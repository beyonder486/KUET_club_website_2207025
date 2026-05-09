<?php
/**
 * Database Configuration — KUET Career Club
 * Include this file in any PHP page that needs DB access:
 *   require_once 'db_config.php';
 *
 * Then use the $pdo variable for queries.
 */

$db_host = 'localhost';
$db_name = 'kc';
$db_user = 'root';        // default XAMPP user
$db_pass = '';             // default XAMPP has no password

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
