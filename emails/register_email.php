<?php
require  'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$email=$_POST[''];

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'adoptapetcienega@gmail.com';
$mail->Password = 'ajclxsaikenaqzie';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('adoptapetcienega@gmail.com', 'Adopta PETCienega');
$mail->addAddress($email);

$mail->isHTML(true);
$mail->Subject = 'Adopta PETCienega';
$mail->Body = '<b>Muchas gracias por mostrar interés es nuestra página</b>';
$mail->AltBody = 'Si deseas formar parte de la comunidad, ingresa al
        siguiente link para crear una cuenta o iniciar sesión: <a href="localhost/adoptapetcienega/login_register.php">Crear cuenta</a>';
$mail->send();
