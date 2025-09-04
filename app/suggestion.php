<?php
session_start();  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host_smtp = $_ENV['SMTP'] ?? null; 
$mail_admin = $_ENV['SENDFROM'] ?? null; 
$pass = $_ENV['PASS_SENDFROM'] ?? null; 
$to = $_ENV['SENDTO'] ?? null; 


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $reply=$email;
    $sendfrom=$mail_admin;
    $phrase = htmlspecialchars($_POST['phrase']);

    if (!$email) {
        $email="un inconnu";
        $reply=$mail_admin;
        $sendfrom=$mail_admin;
    } 

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                           
    $mail->Host       = $host_smtp;                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = $sendfrom ;                    
    $mail->Password   = $pass;                              
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;                                   

    $mail->setFrom($sendfrom, 'Calimera');
    $mail->addAddress($to, 'Admin Calimera');    
    $mail->addReplyTo($reply, "<$reply>");

    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Calimera : nouvelle phrase';
    $mail->Body = "$phrase\n\nde la part de : $email";
    $mail->AltBody = "$phrase , de la part de : $email";

    $mail->send();

    $_SESSION['merci'] = "Merci pour votre proposition ! <br /> Nous l'ajouterons si elle convient";
  
    } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    header("Location: about.php");
    exit;
}    