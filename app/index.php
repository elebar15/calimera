<?php
session_start();  

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']); 
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calimera</title>
 <link rel="stylesheet" href="app/style.css" />
</head>
<body>
    <div class="nav">
        <div>Logo</div>
        <div class="links">
            RSS - <a href="app/about.php">A propos</a>
        </div>
    </div>
    <div class="page">
    <H1 class="title">Calimera</H1>
    <div class="message">Recevez chaque jour <br/> un petit message rafraichissant <br />
        pour démarrer la journée <br/> avec un grand sourire !</div>
    <div class="form-container">
        <form class="form" action="app/add_email.php" method="post">
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
    
</body>
</html>