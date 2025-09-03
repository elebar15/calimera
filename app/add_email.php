<?php

session_start(); 

require_once __DIR__ .  '/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (strlen($sanitized_email) > 255) {
        echo "Email is too long!";
    } 
    elseif (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
        $sql = "INSERT INTO users (email) VALUES (:email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $sanitized_email]);

        $_SESSION['message'] = "Bienvenue, vous recevrez prochainement un petit rayon de soleil quotidien !";
    } else {
        $_SESSION['message'] = "Mince alors, ça coince quelque part ! <br /> Même joueur joue encore !";
    }

    header("Location: index.php");
    exit;
}
?>
