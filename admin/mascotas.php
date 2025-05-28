<?php
session_start();

require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

// Consulta SQL para obtener mascotas disponibles
$sql = "SELECT m.id_mascota,m.nombre_mascota,tm.descripcion,m.sexo,m.municipio,m.imagen
        FROM mascotas m
        JOIN tipos_mascotas tm ON m.tipo_mascota = tm.id_tipo
        WHERE m.estatus_id = 1 OR m.estatus_id = 2";
$result = $conexion->query($sql);
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Dashboard</title>
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
                <li>
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
                <li class="active">
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
                    <a href="publicaciones.php">
                        <i class="fas fa-chart-bar"></i>
                        <span>Publicaciones</span>
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
                                <h2><i class="fas fa-paw me-2"></i>Gestión de Mascotas</h2>
                                <p class="text-muted">Listado de todas las mascotas disponibles para adopción</p>
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
                                        <input type="text" id="buscarMascota" class="form-control" placeholder="Buscar mascotas...">
                                        <button class="btn btn-outline-secondary" type="button" id="btnLimpiarBusqueda">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="filtroTipo">
                                        <option value="" selected>Todos los tipos</option>
                                        <option value="Perro">Perro</option>
                                        <option value="Gato">Gato</option>
                                        <option value="Ave">Ave</option>
                                        <option value="Ave">Conejos</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="ordenarMascota">
                                        <option value="fecha_desc" selected>Fecha: Nuevos primero</option>
                                        <option value="fecha_asc">Fecha: Antiguos primero</option>
                                        <option value="nombre_asc">Nombre: A-Z</option>
                                        <option value="nombre_desc">Nombre: Z-A</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Mascotas -->
                    <div class="card shadow">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Mascotas Disponibles</h5>
                                <span class="badge bg-primary" id="contadorMascota"><?= $result->num_rows ?> mascotas</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50px">ID</th>
                                            <th width="60px">Foto</th>
                                            <th>Nombre</th>
                                            <th>Tipo Mascota</th>
                                            <th>Sexo</th>
                                            <th>Municipio</th>
                                            <th width="150px">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaMascota">
                                        <?php while ($fila = $result->fetch_assoc()):
                                            $id_mascota = $fila['id_mascota'];
                                            $nombre = $fila['nombre_mascota'];
                                            $tipo_mascota = $fila['descripcion'];
                                            $sexo = $fila['sexo'];
                                            $municipio = $fila['municipio'];
                                            $ruta_foto = !empty($fila['imagen']) ? $fila['imagen'] : '../assets/mascotaVacia.jpg';
                                        ?>
                                            <tr data-id="<?= $id_mascota ?>">
                                                <td><?= $id_mascota ?></td>
                                                <td>
                                                    <img src="<?= $ruta_foto ?>" alt="Foto mascota" class="rounded-circle" width="40" height="40">
                                                </td>
                                                <td><?= htmlspecialchars($nombre) ?></td>
                                                <td><?= htmlspecialchars($tipo_mascota) ?></td>
                                                <td><?= htmlspecialchars($sexo) ?></td>
                                                <td><?= htmlspecialchars($municipio) ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <!-- En la tabla de mascotas, cambia el botón por esto: -->
                                                        <a href="ver_mascota.php?id=<?= $id_mascota ?>" class="btn btn-outline-primary btn-sm" title="Visualizar">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-outline-warning btn-editar-mascota" title="Editar" data-id="<?= $id_mascota ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-eliminar-mascota" title="Eliminar" data-id="<?= $id_mascota ?>">
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

            <!-- Modal para nueva mascota -->
            <div class="modal fade" id="nuevaMascotaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-paw me-2"></i>Agregar Nueva Mascota</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="guardar_mascota.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edad" class="form-label">Edad</label>
                                            <input type="text" class="form-control" id="edad" name="edad" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sexo" class="form-label">Sexo</label>
                                            <select class="form-select" id="sexo" name="sexo" required>
                                                <option value="" selected disabled>Seleccione el sexo</option>
                                                <option value="Macho">Macho</option>
                                                <option value="Hembra">Hembra</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">Tipo</label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value="" selected disabled>Seleccione el tipo</option>
                                                <option value="1">Perro</option>
                                                <option value="2">Gato</option>
                                                <option value="3">Ave</option>
                                                <option value="4">Otro</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="raza" class="form-label">Raza</label>
                                            <select class="form-select" id="raza" name="raza" required>
                                                <option value="" selected disabled>Seleccione la raza</option>
                                                
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="estado" class="form-label">Estado</label>
                                            <select class="form-select" id="estado" name="estado" required>
                                                <option value="1" selected>Disponible</option>
                                                <option value="2">En proceso</option>
                                                <option value="3">Adoptado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    <small class="text-muted">Sube una imagen de la mascota (opcional)</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Mascota
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal para visualizar mascota -->
            <div class="modal fade" id="verMascotaModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Detalles de la Mascota</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <img id="mascotaFoto" src="" alt="Foto de la mascota" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombre:</strong> <span id="mascotaNombre"></span></p>
                                    <p><strong>Edad:</strong> <span id="mascotaEdad"></span></p>
                                    <p><strong>Sexo:</strong> <span id="mascotaSexo"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Tipo:</strong> <span id="mascotaTipo"></span></p>
                                    <p><strong>Raza:</strong> <span id="mascotaRaza"></span></p>
                                    <p><strong>Estado:</strong> <span id="mascotaEstado" class="badge bg-success"></span></p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p><strong>Descripción:</strong></p>
                                <p id="mascotaDescripcion" class="text-muted"></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
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
                            <p>¿Estás seguro que deseas eliminar esta mascota? Esta acción no se puede deshacer.</p>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/admin.js"></script>
    <script>
        $(document).ready(function() {
            // Cargar razas según el tipo seleccionado
            $('#tipo').change(function() {
                var tipoId = $(this).val();
                if (tipoId) {
                    $.ajax({
                        url: 'obtener_razas.php',
                        type: 'POST',
                        data: {
                            tipo_id: tipoId
                        },
                        success: function(data) {
                            $('#raza').html(data);
                        }
                    });
                } else {
                    $('#raza').html('<option value="" selected disabled>Seleccione la raza</option>');
                }
            });

            // Eliminar mascota
            var idMascotaAEliminar;
            $('.btn-eliminar-mascota').click(function() {
                idMascotaAEliminar = $(this).data('id');
                $('#confirmarEliminarModal').modal('show');
            });

            $('#confirmarEliminar').click(function() {
                $.ajax({
                    url: 'eliminar_mascota.php',
                    type: 'POST',
                    data: {
                        id: idMascotaAEliminar
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error al eliminar la mascota');
                        }
                    }
                });
            });

            // Buscador de mascotas
            $('#buscarMascota').keyup(function() {
                var searchText = $(this).val().toLowerCase();
                $('#tablaMascota tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1)
                });
                $('#contadorMascota').text($('#tablaMascota tr:visible').length + ' mascotas');
            });

            // Filtro por tipo
            $('#filtroTipo').change(function() {
                var tipo = $(this).val();
                if (tipo) {
                    $('#tablaMascota tr').each(function() {
                        $(this).toggle($(this).find('td:eq(5)').text() === tipo);
                    });
                } else {
                    $('#tablaMascota tr').show();
                }
                $('#contadorMascota').text($('#tablaMascota tr:visible').length + ' mascotas');
            });

            // Limpiar búsqueda
            $('#btnLimpiarBusqueda').click(function() {
                $('#buscarMascota').val('');
                $('#tablaMascota tr').show();
                $('#contadorMascota').text($('#tablaMascota tr').length + ' mascotas');
            });
        });
    </script>
</body>

</html>