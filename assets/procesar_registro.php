<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de perfil</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>

<body>
    <?php
    require('conexionBD.php'); //conexion a la base de datos
    $conexion = obtenerConexion(); //Conexion a la base de datos

    require  'PHPMailer/PHPMailer.php'; //Archivos para mandar correos
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $direccion = $_POST['direccion'];
        date_default_timezone_set('America/Mexico_City');
        $fec_registro = date("Y-m-d H:i:s");
        $tipo_usuario = 1;
        $sql = "INSERT INTO  usuarios (usuario,contraseña,correo,direccion,fec_registro,tipo_usuario)
        VALUES ('$username','$password','$email','$direccion','$fec_registro','$tipo_usuario')";

        if ($conexion->query($sql) === TRUE) {
            //Enviar correo de bienvenida//
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'adoptapetcienega@gmail.com';
            $mail->Password = 'ajclxsaikenaqzie';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('adoptapetcienega@gmail.com', 'Adopta PETCienega');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a la plataforma';
            $mail->Body = '<b>Muchas gracias por registrarte en Adopta PETCienega</b>
            Esperemos que juntos podamos ayudar y dar nuevos hogares a todos los animales.';

            session_start();
            
            if ($mail->send()) {
            '<div class="alert alert-success" role="alert">¡Registro exitoso! Se ha enviado un correo para activar tu cuenta</div>';
            } else {
            '<div class="alert alert-danger" role="alert">Error al enviar el correo g</div>' . $mail->ErrorInfo;
            }
            header('Location: /adoptapetcienega/');
        }
    }
    $conexion->close();
    ?>
    <script type="text/javascrip" src="../js/jquery-3.7.1.min.js"></script>
    <script type="text/javascrip" src="../js/bootstrap.js.map"></script>
</body>
</body>

</html>