<?php
session_start();  

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']); 

$dateToday = date('Y-m-d');
require_once __DIR__ . '/db_connect.php'; 
$stmt = $pdo->prepare("SELECT phrases.phrase_text 
                       FROM alr_used
                       JOIN phrases ON alr_used.id_phrase = phrases.id
                       WHERE alr_used.date = :date_today
                       ORDER BY alr_used.id DESC
                       LIMIT 1");
$stmt->bindValue(':date_today', $dateToday, PDO::PARAM_STR);
$stmt->execute();
$phraseOfTheDay = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calimera</title>
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

    <div class="phrase"><img src="/img/quote-left.webp" alt="quote-left" class="quotem">
        <?php
        if ($phraseOfTheDay) {
            echo htmlspecialchars($phraseOfTheDay['phrase_text']);
        }
        else {
            echo "Aujourd'hui, j'appelle un(e) ami(e) que j'ai perdu de vue depuis longtemps.";
        }
        ?>
    <img src="/img/quote-right.webp" alt="quote-right" class="quotem"></div>
    <br>
    <div class="message">Un petit message rafraichissant <br />
        pour démarrer la journée <br/> avec un grand sourire !</div>
    <div class="form-container">
        <form class="form" action="add_email.php" method="post">
            <fieldset>
                <label>
                    <span>Pour un message quotidien, <br />indiquez simplement votre courriel</span><br /><br />    
                    <input type="email" name="email" />
                </label>
                <input type="submit" value="123... soleil !" />
            </fieldset>
        </form>
    </div>
    <?php if ($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>
    <p class="mention">Votre courriel ne sera utilisé que pour cet envoi et vous pouvez vous désabonner simplement </p>
    </div>
    <script>
    var formLoadTime = new Date().getTime();
    </script>
</body>
</html>