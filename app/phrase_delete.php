<?php

session_start(); 

require_once __DIR__ . '/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int) $_POST['delete_id'];

    $stmt = $pdo->prepare("DELETE FROM phrases WHERE id = :id");
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success'] = "Phrase supprimée";
    header("Location: admin.php");
    exit;
}
?>