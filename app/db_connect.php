<?php
require_once __DIR__ . '/../vendor/autoload.php';  

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dbPath = $_ENV['DB_PATH'] ?? null; 

$dsn = "sqlite:" . $dbPath;

try {
    $pdo = new PDO($dsn);
    $pdo->exec("PRAGMA foreign_keys = ON;");
    $pdo->exec("PRAGMA encoding = 'UTF-8';");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
