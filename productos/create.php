<?php

include '../conexion.php';

header('Content-Type: application/json');

try {
    // ------------- OBTENER PROVEEDORES ----------------

    $result_proveedores = $conexion->query("SELECT id, nombre, ruc FROM proveedor");

    if ($result_proveedores->num_rows > 0) {
        $proveedores = $result_proveedores->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'message' => 'Proveedores encontrados',
            'proveedores' => $proveedores
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontraron proveedores'
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible completar la solicitud' . $e->getMessage()
    ]);
}

$conexion->close();
