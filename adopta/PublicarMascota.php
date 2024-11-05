<?php
require('../assets/conexionBD.php');
$conexion = obtenerConexion();
session_start();
$usuario_id = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_mascota = $_POST['nombre_mascota'];
    $tipo_mascota = $_POST['tipo_mascota'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $descripcion = $_POST['descripcion'];
    $ubicacion_actual = $_POST['ubicacion_actual'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_adopcion = date("Y-m-d H:i:s");

    $imagen = $_FILES['imagen'];
    $directorio = "fotos/";

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombre_imagen = uniqid() . "-" . basename($imagen["name"]);
    $ruta_foto = $directorio . $nombre_imagen;

    if (move_uploaded_file($imagen["tmp_name"], $ruta_foto))
        $sql = "INSERT INTO mascotas(nombre_mascota,tipo_mascota,raza,edad,sexo,descripcion,ubicacion_actual,fecha_adopcion,usuario_id,imagen) 
    values('$nombre_mascota','$tipo_mascota','$raza','$edad','$sexo','$descripcion','$ubicacion_actual','$fecha_adopcion','$usuario_id','$ruta_foto')";
    if ($conexion->query($sql) === true) {
        echo '<script>
        alert("Mascota registrado exitosamente!");
        window.location.href = "/adoptapetcienega/adopta/"; // Redirigir a la página principal
      </script>';
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Mascota</title>
    <link rel="icon" type="image/x-icon"
        href="../assets/adoptapetcienega.png" />
    <link href="../css/PublicarMascotas.css" rel="stylesheet" />
</head>

<body>
    <div class="form-container">
        <h2>Publicar Mascota</h2>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <label for="nombre_mascota">Nombre de la Mascota:</label>
            <input type="text" id="nombre_mascota" name="nombre_mascota" required>

            <label for="tipo_mascota">Tipo de Mascota:</label>
            <select id="tipo_mascota" name="tipo_mascota" required>
                <option value="1">Perro</option>
                <option value="2">Gato</option>
            </select>

            <div style="display: flex; gap: 30px; align-items: center;">
                <div>
                    <label for="raza">Raza:</label>
                    <input type="text" id="raza" name="raza" required>
                </div>
                <div>
                    <label for="edad">Edad en Años:</label>
                    <input type="number" id="edad" name="edad" min="0" required>
                </div>
                <div>
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="M">M (Macho)</option>
                        <option value="H">H (Hembra)</option>
                    </select>
                </div>
            </div>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="1.5" required></textarea>

            <label for="ubicacion_actual">Ubicación Actual:</label>
            <input type="text" id="ubicacion_actual" name="ubicacion_actual" required>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" required>

            <button type="submit">Publicar Mascota</button>
        </form>
    </div>
</body>

</html>