<?php
session_start();

$varsesion = $_SESSION['id_usuario'];
if ($varsesion == null || $varsesion == '') {
    header("location: /adoptapetcienega/");
    die();
}

require('../assets/conexionBD.php'); // Conexión a la base de datos
$conexion = obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_interesado = $_SESSION['id_usuario'];
    $id_mascota = $_POST['id_mascota'];

    // Verifica si ya existe el interés
    $stmt = $conexion->prepare("SELECT * FROM interesados WHERE id_interesado = ? AND id_mascota = ?");
    $stmt->bind_param("ii", $id_interesado, $id_mascota);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        // Inserta nuevo interés
        $stmt = $conexion->prepare("INSERT INTO interesados (id_interesado, id_mascota) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_interesado, $id_mascota);
        $stmt->execute();
        header("Location: detalle.php?id=" . $id_mascota);
    } else {
        echo "Ya estás registrado como interesado.";
        header("Location: detalle.php?id=" . $id_mascota);
exit;
    }

    $stmt->close();
    $conexion->close();
}
?>

