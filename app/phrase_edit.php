<?php

session_start(); 

require_once __DIR__ . '/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int) $_POST['edit_id'];
    $edited_phrase = $_POST['edited_phrase'];

    $stmt = $pdo->prepare('UPDATE phrases SET phrase_text = :phrase_text WHERE id = :id');
    $stmt->bindParam(':phrase_text', $edited_phrase, PDO::PARAM_STR);
    $stmt->bindParam(':id', $edit_id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['success'] = "Phrase modifiée";
    header("Location: admin.php");
    exit;
}
?>

