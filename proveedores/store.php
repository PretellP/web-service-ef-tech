<?php

include '../conexion.php';

header('Content-Type: application/json');

try {

    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $ruc = $_POST['ruc'] ?? '';

    $stmt = $conexion->prepare(
        "INSERT INTO `proveedor` 
            (nombre, direccion, telefono, email, ruc)
            VALUES
            (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $nombre,
        $direccion,
        $telefono,
        $email,
        $ruc
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Proveedor registrado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Hubo un error al registrar el proveedor: ' . $stmt->error
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible registrar el proveedor: ' . $e->getMessage()
    ]);
}

$conexion->close();