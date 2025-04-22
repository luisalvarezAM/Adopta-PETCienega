<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['id_usuario'];
if ($varsesion == null || $varsesion = '') {
    header("location: /adoptapetcienega/");
    die();
}

$modo_edicion = isset($_GET['editar']) && $_GET['editar'] == '1';

require('../assets/conexionBD.php');
$conexion = obtenerConexion();
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre_completo, correo, telefono, tu.descripcion, img_perfil 
        FROM usuarios 
        INNER JOIN tipos_usuarios as tu ON usuarios.tipo_usuario=tu.id_tipo 
        WHERE id_usuario=$id_usuario";
$result = $conexion->query($sql);
$fila = $result->fetch_assoc();

$ruta_foto = $fila['img_perfil'];
$nombre_completo = $fila['nombre_completo'];
$correo = $fila['correo'];
$telefono = $fila['telefono'];
$descripcion = $fila['descripcion'];

$conexion->close();

// Mostrar mensajes
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    
</head>

<body>
    <header>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <strong>Adopta PETCienega</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link active">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">Mis publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="../cerrar_sesion.php" class="nav-link">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>