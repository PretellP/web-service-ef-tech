<?php

include '../conexion.php';

header('Content-Type: application/json');

$proveedor_id = $_POST['id'] ?? 0;

if (!$proveedor_id) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el id del producto'
    ]);
    exit;
}

try {

    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $ruc = $_POST['ruc'] ?? '';
    
    $stmt = $conexion->prepare("UPDATE proveedor SET  
                                nombre = ?,
                                direccion = ?,
                                telefono = ?,
                                email = ?,
                                ruc = ?
                                WHERE id = ?");
    $stmt->bind_param(
        "sssssi",
        $nombre,
        $direccion,
        $telefono,
        $email,
        $ruc,
        $proveedor_id
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Proveedor actualizado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No fue posible actualizar el proveedor: ' . $stmt->error
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
echo json_encode([
        'success' => false,
        'message' => 'No fue posible actualizar el proveedor: ' . $e->getMessage()
    ]);
}


$conexion->close();