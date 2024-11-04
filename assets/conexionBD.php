<?php
  function obtenerConexion(){
    $sevidor = "localhost";
    $username = "root";
    $password = "";
    $b_datos = "adoptapetcienega";
    //Conectar a la base de datos
    $conexion = new mysqli($sevidor, $username, $password, $b_datos);
    //Verificar si existe algun error
    if($conexion->connect_error){
      die("Error de conexión: ".$conexion->connect_error);
    }
    return $conexion;
  }
?>