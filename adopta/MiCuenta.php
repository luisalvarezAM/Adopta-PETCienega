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
    <title>Mi Cuenta</title>
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .readonly-field {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
    </style>
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
                            <a href="MiCuenta.php" class="nav-link active">Mi cuenta</a>
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

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $mensaje ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-lg rounded">
                    <div class="card-header text-center bg-primary text-white">
                        <h2 class="mb-0">Perfil del Usuario</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="editarPerfil.php" method="POST" enctype="multipart/form-data">
                            <!-- Foto de perfil -->
                            <div class="text-center mb-4">
                                <?php if (!empty($ruta_foto)): ?>
                                    <img src="<?= $ruta_foto ?>" alt="Foto de Perfil"
                                        class="rounded-circle border border-3 border-primary profile-img">
                                <?php else: ?>
                                    <img src="../assets/user-default.png" alt="Foto de Perfil"
                                        class="rounded-circle border border-3 border-primary profile-img">
                                <?php endif; ?>

                                <?php if ($modo_edicion): ?>
                                    <div class="mt-3">
                                        <input type="file" name="imagen" class="form-control form-control-sm"
                                            accept="image/*">
                                        <small class="text-muted">Sube una nueva foto de perfil (máx. 2MB)</small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Campos del formulario -->
                            <div class="mb-3">
                                <label for="nombre_completo" class="form-label">Nombre</label>
                                <input type="text" name="nombre_completo"
                                    class="form-control <?= $modo_edicion ? '' : 'readonly-field' ?>"
                                    value="<?= $nombre_completo ?>" <?= $modo_edicion ? '' : 'readonly' ?>>
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="email" class="form-control readonly-field" value="<?= $correo ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" name="telefono"
                                    class="form-control <?= $modo_edicion ? '' : 'readonly-field' ?>"
                                    value="<?= $telefono ?>" <?= $modo_edicion ? '' : 'readonly' ?>>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                                <input type="text" class="form-control readonly-field" value="<?= $descripcion ?>"
                                    readonly>
                            </div>

                            <?php if ($modo_edicion): ?>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    <a href="MiCuenta.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>

                    <?php if (!$modo_edicion): ?>
                        <div class="card-footer text-center bg-light">
                            <a href="MiCuenta.php?editar=1" class="btn btn-primary">Editar Perfil</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>