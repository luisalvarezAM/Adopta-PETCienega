<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['id_usuario'];
if ($varsesion == null || $varsesion = '') {
    header("location: /adoptapetcienega/");
    die();
}
$id_usuario = $_SESSION['id_usuario'];

require('../assets/conexionBD.php'); //conexion a la base de datos
$conexion = obtenerConexion();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta</title>
    <link rel="icon" type="image/x-icon"
        href="../assets/adoptapetcienega.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <!---Barrra de opciones -->
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <strong>Adopta PETCienega</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="/" class="nav-link ">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="MiCuenta.php" class="nav-link active">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="PublicarMascota.php" class="nav-link">Publicar mascota</a>
                        </li>
                        <li class="nav-item">
                            <a href="MisPublicaciones.php" class="nav-link">Mis publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="../cerrar_sesion.php" class="nav-link">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </header>
</body>

</html>