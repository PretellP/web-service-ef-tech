<?php

include '../conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'));

if (isset($data->usuario) && isset($data->password)) {
    
    $usuario = $data->usuario;
    $password = $data->password;

    $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE usuario=? AND `password`=?");
    $sentencia->bind_param('ss', $usuario, $password);
    $sentencia->execute();

    $resultado = $sentencia->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "usuario" => $fila
        ]);
    } else {
        echo json_encode([
            "success" => false
        ]);
    };

    $sentencia->close();
}
else {
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
}

$conexion->close();









