<?php

include '../conexion.php';

header('Content-Type: application/json');

$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el id del producto'
    ]);
    exit;
}

try {

    // -------- OBTENER PRODUCTO ---------------

    $stmt = $conexion->prepare(
        "SELECT p.*, pr.nombre AS nombre_proveedor FROM producto p
        LEFT JOIN proveedor pr
        ON pr.id = p.proveedor_id
        WHERE p.id = ?"
    );

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result_producto = $stmt->get_result();

    // ------------- OBTENER PROVEEDORES ----------------

    $result_proveedores = $conexion->query("SELECT id, nombre, ruc FROM proveedor");
    $proveedores = $result_proveedores->fetch_all(MYSQLI_ASSOC);

    if ($result_producto->num_rows > 0) {
        $product = $result_producto->fetch_assoc();

        echo json_encode([
            'success' => true,
            'message' => 'Producto encontrado',
            'product' => $product,
            'proveedores' => $proveedores
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Producto no encontrado'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible obtener el producto: ' . $e->getMessage()
    ]);
}

$stmt->close();
$conexion->close();
