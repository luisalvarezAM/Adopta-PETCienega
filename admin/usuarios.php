<?php
session_start();

require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nombre_completo = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $municipio = $_POST['municipio'];
    $direccion = $_POST['direccion'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_registro = date("Y-m-d H:i:s");
    $tipo_usuario = 1;
    $contraseña = md5($_POST['contraseña']);

    
    $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'El nombre de usuario "' . htmlspecialchars($username) . '" ya está registrado.'
        ];
        header('Location: administradores.php');
        exit();
    }

    $uploadDir = '../assets/img/fotos_perfil/';

    
    $img_perfil = '../assets/usuarioVacio.jpg';

    
    if (isset($_FILES['img_perfil']) && $_FILES['img_perfil']['error'] === UPLOAD_ERR_OK) {
        
        $ext = strtolower(pathinfo($_FILES['img_perfil']['name'], PATHINFO_EXTENSION));

       
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowedExtensions)) {
            
            $filename = $username . '.' . $ext;
            $uploadFile = $uploadDir . $filename;

            
            if (move_uploaded_file($_FILES['img_perfil']['tmp_name'], $uploadFile)) {
                $img_perfil = '../assets/img/fotos_perfil/' . $filename;
            } else {
                
                $_SESSION['notification'] = [
                    'type' => 'warning',
                    'message' => 'Se subió la imagen pero hubo un problema al guardarla. Se usará imagen por defecto.'
                ];
            }
        } else {
           
            $_SESSION['notification'] = [
                'type' => 'error',
                'message' => 'Formato de imagen no válido. Usando imagen por defecto.'
            ];
        }
    }

    
    $sql = $conexion->prepare("INSERT INTO usuarios 
            (username, nombre_completo, contraseña, correo, telefono, municipio, direccion, img_perfil, tipo_usuario, fec_registro) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $sql->bind_param("ssssssssss", $username, $nombre_completo, $contraseña, $correo, $telefono, $municipio, $direccion, $img_perfil, $tipo_usuario, $fecha_registro);

    if ($sql->execute()) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Usuario registrado correctamente.'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => 'Error al registrar: ' . htmlspecialchars($sql->error)
        ];
    }
}

