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
    // --------- Verificar si el proveedor tiene productos ----------
    $stmt_producto = $conexion->prepare("SELECT id FROM producto WHERE proveedor_id = ?");
    $stmt_producto->bind_param("i", $proveedor_id);

    $stmt_producto->execute();
    $stmt_producto->bind_result($product_id);
    $stmt_producto->fetch();
    $stmt_producto->close();
    
    if (!$product_id) {

        $stmt_delete = $conexion->prepare("DELETE FROM proveedor WHERE id = ?");
        $stmt_delete->bind_param("i", $proveedor_id);

        $stmt_delete->execute();
        $stmt_delete->close();

        echo json_encode([
            "success" => true,
            "message" => "Proveedor eliminado exitosamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Este proveedor tiene productos relacionados, no puede eliminarse"
        ]);
    }

} catch (Exception $e) {
echo json_encode([
        'success' => false,
        'message' => 'No fue posible actualizar el proveedor: ' . $e->getMessage()
    ]);
}

$conexion->close();