<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['id_usuario'];
if ($varsesion == null || $varsesion = '') {
    header("location: /adoptapetcienega/");
    die();
}

require('../assets/conexionBD.php');
$conexion = obtenerConexion();

// Obtener ID de la mascota a editar
$id_mascota = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificar si el usuario es dueño de la mascota
$sql_verificar = "SELECT usuario_id FROM mascotas WHERE id_mascota = $id_mascota";
$result_verificar = $conexion->query($sql_verificar);
if ($result_verificar->num_rows == 0 || $result_verificar->fetch_assoc()['usuario_id'] != $_SESSION['id_usuario']) {
    $_SESSION['error'] = "No tienes permiso para editar esta mascota";
    header("Location: MisPublicaciones.php");
    exit();
}

// Obtener datos actuales de la mascota
$sql_mascota = "SELECT * FROM mascotas WHERE id_mascota = $id_mascota";
$result_mascota = $conexion->query($sql_mascota);
$mascota = $result_mascota->fetch_assoc();

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota</title>
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .pet-img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }
        .form-label {
            font-weight: 500;
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
                            <a href="MiCuenta.php" class="nav-link">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="MisPublicaciones.php" class="nav-link active">Mis publicaciones</a>
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
            <div class="col-md-10 col-lg-8">
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
                        <h2 class="mb-0">Editar Mascota</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="actualizarMascota.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_mascota" value="<?= $id_mascota ?>">
                            
                            <!-- Foto de la mascota -->
                            <div class="text-center mb-4">
                                <?php if (!empty($mascota['imagen'])): ?>
                                    <img src="<?= $mascota['imagen'] ?>" alt="<?= $mascota['nombre_mascota'] ?>"
                                        class="pet-img mb-3 border border-3 border-primary">
                                <?php else: ?>
                                    <img src="../assets/pet-default.png" alt="Mascota"
                                        class="pet-img mb-3 border border-3 border-primary">
                                <?php endif; ?>
                                <div class="mt-3">
                                    <input type="file" name="imagen" class="form-control"
                                        accept="image/*">
                                    <small class="text-muted">Cambiar foto (máx. 2MB, JPG/PNG)</small>
                                </div>
                            </div>

                            <!-- Campos del formulario -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_mascota" class="form-label">Nombre de la mascota</label>
                                        <input type="text" name="mascota" class="form-control" id="mascota"
                                            value="<?= htmlspecialchars($mascota['nombre_mascota']) ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_mascota" class="form-label">Tipo</label>
                                        <select name="tipo_mascota" class="form-select" id="tipo_mascota" required>
                                            <option value="1" <?= $tipo_mascota['tipo_mascota'] == 'Perro' ? 'selected' : '' ?>>Perro</option>
                                            <option value="2" <?= $tipo_mascota['tipo_mascota'] == 'Gato' ? 'selected' : '' ?>>Gato</option>
                                            <option value="Otro" <?= $tipo_mascota['tipo_mascota'] == 'Otro' ? 'selected' : '' ?>>Otro</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="raza" class="form-label">Raza</label>
                                        <input type="text" name="raza" id="raza" class="form-control" 
                                            value="<?= htmlspecialchars($mascota['raza']) ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="edad" class="form-label">Edad (años)</label>
                                        <input type="number" name="edad" id="edad" class="form-control" min="0" max="30"
                                            value="<?= $mascota['edad'] ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="sexo" class="form-label">Sexo</label>
                                        <select name="sexo" class="form-select" required id="sexo">
                                            <option value="M" <?= $mascota['sexo'] == 'M' ? 'selected' : '' ?>>Macho</option>
                                            <option value="H" <?= $mascota['sexo'] == 'H' ? 'selected' : '' ?>>Hembra</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($mascota['descripcion']) ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ubicacion_actual" class="form-label">Ubicación</label>
                                        <input type="text" name="ubicacion_actual" id="ubicacion_actual" class="form-control" 
                                            value="<?= htmlspecialchars($mascota['ubicacion_actual']) ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estatus_adopcion" class="form-label">Estatus de adopción</label>
                                        <select name="estatus_adopcion" class="form-select" required>
                                            <option value="Disponible" <?= $mascota['estatus_adopcion'] == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                                            <option value="En proceso" <?= $mascota['estatus_adopcion'] == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="MisPublicaciones.php" class="btn btn-secondary me-md-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>