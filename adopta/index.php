<?php
session_start();
$id_usuario = $_SESSION['id_usuario'];
$nombre_usuario = $_SESSION['nombre_usuario'];
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
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Adopta PETCienega</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="MiCuenta.php">Mi cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PublicarMascota.php">Publicar mascota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="MisPublicaciones.php">Mis publicaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>  
    <br> 
    <br>   
    <h1 >LA FORMA MÁS FACIL DE ADOPTAR</h1>
</body>

</html>