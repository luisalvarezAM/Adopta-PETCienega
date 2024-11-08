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
on mascotas.tipo_mascota=tm.id_tipo where usuario_id=$id_usuario";

$result = $conexion->query($sql);
$conexion->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis publicaciones</title>
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
                            <a href="index.php" class="nav-link ">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="MiCuenta.php" class="nav-link">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="PublicarMascota.php" class="nav-link">Publicar mascota</a>
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
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $nombre_mascota; ?></h5>
                                <p class="card-text"><?php echo $descripcion; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="Detalle.php?id=<?php echo $id_mascota;?>" class="btn btn-primary">Detalle</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>