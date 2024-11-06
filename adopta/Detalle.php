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

$id_mascota = isset($_GET['id']) ? $_GET['id'] : '';

if ($id_mascota == '') {
    echo 'Error al procesar la petición';
    exit;
}
$sql = "SELECT count(id_mascota) from mascotas where id_mascota=$id_mascota";
$result = $conexion->query($sql);

if ($result->fetch_column() > 0) {
    $sql = "SELECT nombre_mascota,tm.descripcion as tipo_mascota,raza,edad,sexo,m.descripcion, ubicacion_actual,imagen,u.correo,u.telefono
    from mascotas as m inner join tipos_mascotas as tm on tm.id_tipo=m.tipo_mascota 
    inner join usuarios as u on m.usuario_id=u.id_usuario
    where id_mascota=$id_mascota";
    $resultado = $conexion->query($sql);
    $fila = $resultado->fetch_assoc();

    $nombre_mascota = $fila['nombre_mascota'];
    $tipo_mascota = $fila['tipo_mascota'];
    $raza = $fila['raza'];
    $edad = $fila['edad'];
    $sexo = $fila['sexo'];
    $descripcion = $fila['descripcion'];
    $ubicacion = $fila['ubicacion_actual'];
    $ruta_foto = $fila['imagen'];
    $correo_contacto = $fila['correo'];
    $telefono_contacto = $fila['telefono'];

    $conexion->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de mascota</title>
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
                            <a href="/" class="nav-link active">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="MiCuenta.php" class="nav-link">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="PublicarMascota.php" class="nav-link">Publicar mascota</a>
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
        <!--- Sección de la descripción de la mascota registrada -->
        <div class="container mt-5 mb-5">
            <div class="row align-items-center shadow p-4 rounded" style="background-color: rgba(0, 123, 255, 0.2);">
                <!-- Imagen de la mascota -->
                <div class="col-md-6 order-md-1 text-center mb-4 mb-md-0">
                    <img src="<?php echo $ruta_foto; ?>" class="img-fluid rounded" alt="Imagen de <?php echo $nombre_mascota; ?>">
                </div>

                <!-- Información de la mascota -->
                <div class="col-md-6 order-md-2">
                    <h1 class="display-4 font-weight-bold text-primary mb-3"><?php echo $nombre_mascota; ?></h1>

                    <h3 class="text-muted mb-2">Tipo de mascota: <span class="text-secondary"><?php echo $tipo_mascota; ?></span></h3>

                    <h4 class="text-muted mb-3">Raza: <span class="text-secondary"><?php echo $raza; ?></span></h4>

                    <p class="lead text-justify">
                        <?php echo $descripcion; ?>
                    </p>
                    <h4 class="text-muted mb-2">Edad: <?php echo $edad; ?> años</h4>
                    <h4 class="text-muted mb-2">Ubicación : <?php echo $ubicacion; ?> </h4>

                </div>

            </div>
            <!-- Cuadro de contacto para adopción -->
            <div class="col-12 mt-5">
                <div class="p-4 rounded text-center" style="background-color: rgba(255, 255, 255, 0.9); color: #333;">
                    <h4 class="font-weight-bold mb-3">Contacto para Adopción</h4>

                    <p class="mb-2">
                        <span class="font-weight-bold" style="font-size: 1.2em; color: #007bff;">Teléfono:</span>
                        <a href="tel:<?php echo $telefono_contacto; ?>" class="text-dark" style="font-size: 1.2em;">
                            <?php echo $telefono_contacto; ?>
                        </a>
                    </p>

                    <p>
                        <span class="font-weight-bold" style="font-size: 1.2em; color: #007bff;">Email:</span>
                        <a href="mailto:<?php echo $correo_contacto; ?>" class="text-dark" style="font-size: 1.2em;">
                            <?php echo $correo_contacto; ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>