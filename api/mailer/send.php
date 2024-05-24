<?php

include ("../../secrets.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../include/PHPMailer/src/Exception.php';
require '../../include/PHPMailer/src/PHPMailer.php';
require '../../include/PHPMailer/src/SMTP.php';

function send_mail($username, $email, $code) {
   global $smtp_host;
   global $smtp_auth;
   global $smtp_username;
   global $smtp_password;
   global $smtp_security;
   global $smtp_port;

   $mail = new PHPMailer(True);

   $mail->isSMTP();
   $mail->CharSet = 'UTF-8';
   $mail->Host = $smtp_host;
   $mail->SMTPAuth = $smtp_auth;
   $mail->Username = $smtp_username;
   $mail->Password = $smtp_password;
   $mail->SMTPSecure = $smtp_security;
   $mail->Port = $smtp_port;

   $mail->setFrom('r-place@noreply.firminlaunay.me', 'r/place');
   $mail->addAddress($email, $username);


   $mail->isHTML(false);

   $mail->Subject = 'Votre compte r/place';
   $mail->Body = "r/place\n=======\n\nBonjour $username,\nVotre code de vérification est : $code.\nCe code est valable 15 minutes.\nÀ très bientôt :)";

   return $mail->send();
}

?>
