<?php
session_start();
error_reporting(0);
$varsesion = $_SESSION['id_usuario'];
if ($varsesion == null || $varsesion = '') {
    header("location: /adoptapetcienega/");
    die();
}
$id_usuario = $_SESSION['id_usuario'];
$nombre_usuario=$_SESSION['nombre_usuario'];
require('../assets/conexionBD.php'); //conexion a la base de datos
$conexion = obtenerConexion();

$sql = "SELECT id_mascota, nombre_mascota, tm.descripcion, imagen
FROM mascotas 
INNER JOIN tipos_mascotas AS tm ON mascotas.tipo_mascota = tm.id_tipo
WHERE estatus_adopcion != 'Adoptado'";

$result = $conexion->query($sql);
$conexion->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="icon" type="image/x-icon"
        href="../assets/adoptapetcienega.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/bootstrap.bundle.min.js" rel="stylesheet" />
    <link href="../css/adoptaindex.css" rel="stylesheet" />
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
                            <a href="" class="nav-link active">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="MiCuenta.php" class="nav-link">Mi cuenta</a>
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

    <main>
        <section class="py-4 text-center container">
            <div class="row py-lg-">
                <div class="col-lg-8 col-md-5 mx-auto">
                    <h1 class="fw-light">Bienvenid@ <?php echo $nombre_usuario; ?></h1>
                    <p class="lead text-muted">Gracias por ayudarnos a encontrar un hogar para esta mascota.
                        Recuerda que no contamos con un refugio físico;
                        conectamos a las mascotas con posibles adoptantes a través de
                        esta plataforma.</p>
                    <p>
                        <button class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#formularioMascota">Subir mascota</button>
                    </p>
                </div>
            </div>
        </section>
        <!---Aqui aparece los animales registrados-->
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php while ($fila = $result->fetch_assoc()) {
                    $id_mascota = $fila['id_mascota'];
                    $nombre_mascota = $fila['nombre_mascota'];
                    $descripcion = $fila['descripcion'];
                    $ruta_foto = htmlspecialchars($fila['imagen'] ?? '');
                ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <?php if ($ruta_foto): ?>
                                <div class="image-container" style="height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo $ruta_foto; ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px 10px 0 0;">
                                </div>
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo $nombre_mascota; ?></h5>
                                <div class="mt-3">
                                    <a href="Detalle.php?id=<?php echo $id_mascota; ?>" class="btn btn-primary">Detalle</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="formularioMascota" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFormularioLabel">Publicar Mascota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="PublicarMascota.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nombre_mascota" class="form-label">Nombre de la Mascota:</label>
                                <input type="text" class="form-control" id="nombre_mascota" name="nombre_mascota" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_mascota" class="form-label">Tipo de Mascota:</label>
                                <select class="form-select" id="tipo_mascota" name="tipo_mascota" required>
                                    <option value="1">Perro</option>
                                    <option value="2">Gato</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="raza" class="form-label">Raza:</label>
                                    <input type="text" class="form-control" id="raza" name="raza" required>
                                </div>
                                <div class="col">
                                    <label for="edad" class="form-label">Edad en Años:</label>
                                    <input type="number" class="form-control" id="edad" name="edad" min="0" required>
                                </div>
                                <div class="col">
                                    <label for="sexo" class="form-label">Sexo:</label>
                                    <select class="form-select" id="sexo" name="sexo" required>
                                        <option value="M">Macho</option>
                                        <option value="H">Hembra</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción:</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ubicacion_actual" class="form-label">Ubicación Actual:</label>
                                <input type="text" class="form-control" id="ubicacion_actual" name="ubicacion_actual" required>
                            </div>
                            <div class="mb-3">
                                <label for="imagen" class="form-label">Imagen:</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Publicar Mascota</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>