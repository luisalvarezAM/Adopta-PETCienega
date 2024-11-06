<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['id_usuario'];
if ($varsesion == null || $varsesion = '') {
    header("location: /adoptapetcienega/");
    die();
}
require('../assets/conexionBD.php'); //conexion a la base de datos
$conexion = obtenerConexion();
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre_completo,correo,telefono,tu.descripcion 
from usuarios inner join tipos_usuarios as tu on 
usuarios.tipo_usuario=tu.id_tipo where id_usuario=$id_usuario;";
$result = $conexion->query($sql);
$fila = $result->fetch_assoc();

$nombre_completo = $fila['nombre_completo'];
$correo = $fila['correo'];
$telefono = $fila['telefono'];
$descripcion = $fila['descripcion'];

$conexion->close();
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
                <!---Barra de opciones a navegar---->
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link ">Mascotas</a>
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
                <!---->

            </div>
        </div>
    </header>
    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg rounded">
                    <div class="card-header text-center bg-primary text-white">
                        <h2 class="mb-0">Perfil del Usuario</h2>
                    </div>
                    <div class="card-body p-4">
                        <!-- Foto de perfil (opcional) -->
                        <div class="text-center mb-4">
                            <img src="ruta_a_la_imagen_de_perfil.jpg" alt="Foto de Perfil" class="rounded-circle border border-3 border-primary" style="width: 120px; height: 120px;">
                        </div>

                        <!-- Información del usuario -->
                        <form>
                            <div class="mb-3">
                                <label for="nombre_completo" class="form-label text-muted">Nombre</label>
                                <input type="text" id="nombre_completo" class="form-control" value="<?php echo $nombre_completo; ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label text-muted">Correo</label>
                                <input type="email" id="correo" class="form-control" value="<?php echo $correo; ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label text-muted">Teléfono</label>
                                <input type="tel" id="telefono" class="form-control" value="<?php echo $telefono; ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_usuario" class="form-label text-muted">Tipo de Usuario</label>
                                <input type="text" id="tipo_usuario" class="form-control" value="<?php echo $descripcion; ?>" readonly>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-light">
                        <a href="" class="btn btn-primary">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>