<?php
require_once __DIR__ . '/db_connect.php';

$dayOfYear = date('z');  

$stmtTotalPhrases = $pdo->prepare("SELECT COUNT(*) FROM phrases");
$stmtTotalPhrases->execute();
$totalPhrases = $stmtTotalPhrases->fetchColumn();

$dateToday = date('Y-m-d');
$dayOfWeek = date('w'); 

$stmtUsers = $pdo->prepare("SELECT id, email, frequency, day_of_week FROM users WHERE frequency IN (0, 1, 2)");
$stmtUsers->execute();
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $UserId = $user['id'];
    $to = $user['email'];
    $frequency = $user['frequency'];
    $dayOfWeekUser = $user['day_of_week'];  

    $randomPhraseId = rand(1, $totalPhrases);
    $stmtPhrase = $pdo->prepare("SELECT id, phrase_text FROM phrases WHERE id = :phrase_id");
    $stmtPhrase->bindValue(':phrase_id', $randomPhraseId, PDO::PARAM_INT);
    $stmtPhrase->execute();
    $phrase = $stmtPhrase->fetch(PDO::FETCH_ASSOC);

    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM alr_used WHERE id_user = :user_id AND id_phrase = :phrase_id AND date = :date");
    $stmtCheck->bindValue(':user_id', $UserId, PDO::PARAM_INT);
    $stmtCheck->bindValue(':phrase_id', $phrase['id'], PDO::PARAM_INT);  
    $stmtCheck->bindValue(':date', $dateToday, PDO::PARAM_STR);
    $stmtCheck->execute();
    $phraseUsedToday = $stmtCheck->fetchColumn();

    // For cron user, only update used phrase
    if ($frequency == 0 && $phraseUsedToday == 0) {
        $stmtInsert = $pdo->prepare("INSERT INTO alr_used (id_user, id_phrase, date) VALUES (:user_id, :phrase_id, :date)");
        $stmtInsert->bindValue(':user_id', $UserId, PDO::PARAM_INT);  
        $stmtInsert->bindValue(':phrase_id', $phrase['id'], PDO::PARAM_INT); 
        $stmtInsert->bindValue(':date', $dateToday, PDO::PARAM_STR);
        $stmtInsert->execute();
    }

    if ($frequency == 1 && $phraseUsedToday == 0) {
        $stmtInsertQueue = $pdo->prepare("INSERT INTO email_queue (user_id, email, phrase_id, status) VALUES (:user_id, :email, :phrase_id, 'pending')");
        $stmtInsertQueue->bindValue(':user_id', $UserId, PDO::PARAM_INT);
        $stmtInsertQueue->bindValue(':email', $to, PDO::PARAM_STR);
        $stmtInsertQueue->bindValue(':phrase_id', $phrase['id'], PDO::PARAM_INT); 
        $stmtInsertQueue->execute();
    }

    if ($frequency == 2 && $dayOfWeek == $dayOfWeekUser && $phraseUsedToday == 0) {
        $stmtInsertQueue = $pdo->prepare("INSERT INTO email_queue (user_id, email, phrase_id, status) VALUES (:user_id, :email, :phrase_id, 'pending')");
        $stmtInsertQueue->bindValue(':user_id', $UserId, PDO::PARAM_INT);
        $stmtInsertQueue->bindValue(':email', $to, PDO::PARAM_STR);
        $stmtInsertQueue->bindValue(':phrase_id', $phrase['id'], PDO::PARAM_INT);  
        $stmtInsertQueue->execute();
    }
}
?>
