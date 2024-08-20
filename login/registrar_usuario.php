<?php

include '../conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'));

if (isset($data->usuario) && isset($data->password) && isset($data->nombres) && isset($data->apellidos)) {

    $usuario = $data->usuario;
    $password = $data->password;
    $nombres = $data->nombres;
    $apellidos = $data->apellidos;

    // Verificar si el usuario ya existe
    $checkUser = $conexion->prepare("SELECT * FROM usuario WHERE usuario=?");
    $checkUser->bind_param('s', $usuario);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "El nombre de usuario ya está registrado"
        ]);
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sentencia = $conexion->prepare("INSERT INTO usuario (usuario, `password`, nombres, apellidos) VALUES (?, ?, ?, ?)");
        $sentencia->bind_param('ssss', $usuario, $password, $nombres, $apellidos);

        if ($sentencia->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Usuario registrado exitosamente"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al registrar el usuario"
            ]);
        }

        $sentencia->close();
    }

    $checkUser->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
}

$conexion->close();
?>
