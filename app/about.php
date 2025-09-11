<?php
session_start();  

$merci= $_SESSION['merci'] ?? null;
unset($_SESSION['merci']); 
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calimera</title>
 <link rel="stylesheet" href="css/style.css" />
</head>
<body style="background-color: rgb(250, 243, 235);">
    <div class="nav">
        <div>Logo</div>
        <div class="links">
            <a href="rss.php">RSS</a> - A propos
        </div>
    </div>
    <div class="about">
    <div class="message"><p>Kalimera (καλημέρα) veut dire bonjour, bonne journée en grec, cela évoque pour moi 
        le soleil méditerranéen, les vacances, une bonne journée qui commence, le plein d'énergie pour commencer 
        la journée avec un grand sourire et des étoiles dans les yeux !</p>
        <p>L'idée de ce site est de proposer un petit message revigorant pour se sentir bien et que tout aille bien
            autour de nous.
        </p>
        <p>Vous pouvez contribuer aux pépites de soleil en proposant un message avec le formulaire ci-dessous :</p>
    </div>
    <?php if ($merci): ?>
        <p style="color: green; font-size: 1.5em;"><?php echo $merci; ?></p>
    <?php endif; ?>
    <div class="form-container">
        <form class="form" action="suggestion.php" method="post">
            <fieldset>
                <label>
                    <span>Votre phrase :</span><br>
                    <textarea name="phrase" id="phrase" ></textarea>
                </label>
                <br><br>
                <label>
                    <span>Votre courriel (si vous souhaitez que l'on reste en contact)</span><br />    
                    <input type="email" name="email" />
                </label>
                <br><br>
                <input type="submit" value="Envoyer" />
            </fieldset>
        </form>
    </div>

    <p class="mention">Votre courriel ne sera utilisé que pour communiquer au sujet de votre proposition.<br>
    L'ajout d'une proposition de phrase dépendra de critères comme l'esprit du site ou la préexitence d'un 
    message similaire ou non.</p>
    </div>
    
</body>
</html>