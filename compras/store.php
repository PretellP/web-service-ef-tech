<?php

include '../conexion.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$usuario_id = $input['usuario_id'] ?? null;
$fecha = $input['fecha_hora'] ?? null;
$detalles = $input['detalles'] ?? [];

if (!$usuario_id || !$fecha || empty($detalles)) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos no válidos'
    ]);
}

// ----- INICIA LA TRANSACCION A LA BD ---------
$conexion->begin_transaction();

try {

    $stmt = $conexion->prepare("INSERT INTO compra (usuario_id, fecha) VALUES (?, ?)");
    $stmt->bind_param("is", $usuario_id, $fecha);
    $stmt->execute();

    $compra_id = $conexion->insert_id;

    $stmt_detalles = $conexion->prepare("INSERT INTO detallecompra 
                (compra_id, producto_id, cantidad, precio_unitario)
                VALUES(?,?,?,?)");

    $stmt_update_stock = $conexion->prepare("UPDATE producto SET
                                            stock = stock + ?
                                            WHERE id = ?");

    foreach ($detalles as $detalle) {
        $producto_id = $detalle['producto_id'];
        $cantidad = $detalle['cantidad'];
        $precio_unitario = $detalle['precio_unitario'];

        $stmt_detalles->bind_param("iiid", $compra_id, $producto_id, $cantidad, $precio_unitario);

        if ($stmt_detalles->execute()) {
            $stmt_update_stock->bind_param('ii', $cantidad, $producto_id);
            $stmt_update_stock->execute();
        }
    }

    $conexion->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Compra registrada exitosamente'
    ]);
} catch (Exception $e) {
    // ----------- REVIERTE LA TRANSACCIÓN EN CASO DE ERROR ------------
    $conexion->rollback();

    echo json_encode([
        'success' => false,
        'message' => 'No fue posible registrar la compra: ' . $e->getMessage()
    ]);
} 

$stmt->close();
$stmt_detalles->close();
$stmt_update_stock->close();
$conexion->close();
