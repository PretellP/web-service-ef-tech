<?php

include '../conexion.php';

header('Content-Type: application/json');

try {

    $result_productos = $conexion->query("SELECT p.*, pr.nombre nombre_proveedor, pr.ruc ruc_proveedor
                                        FROM producto p
                                        LEFT JOIN proveedor pr
                                        ON pr.id = p.proveedor_id");

    if ($result_productos->num_rows > 0) {
        $products = $result_productos->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'message' => 'Se encontraron productos',
            'items' => $products,
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontraron productos'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible completar la solicitud' . $e->getMessage()
    ]);
}

$conexion->close();
