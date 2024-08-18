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

$stmt = $conexion->prepare("SELECT * FROM producto WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

try {
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'message' => 'Producto encontrado',
            'item' => $product
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

$conexion->close();