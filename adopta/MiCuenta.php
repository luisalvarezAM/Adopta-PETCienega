<?php
session_start();
$varsesion = $_SESSION['id_usuario'] ?? null;
if (empty($varsesion)) {
    header("location: /adoptapetcienega/");
    exit();
}

$modo_edicion = isset($_GET['editar']) && $_GET['editar'] == '1';

require('../assets/conexionBD.php');
$conexion = obtenerConexion();
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre_completo, correo, telefono, tu.descripcion, img_perfil, m.nombre_municipio, direccion 
        FROM usuarios 
        INNER JOIN tipos_usuarios as tu ON usuarios.tipo_usuario=tu.id_tipo 
        JOIN municipios as m ON municipio=m.id_municipio
        WHERE id_usuario=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$fila = $result->fetch_assoc();

$ruta_foto = htmlspecialchars($fila['img_perfil'] ?? '');
$nombre_completo = htmlspecialchars($fila['nombre_completo'] ?? '');
$correo = htmlspecialchars($fila['correo'] ?? '');
$telefono = htmlspecialchars($fila['telefono'] ?? '');
$descripcion = htmlspecialchars($fila['descripcion'] ?? '');
$municipio = htmlspecialchars($fila['nombre_municipio'] ?? '');
$direccion = htmlspecialchars($fila['direccion'] ?? '');

$conexion->close();

// Mostrar mensajes
$mensaje = $_SESSION['mensaje'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['mensaje'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Adopta PET Cienega</title>
    <meta name="description" content="Administra tu perfil en Adopta PET Cienega">
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .profile-card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            padding: 1.5rem;
        }
        
        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: -75px auto 1rem;
        }
        
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .edit-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .form-control:disabled, .form-control[readonly] {
            background-color: var(--secondary-color);
            border-color: #e3e6f0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .info-label {
            font-weight: 600;
            color: #5a5c69;
        }
        
        .nav-link.active {
            font-weight: 600;
            color: var(--primary-color) !important;
        }
        
        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <img src="../assets/adoptapetcienega.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                    <span>Adopta PET Cienega</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader">
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
                    </ul>
                    <div class="d-flex">
                        <a href="../cerrar_sesion.php" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Notificaciones -->
                <?php if ($mensaje): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?= $mensaje ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Tarjeta de Perfil -->
                <div class="profile-card card mb-4">
                    <div class="card-header text-center text-white">
                        <h2 class="mb-0"><i class="bi bi-person-circle me-2"></i>Perfil de Usuario</h2>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="editarPerfil.php" method="POST" enctype="multipart/form-data">
                            <!-- Foto de Perfil -->
                            <div class="profile-img-container">
                                <?php if (!empty($ruta_foto)): ?>
                                    <img src="<?= $ruta_foto ?>" alt="Foto de Perfil" class="profile-img rounded-circle">
                                <?php else: ?>
                                    <img src="../assets/user-default.png" alt="Foto de Perfil" class="profile-img rounded-circle">
                                <?php endif; ?>
                                
                                <?php if ($modo_edicion): ?>
                                    <label for="imagen" class="edit-icon" title="Cambiar foto">
                                        <i class="bi bi-camera"></i>
                                        <input type="file" id="imagen" name="imagen" class="d-none" accept="image/*">
                                    </label>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($modo_edicion): ?>
                                <div class="text-center mb-4">
                                    <small class="text-muted">Formatos: JPG, PNG. Tamaño máximo: 2MB</small>
                                </div>
                            <?php endif; ?>

                            <!-- Información Personal -->
                            <h4 class="section-title">Información Personal</h4>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="nombre_completo" class="form-label info-label">Nombre Completo</label>
                                    <input type="text" id="nombre_completo" name="nombre_completo" 
                                        class="form-control <?= $modo_edicion ? '' : 'form-control-plaintext' ?>" 
                                        value="<?= $nombre_completo ?>" <?= $modo_edicion ? '' : 'readonly' ?>>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="correo" class="form-label info-label">Correo Electrónico</label>
                                    <input type="email" id="correo" class="form-control form-control-plaintext" 
                                        value="<?= $correo ?>" readonly>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label info-label">Teléfono</label>
                                    <input type="tel" id="telefono" name="telefono" 
                                        class="form-control <?= $modo_edicion ? '' : 'form-control-plaintext' ?>" 
                                        value="<?= $telefono ?>" <?= $modo_edicion ? '' : 'readonly' ?>>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="tipo_usuario" class="form-label info-label">Tipo de Usuario</label>
                                    <input type="text" id="tipo_usuario" class="form-control form-control-plaintext" 
                                        value="<?= $descripcion ?>" readonly>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <h4 class="section-title">Ubicación</h4>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="municipio" class="form-label info-label">Municipio</label>
                                    <input type="text" id="municipio" class="form-control form-control-plaintext" 
                                        value="<?= $municipio ?>" readonly>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="direccion" class="form-label info-label">Dirección</label>
                                    <input type="text" id="direccion" name="direccion" 
                                        class="form-control <?= $modo_edicion ? '' : 'form-control-plaintext' ?>" 
                                        value="<?= $direccion ?>" <?= $modo_edicion ? '' : 'readonly' ?>>
                                </div>
                            </div>

                            <!-- Botones -->
                            <?php if ($modo_edicion): ?>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <a href="MiCuenta.php" class="btn btn-outline-secondary me-md-2">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Guardar Cambios
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                    
                    <?php if (!$modo_edicion): ?>
                        <div class="card-footer text-center bg-light">
                            <a href="MiCuenta.php?editar=1" class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i> Editar Perfil
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Adopta PET Cienega. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar vista previa de la imagen seleccionada
        document.getElementById('imagen')?.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-img').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>