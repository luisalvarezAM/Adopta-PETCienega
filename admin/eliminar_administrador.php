<?php
session_start();
require_once '../assets/conexionBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $conexion = obtenerConexion();
    $id_usuario = (int)$_POST['id_usuario'];
    
    try {
        
        $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE id_usuario = ?");
        $check->bind_param("i", $id_usuario);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception('El usuario no existe');
        }
        
       
        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        
        if ($stmt->execute()) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Usuario eliminado correctamente'
            ];
        } else {
            throw new Exception('Error al ejecutar la eliminación');
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => $e->getMessage()
        ];
    } finally {
        $conexion->close();
    }
} else {
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => 'Solicitud inválida'
    ];
}

header('Location: adminitradores.php');
exit();
?>