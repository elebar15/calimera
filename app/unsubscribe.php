<?php
require_once __DIR__ . '/db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Fetch the user by token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = :token");
    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $unsubscribe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($unsubscribe) {
        // Mark as unsubscribed
        $stmtUpdate = $pdo->prepare("UPDATE users SET unsubscribed = 1 WHERE token = :token");
        $stmtUpdate->bindValue(':token', $token, PDO::PARAM_STR);
        $stmtUpdate->execute();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calimera - désinscription</title>
 <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <div class="nav">
        <div>Logo</div>
        <div class="links">
            <a href="rss.php">RSS</a> - <a href="about.php">A propos</a>
        </div>
    </div>
    <div class="page">
    <H1 class="title">Calimera</H1>

    <div class="phrase">
        <?php
        if ($unsubscribe) {
            echo "Vous êtes correctement désinscrit de l'envoi de mail";
        }
        else {
            echo "Il y a eu un problème avec votre désinscription, <br>
            merci de contacter l'administrateur du site.";
        }
        ?>
   </div>

</body>
</html>