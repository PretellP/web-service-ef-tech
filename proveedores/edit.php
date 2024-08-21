<?php

include '../conexion.php';

header('Content-Type: application/json');

$proveedor_id = $_GET['id'] ?? 0;

if (!$proveedor_id) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el id del producto'
    ]);
    exit;
}

try {

    // -------- OBTENER PROVEEDOR ---------------
    $stmt = $conexion->prepare(
        "SELECT * FROM proveedor WHERE id = ?"
    );

    $stmt->bind_param("i", $proveedor_id);
    $stmt->execute();

    $result_proveedor = $stmt->get_result();

    if ($result_proveedor->num_rows > 0) {
        $proveedor = $result_proveedor->fetch_assoc();

        echo json_encode([
            'success' => true,
            'message' => 'Proveedor encontrado',
            'proveedor' => $proveedor
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Proveedor no encontrado'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible obtener el proveedor: ' . $e->getMessage()
    ]);
}

$stmt->close();
$conexion->close();
