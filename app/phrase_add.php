<?php
session_start();

require_once __DIR__ . '/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phrase_text = trim($_POST['phrase_text']);
    $selected_themes = $_POST['themes'] ?? [];

    if (!empty($phrase_text) && !empty($selected_themes)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO phrases (phrase_text) VALUES (:phrase_text)");
            $stmt->bindParam(':phrase_text', $phrase_text, PDO::PARAM_STR);
            $stmt->execute();
            $phrase_id = $pdo->lastInsertId();

            foreach ($selected_themes as $theme_id) {
                $stmt = $pdo->prepare("INSERT INTO phrase_theme (phrase_id, theme_id) VALUES (:phrase_id, :theme_id)");
                $stmt->bindParam(':phrase_id', $phrase_id, PDO::PARAM_INT);
                $stmt->bindParam(':theme_id', $theme_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $pdo->commit();
            $_SESSION['success'] = "Phrase ajoutée avec succès.";
            header("Location: admin.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de l'ajout de la phrase: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
    }
}
?>
