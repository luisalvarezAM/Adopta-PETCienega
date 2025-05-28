<?php
session_start();

$varsesion = $_SESSION['id_usuario'] ?? null;
if (empty($varsesion)) {
    header("location: /adoptapetcienega/");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nombre_usuario = htmlspecialchars($_SESSION['nombre_usuario'] ?? '');
require('../assets/conexionBD.php');
$conexion = obtenerConexion();

$filtro_tipo = isset($_GET['tipo']) ? intval($_GET['tipo']) : null;

// Consulta preparada para mayor seguridad
$sql = "SELECT id_mascota, nombre_mascota, tm.descripcion, imagen,tipo_mascota
        FROM mascotas 
        INNER JOIN tipos_mascotas AS tm ON mascotas.tipo_mascota = tm.id_tipo
        WHERE estatus_id != '3'";

if ($filtro_tipo) {
    $sql .= " AND mascotas.tipo_mascota = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $filtro_tipo);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conexion->query($sql);
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Plataforma para adopción de mascotas en La Cienega">
    <title>Adopta PET Cienega - Inicio</title>
    <link rel="icon" type="image/x-icon" href="../assets/adoptapetcienega.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/adoptaindex.css" rel="stylesheet">
    <style>
        .btn-center-container {
            display: flex;
            justify-content: center;
            margin: 1.5rem 0;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            margin-right: 10px;
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .filter-container {
            margin-bottom: 2rem;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <img src="../assets/adoptapetcienega.png" alt="Logo" width="30" height="30" class="d-inline-block align-top">
                    <span>Adopta PET Cienega</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">Mascotas</a>
                        </li>
                        <li class="nav-item">
                            <a href="MiCuenta.php" class="nav-link">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a href="MisPublicaciones.php" class="nav-link">Mis publicaciones</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <span class="text-light me-3">Hola, <?php echo $nombre_usuario; ?></span>
                        <a href="../cerrar_sesion.php" class="btn btn-outline-light">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="pb-5">
        <section class="py-4 container">
            <div class="welcome-message">
                <h1 class="h3 mb-3">Adopta una mascota</h1>
                <p class="text-muted">Conectamos a las mascotas con adoptantes mediante esta plataforma.</p>
            </div>

            <div class="btn-center-container">
                <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#formularioMascota">
                    <i class="bi bi-plus-circle"></i> Publicar mascota
                </button>
            </div>

            <div class="filter-container">
                <form method="GET" class="mt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-4">
                            <select name="tipo" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas las mascotas</option>
                                <option value="1" <?php echo $filtro_tipo == 1 ? 'selected' : ''; ?>>Perros</option>
                                <option value="2" <?php echo $filtro_tipo == 2 ? 'selected' : ''; ?>>Gatos</option>
                                <option value="3" <?php echo $filtro_tipo == 3 ? 'selected' : ''; ?>>Aves</option>
                                <option value="4" <?php echo $filtro_tipo == 4 ? 'selected' : ''; ?>>Conejos</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <div class="container">
            <?php if ($result->num_rows === 0): ?>
                <div class="alert alert-info text-center">
                    No hay mascotas disponibles con los filtros seleccionados.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    <?php while ($fila = $result->fetch_assoc()):
                        $id_mascota = $fila['id_mascota'];
                        $nombre_mascota = htmlspecialchars($fila['nombre_mascota']);
                        $descripcion = htmlspecialchars($fila['descripcion']);
                        $ruta_foto = htmlspecialchars($fila['imagen'] ?? '');
                    ?>
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <?php if ($ruta_foto): ?>
                                    <div class="image-container" style="height: 250px; overflow: hidden;">
                                        <img src="<?php echo $ruta_foto; ?>" alt="<?php echo $nombre_mascota; ?>" class="img-fluid w-100 h-100 object-fit-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="image-container bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-center"><?php echo $nombre_mascota; ?></h5>
                                    <p class="card-text text-center text-muted small"><?php echo $descripcion; ?></p>
                                    <div class="mt-auto text-center">
                                        <a href="Detalle.php?id=<?php echo $id_mascota; ?>" class="btn btn-primary">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal para publicar mascota -->
        <div class="modal fade" id="formularioMascota" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalFormularioLabel">Publicar Mascota</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="PublicarMascota.php" method="post" enctype="multipart/form-data" id="mascotaForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre_mascota" class="form-label">Nombre de la Mascota*</label>
                                    <input type="text" class="form-control" id="nombre_mascota" name="nombre_mascota" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipo_mascota" class="form-label">Tipo de Mascota*</label>
                                    <select class="form-select" id="tipo_mascota" name="tipo_mascota" required>
                                        <option value="" disabled selected>Selecciona...</option>
                                        <option value="1">Perro</option>
                                        <option value="2">Gato</option>
                                        <option value="3">Ave</option>
                                        <option value="4">Conejo</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="raza" class="form-label">Raza*</label>
                                    <input type="text" class="form-control" id="raza" name="raza" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="edad" class="form-label">Edad (años)*</label>
                                    <input type="number" class="form-control" id="edad" name="edad" min="0" max="30" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="sexo" class="form-label">Sexo*</label>
                                    <select class="form-select" id="sexo" name="sexo" required>
                                        <option value="" disabled selected>Selecciona...</option>
                                        <option value="M">Macho</option>
                                        <option value="H">Hembra</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Descripción*</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="sexo" class="form-label">Municipio</label>
                                    <select class="form-select" id="municipio" name="municipio" required>
                                        <option value="" disabled selected>Selecciona...</option>
                                        <option value="1">Atotonilco el Alto</option>
                                        <option value="2">Ayotlán</option>
                                        <option value="3">Degollado</option>
                                        <option value="4">Jamay</option>
                                        <option value="5">La Barca</option>
                                        <option value="6">Ocotlán</option>
                                        <option value="7">Poncitlán</option>
                                        <option value="8">Tototlán</option>
                                        <option value="9">Zapotlán del Rey</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="direccion" class="form-label">Dirección*</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                                </div>
                                <div class="col-12">
                                    <label for="imagen" class="form-label">Imagen*</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                                    <div class="form-text">Formatos aceptados: JPG, PNG. Tamaño máximo: 5MB.</div>
                                </div>
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
    </main>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">© <?php echo date('Y'); ?> Adopta PET Cienega. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación del formulario antes de enviar
        document.getElementById('mascotaForm').addEventListener('submit', function(e) {
            const imagen = document.getElementById('imagen').files[0];
            if (imagen && imagen.size > 5 * 1024 * 1024) {
                alert('La imagen no debe exceder los 5MB');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>