<?php
require_once __DIR__ . '/db_connect.php'; 

$websiteUserId = 1;  
$dayOfYear = date('z');  

$stmt = $pdo->prepare("SELECT * FROM phrases ORDER BY id LIMIT 1 OFFSET :offset");
$stmt->bindValue(':offset', $dayOfYear % 6, PDO::PARAM_INT);  // Adjust this for the number of phrases you have
$stmt->execute();
$phraseOfTheDay = $stmt->fetch(PDO::FETCH_ASSOC);

$dateToday = date('Y-m-d');  

$stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM alr_used WHERE id_user = :user_id AND id_phrase = :phrase_id AND date = :date");
$stmtCheck->bindValue(':user_id', $websiteUserId, PDO::PARAM_INT);
$stmtCheck->bindValue(':phrase_id', $phraseOfTheDay['id'], PDO::PARAM_INT);
$stmtCheck->bindValue(':date', $dateToday, PDO::PARAM_STR);
$stmtCheck->execute();
$phraseUsedToday = $stmtCheck->fetchColumn();

if ($phraseUsedToday == 0) {
    $stmtInsert = $pdo->prepare("INSERT INTO alr_used (id_user, id_phrase, date) VALUES (:user_id, :phrase_id, :date)");
    $stmtInsert->bindValue(':user_id', $websiteUserId, PDO::PARAM_INT);
    $stmtInsert->bindValue(':phrase_id', $phraseOfTheDay['id'], PDO::PARAM_INT);
    $stmtInsert->bindValue(':date', $dateToday, PDO::PARAM_STR);
    $stmtInsert->execute();
} 

?>
