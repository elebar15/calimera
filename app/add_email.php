<?php

session_start(); 

require_once __DIR__ .  '/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formSubmitTime = time();
    $timeElapsed = $formSubmitTime - $_POST['form_load_time'];

    if ($timeElapsed < 5) {
        die("Thank you");
    } else {

    $email = $_POST['email'] ?? '';

    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (strlen($sanitized_email) > 255) {
        echo "Email is too long!";
    } 

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$sanitized_email]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['message'] = "Ce courriel recoit déjà les messages de Calimera.";
        header("Location: index.php");
        exit;
        }

    elseif (filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO users (email, token) VALUES (:email, :token)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $sanitized_email, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['message'] = "Bienvenue, vous recevrez prochainement un petit rayon de soleil quotidien !";
    } else {
        $_SESSION['message'] = "Mince alors, ça coince quelque part ! <br /> Même joueur joue encore !";
    }

    header("Location: index.php");
    exit;
    }
}
?>
