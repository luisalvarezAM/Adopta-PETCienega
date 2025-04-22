<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('assets/conexionBD.php');
    $conexion = obtenerConexion();
    
    // Limpiar y validar datos
    $username = $conexion->real_escape_string(trim($_POST['username']));
    $password = md5(trim($_POST['password']));
    
    // Consulta segura
    $sql = "SELECT id_usuario, nombre_completo, tipo_usuario FROM usuarios 
            WHERE username = '$username' AND contraseña = '$password'";
    $result = $conexion->query($sql);

    if ($result->num_rows == 1) {
        $fila = $result->fetch_assoc();
        session_start();
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['nombre_usuario'] = $fila['nombre_completo'];
        $_SESSION['tipo_usuario'] = $fila['tipo_usuario'];
        
        // Preparar respuesta JSON
        $response = [
            'success' => true,
            'userType' => $fila['tipo_usuario'],
            'message' => 'Inicio de sesión exitoso'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }
    
    $conexion->close();
    
    // Enviar respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>