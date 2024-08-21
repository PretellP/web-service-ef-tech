<?php

include '../conexion.php';

header('Content-Type: application/json');

$result = $conexion->query("SELECT * FROM proveedor");

try {
    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'success' => true,
            'message' => 'Se encontraron proveedores',
            'items' => $products,
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No hay proveedores'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible obtener proveedores: ' . $e->getMessage()
    ]);
}


$conexion->close();