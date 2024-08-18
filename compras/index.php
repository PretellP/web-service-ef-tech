<?php

include '../conexion.php';

header('Content-Type: application/json');

$query = "SELECT c.*, SUM(dc.cantidad) AS total_productos,
        SUM(dc.cantidad * dc.precio_unitario) AS total_costo,
        u.nombres AS usuario_nombre
        FROM compra c
        LEFT JOIN detallecompra dc 
        ON dc.compra_id = c.id
        LEFT JOIN usuario u
        ON u.id = c.usuario_id
        GROUP BY c.id";

$result = $conexion->query($query);

if ($result->num_rows > 0) {

    $compras = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Se encontraron registros',
        'compras' => $compras
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No hay compras'
    ]);
}

$conexion->close();