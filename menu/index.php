<?php

include '../conexion.php';

header('Content-Type: application/json');

try {
    
    $r_productos = $conexion->query("SELECT COUNT(*) AS count FROM producto");
    $count_productos = $r_productos->fetch_assoc()['count'];

    $r_proveedores = $conexion->query("SELECT COUNT(*) AS count FROM proveedor");
    $count_proveedores = $r_proveedores->fetch_assoc()['count'];

    $r_usuarios = $conexion->query("SELECT COUNT(*) AS count FROM usuario");
    $count_usuarios = $r_usuarios->fetch_assoc()['count'];

    $r_compras = $conexion->query("SELECT COUNT(*) AS count FROM compra");
    $count_compras = $r_compras->fetch_assoc()['count'];

    $counts = [
        'productos' => $count_productos,
        'proveedores' => $count_proveedores,
        'usuarios' => $count_usuarios,
        'compras' => $count_compras
    ];

    echo json_encode([
        'success' => true,
        'counts' => $counts
    ]);


} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo completar la solicitud: ' . $e->getMessage()
    ]);
}

$conexion->close();