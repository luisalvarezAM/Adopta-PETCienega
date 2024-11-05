<?php
require('assets/conexionBD.php'); //conexion a la base de datos
$conexion = obtenerConexion(); //Conexion a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $username = $_POST['username'];
    $nombre_completo = $_POST['nombre_completo'];
    $telefono = $_POST['telefono'];
    date_default_timezone_set('America/Mexico_City');
    $fec_registro = date("Y-m-d H:i:s");
    $tipo_usuario = 1;
    $sql = "INSERT INTO  usuarios (correo,contraseña,username,nombre_completo,
    telefono,fec_registro,tipo_usuario)
    VALUES ('$email','$password','$username','$nombre_completo','$telefono','$fec_registro','$tipo_usuario')";

    if ($conexion->query($sql) === TRUE) {
        echo '<script>
            alert("¡Usuario registrado exitosamente!");
            window.location.href = "/adoptapetcienega/"; // Redirigir a la página principal
          </script>';
    } else {
        echo '<script>
        alert("¡Error al crear tu cuenta!");
        window.location.href = "/adoptapetcienega/"; // Redirigir a la página principal
      </script>';
    }
}
$conexion->close();
?>
<script type="text/javascrip" src="/js/signup.js"></script>
<script type="text/javascrip" src="../js/jquery-3.7.1.min.js"></script>
<script type="text/javascrip" src="../js/bootstrap.js.map"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link rel="icon" type="image/x-icon"
        href="assets/adoptapetcienega.png" />
    <link href="css/signup.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-9 mx-auto">
                    <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                        <div class="card-img-left d-none d-md-flex">
                        </div>
                        <div class="card-body p-4 p-sm-5">
                            <h5 class="card-title text-center mb-5 fw-light fs-5">Registro</h5>
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required autofocus>
                                    <label for="email">Correo electrónico</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    <label for="password">Contraseña</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="myusername" required autofocus>
                                    <label for="username">Nombre de usuario</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" placeholder="Nombre completo">
                                    <label for="nombre_completo">Nombre completo</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10}" placeholder="Número de telefono">
                                    <label for="telefono">Telefono</label>
                                </div>

                                <div class="d-grid mb-2">
                                    <input class="btn btn-lg btn-primary btn-login fw-bold text-uppercase" type="submit"></button>
                                </div>

                                <a class="d-block text-center mt-2 small" href="index.php">¿Ya tienes una cuenta? Inicia Sesión</a>

                                <hr class="my-4">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="js/scripts.js"></script>
    <script type="text/javascrip" src="/js/jquery-3.7.1.min.js"></script>
    <script type="text/javascrip" src="/js/bootstrap.js.map"></script>
    <script type="text/javascrip" src="/js/signup.js"></script>
</body>

</html>