<?php
// Conexión a la base de datos
require_once '../assets/conexionBD.php';
$conexion = obtenerConexion();

$id_admin = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT id_admin, username, nombre_completo, correo, telefono, fec_registro, img_perfil FROM administradores WHERE id_admin = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$conexion->close();

if (!$admin) {
    header("Location: administradores.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <a href="administradores.php" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left"></i> Volver</a>
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Perfil del Administrador</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-4 text-center">
                <img src="<?= htmlspecialchars($admin['img_perfil'] ?? '../assets/img/usuarios/default.png') ?>" alt="Foto" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                <h5 class="mt-3"><?= htmlspecialchars($admin['nombre_completo']) ?></h5>
                <p class="text-muted">@<?= htmlspecialchars($admin['username']) ?></p>
            </div>
            <div class="col-md-8">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Correo:</strong> <?= htmlspecialchars($admin['correo']) ?></li>
                    <li class="list-group-item"><strong>Teléfono:</strong> <?= htmlspecialchars($admin['telefono'] ?? 'No especificado') ?></li>
                    <li class="list-group-item"><strong>Miembro desde:</strong> <?= date('d M Y', strtotime($admin['fec_registro'])) ?></li>
                </ul>
                <div class="mt-3">
                    <a href="editarPerfilAdmin.php?id=<?= $admin['id_admin'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Editar Perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
