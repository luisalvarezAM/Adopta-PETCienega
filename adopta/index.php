<?php
session_start();
$id_usuario = $_SESSION['id_usuario'];

require('../assets/conexionBD.php'); //conexion a la base de datos
$conexion = obtenerConexion();

$sql = "SELECT id_mascota,nombre_mascota,tm.descripcion,imagen
from mascotas inner join tipos_mascotas as tm  
on mascotas.tipo_mascota=tm.id_tipo";

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
                            <a href="#" class="nav-link active">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Publicar mascota</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Mis publicaciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </header>

    <main>
        <section class="py-4 text-center container">
            <div class="row py-lg-">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">La forma más facil de adoptar</h1>
                    <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc.
                        Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
                    <p>
                        <a href="#" class="btn btn-primary my-2">Subir mascota</a>
                    </p>
                </div>
            </div>
        </section>
        <!---Aqui aparece los animales registrados-->
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
                                <img src="<?php echo $ruta_foto;?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $nombre_mascota; ?></h5>
                                <p class="card-text"><?php echo $descripcion; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="" class="btn btn-primary">Detalles</a>
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