<?php
session_start();

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
    $sql = "SELECT nombre_mascota,tm.descripcion as tipo_mascota,raza,edad,sexo,m.descripcion,mu.nombre_municipio, m.direccion,imagen,u.correo,u.telefono
    from mascotas as m inner join tipos_mascotas as tm on tm.id_tipo=m.tipo_mascota 
    inner join usuarios as u on m.usuario_id=u.id_usuario
    inner join municipios as mu on m.municipio=mu.id_municipio
    where id_mascota=$id_mascota";
    $resultado = $conexion->query($sql);
    $fila = $resultado->fetch_assoc();

    $nombre_mascota = $fila['nombre_mascota'];
    $tipo_mascota = $fila['tipo_mascota'];
    $raza = $fila['raza'];
    $edad = $fila['edad'];
    $sexo = $fila['sexo'];
    $descripcion = $fila['descripcion'];
    $ubicacion = $fila['direccion'];
    $municipio = $fila['nombre_municipio'];
    $ruta_foto = $fila['imagen'];
    $correo_contacto = $fila['correo'];
    $telefono_contacto = $fila['telefono'];

    $conexion->close();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nombre_mascota; ?> - Detalles | Adopta PETCienega</title>
    <meta name="description" content="Información detallada sobre <?php echo $nombre_mascota; ?>, <?php echo $tipo_mascota; ?> en adopción en la Ciénega">
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="../css/adoptaindex.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #f6c23e;
            --dark-color: #5a5c69;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fc;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .pet-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            border-radius: 0.35rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .pet-card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
        }

        .pet-img-container {
            height: 400px;
            overflow: hidden;
            border-radius: 0.35rem 0.35rem 0 0;
        }

        .pet-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .pet-img-container:hover .pet-img {
            transform: scale(1.05);
        }

        .contact-card {
            background-color: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            padding: 2rem;
        }

        .detail-icon {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .btn-adopt {
            background-color: var(--accent-color);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-adopt:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .badge-pet {
            background-color: var(--primary-color);
            font-weight: 600;
            padding: 0.5em 1em;
        }

        .description-text {
            line-height: 1.8;
            color: var(--dark-color);
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../assets/adoptapetcienega.png" alt="Logo Adopta PETCienega" width="40" height="40" class="me-2">
                <span>Adopta PETCienega</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="../adopta/" class="nav-link"><i class="bi bi-house-door me-1"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="MiCuenta.php" class="nav-link"><i class="bi bi-person me-1"></i> Mi cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a href="MisPublicaciones.php" class="nav-link"><i class="bi bi-images me-1"></i> Mis publicaciones</a>
                    </li>
                    <li class="nav-item">
                        <a href="../cerrar_sesion.php" class="nav-link"><i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <!-- Encabezado -->
        <div class="pet-header text-center mb-5">
            <h1 class="display-4 fw-bold"><?php echo $nombre_mascota; ?></h1>
            <p class="lead mb-0"><?php echo $tipo_mascota; ?> en adopción</p>
            <span class="badge bg-light text-dark mt-2 p-2"><?php echo $raza; ?></span>
        </div>

        <div class="row g-5">
            <!-- Columna de la imagen -->
            <div class="col-lg-6">
                <div class="pet-card h-100">
                    <div class="pet-img-container">
                        <img src="<?php echo $ruta_foto; ?>" class="pet-img" alt="<?php echo $nombre_mascota; ?>, <?php echo $raza; ?>">
                    </div>
                    <div class="card-body text-center">
                        <form action="interesado.php" method="POST">
                            <input type="hidden" name="id_mascota" value="<?php echo $id_mascota; ?>">
                            <button type="submit" class="btn btn-adopt btn-lg fw-bold mt-3">
                                <i class="bi bi-heart-fill me-2"></i>Estoy interesado
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna de información -->
            <div class="col-lg-6">
                <div class="pet-card h-100 p-4">
                    <h2 class="h3 fw-bold mb-4">Sobre <?php echo $nombre_mascota; ?></h2>

                    <p class="description-text mb-4"><?php echo $descripcion; ?></p>

                    <h3 class="h5 fw-bold mb-3">Detalles</h3>
                    <ul class="feature-list">
                        <li>
                            <i class="bi bi-tag-fill detail-icon"></i>
                            <strong>Tipo:</strong> <?php echo $tipo_mascota; ?>
                        </li>
                        <li>
                            <i class="bi bi-star-fill detail-icon"></i>
                            <strong>Raza:</strong> <?php echo $raza; ?>
                        </li>
                        <li>
                            <i class="bi bi-calendar-event-fill detail-icon"></i>
                            <strong>Edad:</strong> <?php echo $edad; ?> años
                        </li>
                        <li>
                            <i class="bi bi-gender-<?php echo strtolower($sexo[0]) == 'h' ? 'male' : 'female'; ?> detail-icon"></i>
                            <strong>Sexo:</strong> <?php echo $sexo; ?>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt-fill detail-icon"></i>
                            <strong>Ubicación:</strong> <?php echo $ubicacion; ?>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt-fill detail-icon"></i>
                            <strong>Municipio:</strong> <?php echo $municipio; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sección de contacto -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="contact-card mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="h4 fw-bold mb-3">Contacta al dueño para adoptar</h3>
                            <p class="mb-4">Si estás interesado en darle un hogar a <?php echo $nombre_mascota; ?>, contacta a su dueño actual.</p>

                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-telephone-fill fs-4 me-3 text-primary"></i>
                                <div>
                                    <h4 class="h6 fw-bold mb-0">Teléfono</h4>
                                    <a href="tel:<?php echo $telefono_contacto; ?>" class="text-decoration-none"><?php echo $telefono_contacto; ?></a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope-fill fs-4 me-3 text-primary"></i>
                                <div>
                                    <h4 class="h6 fw-bold mb-0">Correo electrónico</h4>
                                    <a href="mailto:<?php echo $correo_contacto; ?>" class="text-decoration-none"><?php echo $correo_contacto; ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 text-center mt-4 mt-md-0">
                            <div class="bg-light p-4 rounded">
                                <i class="bi bi-info-circle-fill fs-1 text-primary mb-3"></i>
                                <p class="mb-0">Recuerda verificar toda la información antes de concretar la adopción.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Adopta PETCienega</h5>
                    <p>Conectando mascotas con familias amorosas en la región de la Ciénega.</p>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="../adopta/" class="text-white text-decoration-none">Mascotas</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Cómo adoptar</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Preguntas frecuentes</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Contacto</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-2"></i> contacto@adoptapetcienega.com</li>
                        <li><i class="bi bi-telephone me-2"></i> +52 123 456 7890</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Adopta PETCienega. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de scroll suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>