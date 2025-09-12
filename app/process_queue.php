<?php
require_once __DIR__ . '/db_connect.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host_smtp = $_ENV['SMTP'] ?? null; 
$sendfrom = $_ENV['SENDFROM'] ?? null; 
$pass = $_ENV['PASS_SENDFROM'] ?? null; 

if (!$host_smtp || !$sendfrom || !$pass) {
    die("Il manque les informations du serveur SMTP dans le fichier .env.");
}

$stmt = $pdo->prepare("SELECT * FROM email_queue WHERE status = 'pending'");
$stmt->execute();
$emailJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($emailJobs as $emailJob) {
    $jobId = $emailJob['id'];
    $userId = $emailJob['user_id'];
    $email = $emailJob['email'];
    $phraseId = $emailJob['phrase_id']; 

    // No mail for the cron user (id:1)
    if ($userId == 1) {
        continue;  
    }

    $stmtPhrase = $pdo->prepare("SELECT id, phrase_text FROM phrases WHERE id = :phrase_id");
    $stmtPhrase->bindValue(':phrase_id', $phraseId, PDO::PARAM_INT);
    $stmtPhrase->execute();
    $phrase = $stmtPhrase->fetch(PDO::FETCH_ASSOC);

    if (!$phrase) {
        echo "No phrase found for ID {$phraseId}. Skipping this job.\n";
        continue;
    }

    $stmtSubject = $pdo->prepare("
        SELECT themes.mail_subject
        FROM phrases
        JOIN phrase_theme ON phrases.id = phrase_theme.phrase_id
        JOIN themes ON phrase_theme.theme_id = themes.id
        WHERE phrases.id = :phrase_id");
    $stmtSubject->bindValue(':phrase_id', $phraseId, PDO::PARAM_INT);
    $stmtSubject->execute();
    $mailSubject = $stmtSubject->fetch(PDO::FETCH_ASSOC);

    if (!$mailSubject) {
        echo "No mail subject found for phrase ID {$phraseId}. Skipping this job.\n";
        continue;
    }

    $pdo->beginTransaction();

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $host_smtp;
        $mail->SMTPAuth   = true;
        $mail->Username   = $sendfrom;
        $mail->Password   = $pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($sendfrom, 'Calimera');
        $mail->addAddress($email, 'vous');
        $mail->addReplyTo($sendfrom, 'Calimera');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $mailSubject['mail_subject'];
        $mail->Body = "Bonne journée, <br><br> {$phrase['phrase_text']}
            <br><br>A bientôt !";
        $mail->AltBody = "Bonne journée,\n\n {$phrase['phrase_text']}
            \n\nA bientôt !";

        $mail->send();

        $dateToday = date('Y-m-d');
        $stmtInsertUsed = $pdo->prepare("INSERT INTO alr_used (id_user, id_phrase, date) VALUES (:user_id, :phrase_id, :date)");
        $stmtInsertUsed->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmtInsertUsed->bindValue(':phrase_id', $phraseId, PDO::PARAM_INT);
        $stmtInsertUsed->bindValue(':date', $dateToday, PDO::PARAM_STR);
        $stmtInsertUsed->execute();

        $stmtUpdate = $pdo->prepare("UPDATE email_queue SET status = 'completed', date_processed = datetime('now') WHERE id = :id");
        $stmtUpdate->bindValue(':id', $jobId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();  

        $stmtUpdate = $pdo->prepare("UPDATE email_queue SET status = 'failed', date_processed = datetime('now') WHERE id = :id");
        $stmtUpdate->bindValue(':id', $jobId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        echo "Le mail pour {$email} n'a pas pu être envoyé. Erreur : {$e->getMessage()}\n";
    }
}
?>
