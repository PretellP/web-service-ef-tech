<?php

include '../conexion.php';

header('Content-Type: application/json');

$product_id = $_POST['id'] ?? null;

if (!$product_id) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el id del producto'
    ]);
    exit;
}

$query = "SELECT image_url FROM producto WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($imageURL);
$stmt->fetch();
$stmt->close();

// -------- ELIMINA LA IMAGEN -----------

try {
    if ($imageURL) {
        $server_url = "http://" . $_ENV['LOCAL_IP'] . '/web-service-ef-tech/';

        $imagePath = "../" . str_replace($server_url, "", $imageURL);
        if (file_exists($imagePath) && $imagePath != '../') {
            unlink($imagePath);
        }
    }

    $stmt = $conexion->prepare("DELETE FROM producto WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Producto eliminado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No fue posible eliminar el producto: ' . $stmt->error
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible eliminar el producto: ' . $e->getMessage()
    ]);
}

$conexion->close();
