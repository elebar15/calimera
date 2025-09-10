<?php
session_start();
require_once __DIR__ . '/db_connect.php';

    if (isset($_POST['clear_search']) && $_POST['clear_search'] == 'true') {
        unset($_SESSION['search_term']);
        unset($_SESSION['search_results']);
        exit; 
    }

    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $search = $_POST['search'];
        $_SESSION['search_term'] = $search; 

        $stmt = $pdo->prepare("SELECT * FROM phrases WHERE phrase_text LIKE :search");
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['search_results'] = $results;
    } else {
        unset($_SESSION['search_term']);
        unset($_SESSION['search_results']);
    }

header("Location: admin.php");
exit;
