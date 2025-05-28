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

$sql = "SELECT id_mascota,nombre_mascota,tm.descripcion,imagen
from mascotas inner join tipos_mascotas as tm  
on mascotas.tipo_mascota=tm.id_tipo where usuario_id=$id_usuario and estatus_id=3";

$result = $conexion->query($sql);
$conexion->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Adoptadas</title>
    <link rel="icon" type="image/x-icon"
        href="../assets/adoptapetcienega.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/bootstrap.bundle.min.js" rel="stylesheet" />
    <link href="../css/adoptaindex.css" rel="stylesheet" />
    <link href="../css/adopta.css" rel="stylesheet" />
</head>

<body>
    <!---Barrra de opciones -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-2">
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
                            <a href="MiCuenta.php" class="nav-link ">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="MisPublicaciones.php" class="nav-link ">Mis publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="MascotasAdoptadas.php" class="nav-link active ">Mascotas Adoptadas</a>
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
        <style>
            .formato-imagen {
                width: 100%;
                height: 360px;
                object-fit: cover;
                border-top-left-radius: 0.25rem;
                border-top-right-radius: 0.25rem;
            }

            .card-body .btn-group {
                margin-right: 5px;
            }

            .acciones {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .acciones .btn {
                flex: 1 1 auto;
                min-width: 48%;
            }

            @media (min-width: 768px) {
                .acciones .btn {
                    min-width: auto;
                    flex: none;
                }
            }
        </style>

    </header>

    <main>
        <!---Aqui aparece los animales registrados por el usuario-->
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php while ($fila = $result->fetch_assoc()) {
                    $id_mascota = $fila['id_mascota'];
                    $nombre_mascota = $fila['nombre_mascota'];
                    $descripcion = $fila['descripcion'];
                    $ruta_foto = htmlspecialchars($fila['imagen'] ?? '');
                ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php if ($ruta_foto): ?>
                                <img src="<?php echo $ruta_foto; ?>" class="formato-imagen">
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="btn-group">
                                    <a href="DetalleMascota.php?id=<?php echo $id_mascota; ?>" class="btn btn-primary">Detalle</a>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmarEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar esta publicación? Esta acción no se puede deshacer.</p>
                    <p class="fw-bold">Todas las imágenes y datos asociados serán eliminados permanentemente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Sí, eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmarEliminacion(id) {
            // Mostrar modal de confirmación
            $('#confirmarEliminar').modal('show');

            // Al confirmar, enviar el formulario
            $('#btnConfirmarEliminar').off('click').on('click', function() {
                $.ajax({
                    url: 'eliminarMascota.php',
                    type: 'POST',
                    data: {
                        id_mascota: id
                    },
                    success: function(response) {
                        location.reload(); // Recargar para ver los cambios
                    },
                    error: function(xhr, status, error) {
                        alert('Error al eliminar: ' + error);
                    }
                });
            });
        }
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>