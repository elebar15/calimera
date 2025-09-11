<?php
require_once __DIR__ . '/db_connect.php'; 

$dateToday = date('Y-m-d');

$stmt = $pdo->prepare("SELECT phrases.phrase_text 
                       FROM alr_used
                       JOIN phrases ON alr_used.id_phrase = phrases.id
                       WHERE alr_used.date = :date_today
                       ORDER BY alr_used.id DESC
                       LIMIT 1");
$stmt->bindValue(':date_today', $dateToday, PDO::PARAM_STR);
$stmt->execute();
$phraseOfTheDay = $stmt->fetch(PDO::FETCH_ASSOC);


$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$rss = $dom->createElement('rss');
$rss->setAttribute('version', '2.0');

$channel = $dom->createElement('channel');

$title = $dom->createElement('title', 'Calimera');
$channel->appendChild($title);
$link = $dom->createElement('link', 'https://calimera.fr');
$channel->appendChild($link);
$description = $dom->createElement('description', 'Une phrase quotidienne pour bien débuter la journée');
$channel->appendChild($description);

if ($phraseOfTheDay) {
    $item = $dom->createElement('item');
    
    $itemTitle = $dom->createElement('title', htmlspecialchars($phraseOfTheDay['phrase_text']));
    $item->appendChild($itemTitle);
    
    $channel->appendChild($item);
}

$rss->appendChild($channel);

$dom->appendChild($rss);

header('Content-Type: application/xml');

echo $dom->saveXML();
?>