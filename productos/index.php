<?php

include '../conexion.php';

header('Content-Type: application/json');

$result = $conexion->query("SELECT * FROM producto");

try {
    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'success' => true,
            'message' => 'Se encontraron productos',
            'items' => $products,
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'No hay productos'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible obtener los productos: ' . $e->getMessage()
    ]);
}

$conexion->close();