$sql = "SELECT id_usuario, username, nombre_completo, correo, telefono, m.nombre_municipio, fec_registro, img_perfil 
FROM usuarios 
left JOIN municipios m ON usuarios.municipio= m.id_municipio
where tipo_usuario=1";
$result = $conexion->query($sql);
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/notificaciones.css">

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>Administrador<span> Adopta PETCienega</span></h3>
                <strong>AP</strong>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="active">
                    <a href="usuarios.php">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="administradores.php">
                        <i class="fas fa-user-cog"></i>
                        <span>Administradores</span>
                    </a>
                </li>
                <li>
                    <a href="mascotas.php">
                        <i class="fas fa-paw"></i>
                        <span>Mascotas</span>
                    </a>
                </li>
                <li>
                    <a href="adopciones.php">
                        <i class="fas fa-calendar-check"></i>
                        <span>Adopciones</span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <i class="fas fa-cog"></i>
                        <span>Editar perfil</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <div class="user-profile ml-auto">
                        <div class="user-info">
                            <span class="user-name">Administrador</span>
                        </div>
                        <img src="../assets/adoptapetcienega.png" alt="User" class="user-avatar rounded-circle">
                    </div>
                </div>
            </nav>
            <div id="notification-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <?php if (isset($_SESSION['notification'])): ?>
                    <div class="alert alert-<?= $_SESSION['notification']['type'] ?> alert-dismissible fade show">
                        <?= $_SESSION['notification']['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['notification']); ?>
                <?php endif; ?>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    <div class="page-header mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2><i class="fas fa-user-cog me-2"></i>Gestión de Usuarios</h2>
                                <p class="text-muted">Listado de todos los usuarios del sistema</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
                                    <i class="fas fa-plus me-1"></i> Nuevo Usuario
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros y Buscador -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" id="buscarUsuario" class="form-control" placeholder="Buscar usuarios...">
                                        <button class="btn btn-outline-secondary" type="button" id="btnLimpiarBusqueda">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroMunicipio">
                                        <option value="" selected>Todos los municipios</option>
                                        <option value="Atotonilco el Alto">Atotonilco el Alto</option>
                                        <option value="Ayotlán">Ayotlán</option>
                                        <option value="Degollado">Degollado</option>
                                        <option value="Jamay">Jamay</option>
                                        <option value="La barca">La barca</option>
                                        <option value="Ocotlán">Ocotlán</option>
                                        <option value="Poncitlán">Poncitlán</option>
                                        <option value="Tototlán">Tototlán</option>
                                        <option value="Zapotlán del Rey">Zapotlán del Rey</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="ordenarUsuario">
                                        <option value="fecha_desc" selected>Fecha: Nuevos primero</option>
                                        <option value="fecha_asc">Fecha: Antiguos primero</option>
                                        <option value="nombre_asc">Nombre: A-Z</option>
                                        <option value="nombre_desc">Nombre: Z-A</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Usuarios -->
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Usuarios del Sistema</h5>
                                <span class="badge bg-primary" id="contadorUsuario">0 usuarios</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50px">ID</th>
                                            <th width="60px">Foto</th>
                                            <th>Usuario</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Telefono</th>
                                            <th>Municipio</th>
                                            <th width="150px">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaUsuario">
                                        <?php while ($fila = $result->fetch_assoc()):
                                            $id_usuario = $fila['id_usuario'];
                                            $username = $fila['username'];
                                            $nombre_completo = $fila['nombre_completo'];
                                            $email = $fila['correo'];
                                            $telefono = $fila['telefono'];
                                            $municipio = $fila['nombre_municipio'];
                                            $ruta_foto = !empty($fila['img_perfil']) ? $fila['img_perfil'] : '../assets/usuarioVacio.jpg';
                                        ?>
                                            <tr data-id="<?= $id_usuario ?>" data-funciones="usuarios,mascotas,pajaros">
                                                <td><?= $id_usuario ?></td>
                                                <td>
                                                    <img src="<?= $ruta_foto ?>" alt="Foto perfil" class="rounded-circle" width="40" height="40">
                                                </td>
                                                <td><?= htmlspecialchars($username) ?></td>
                                                <td><?= htmlspecialchars($nombre_completo) ?></td>
                                                <td><?= htmlspecialchars($email) ?></td>
                                                <td><?= htmlspecialchars($telefono) ?></td>
                                                <td><?= htmlspecialchars($municipio) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="verPerfil.php?id=<?= $id_usuario ?>" class="btn btn-outline-primary" title="Ver perfil">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="editarPerfil.php?id=<?= $id_usuario ?>" class="btn btn-outline-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-outline-danger btn-eliminar-admin" title="Eliminar"
                                                            data-id="<?= $id_usuario ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <nav aria-label="Paginación">
                                <ul class="pagination justify-content-center mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Anterior</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!--- Agregar nuevo usuario--->
            <div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Agregar Nuevo Usuario</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="registrar_usuario" value="1">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Nombre de Usuario</label>
                                            <input type="text" class="form-control" id="username" name="username" required
                                                minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+">
                                            <div id="username-feedback" class=""></div>
                                            <small class="text-muted">Solo letras, números y guiones bajos (3-20 caracteres)</small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="correo" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="municipio" class="form-label">Municipio</label>
                                            <select class="form-select" id="municipio" name="municipio" required>
                                                <option value="" selected disabled>Seleccione un municipio</option>
                                                <option value="1">Atotonilco el Alto</option>
                                                <option value="2">Ayotlán</option>
                                                <option value="3">Degollado</option>
                                                <option value="4">Jamay</option>
                                                <option value="5">La barca</option>
                                                <option value="6">Ocotlán</option>
                                                <option value="7">Poncitlán</option>
                                                <option value="8">Tototlán</option>
                                                <option value="9">Zapotlán del Rey</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefono" class="form-label">Teléfono</label>
                                            <input type="tel" class="form-control" id="telefono" name="telefono">
                                        </div>
                                        <div class="mb-3">
                                            <label for="contraseña" class="form-label">Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="direccion" class="form-label">Dirección</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="img_perfil" class="form-label">Imagen de Perfil</label>
                                    <input type="file" class="form-control" id="img_perfil" name="img_perfil" accept="image/*">
                                    <small class="text-muted">Sube una imagen para el perfil (opcional)</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmación Eliminar -->
            <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.</p>
                            <p class="fw-bold">Todos los datos asociados serán eliminados permanentemente.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/admin.js"></script>
    <script src="../js/usuarios.js"></script>
</body>

</html>