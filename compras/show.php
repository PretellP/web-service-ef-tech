<?php

include '../conexion.php';

header('Content-Type: application/json');

$compra_id = $_GET['id'] ?? null;

if (!$compra_id) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el id de la compra'
    ]);
    exit;
}

try {

    $stmt_compra = $conexion->prepare("SELECT c.*, SUM(dc.cantidad) AS total_productos,
                                        SUM(dc.cantidad * dc.precio_unitario) AS total_costo,
                                        u.nombres AS usuario_nombre, u.apellidos AS usuario_apellido
                                        FROM compra c
                                        LEFT JOIN detallecompra dc 
                                        ON dc.compra_id = c.id
                                        LEFT JOIN usuario u
                                        ON u.id = c.usuario_id
                                        WHERE c.id = ?
                                        GROUP BY c.id");

    $stmt_compra->bind_param("i", $compra_id);
    $stmt_compra->execute();

    $result_compra = $stmt_compra->get_result();

    if ($result_compra->num_rows > 0) {

        $compra = $result_compra->fetch_assoc();

        $stmt_detalles = $conexion->prepare("SELECT dc.*, (dc.cantidad * dc.precio_unitario) AS monto_total, 
                                            p.nombre, p.categoria, p.image_url 
                                            FROM detallecompra dc
                                            LEFT JOIN producto p
                                            ON p.id = dc.producto_id
                                            WHERE dc.compra_id = ?");

        $stmt_detalles->bind_param("i", $compra_id);
        $stmt_detalles->execute();

        $result_detalles = $stmt_detalles->get_result();

        $detalles = $result_detalles->fetch_all(MYSQLI_ASSOC);


        echo json_encode([
            "success" => true,
            "message" => "Compra encontrada",
            "items" => [
                "compra" => $compra,
                "detalles" => $detalles
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Compra no encontrada'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible obtener el producto: ' . $e->getMessage()
    ]);
}


$conexion->close();
