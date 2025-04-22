<?php
require('assets/conexionBD.php');
$conexion = obtenerConexion();

$alert = ""; // Variable para almacenar el mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    $username = trim($_POST['username']);
    $nombre_completo = trim($_POST['nombre_completo']);
    $telefono = trim($_POST['telefono']);

    // Verificar si usuario o email ya existen
    $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ? OR correo = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Verificar exactamente qué existe
        $check_ind = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
        $check_ind->bind_param("s", $username);
        $check_ind->execute();
        $username_exists = $check_ind->get_result()->num_rows > 0;

        $check_ind = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $check_ind->bind_param("s", $email);
        $check_ind->execute();
        $email_exists = $check_ind->get_result()->num_rows > 0;

        if ($username_exists && $email_exists) {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        El nombre de usuario y correo electrónico ya están registrados
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        } elseif ($username_exists) {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        El nombre de usuario ya está registrado
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        El correo electrónico ya está registrado
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
    } else {
        // Registrar usuario
        $sql = $conexion->prepare("INSERT INTO usuarios (correo, contraseña, username, nombre_completo, telefono, fec_registro, tipo_usuario) 
                VALUES (?, ?, ?, ?, ?, NOW(), 1)");
        $sql->bind_param("sssss", $email, $password, $username, $nombre_completo, $telefono);

        if ($sql->execute()) {
            // REGISTRO EXITOSO - REDIRIGIR A INDEX.PHP
            header("Location: index.php");
            exit(); // Importante para evitar que continúe ejecutando el script
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        Error al registrar: ' . $conexion->error . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
        $sql->close();
    }
    $check->close();
}
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link rel="icon" type="image/x-icon" href="assets/adoptapetcienega.png" />
    <link href="css/signup.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-top: 50px;
        }

        .alert {
            margin-top: 20px;
        }

        .btn-login {
            padding: 0.75rem 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-9 mx-auto">
                <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                    <div class="card-img-left d-none d-md-flex">
                    </div>
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title text-center mb-5 fw-light fs-5">Registro</h5>

                        <?php echo $alert; ?>

                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required autofocus>
                                <label for="email">Correo electrónico</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Contraseña</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="myusername" required>
                                <label for="username">Nombre de usuario</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" placeholder="Nombre completo" required>
                                <label for="nombre_completo">Nombre completo</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10}" placeholder="Número de telefono" required>
                                <label for="telefono">Teléfono</label>
                            </div>

                            <div class="d-grid mb-2">
                                <button class="btn btn-lg btn-primary btn-login fw-bold text-uppercase" type="submit">ENVIAR</button>
                            </div>

                            <a class="d-block text-center mt-2 small" href="index.php">¿Ya tienes una cuenta? Inicia Sesión</a>

                            <hr class="my-4">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hacer que las alertas desaparezcan después de 5 segundos
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.remove();
            });
        }, 5000);
    </script>
</body>

</html>