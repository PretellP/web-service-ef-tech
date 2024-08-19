<?php

include '../conexion.php';

header('Content-Type: application/json');

$folder_dir = "images/products/";

try {
    // ------ OBTENER LOS PARAMETROS ENVIADOS ----------

    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0.0;
    $precio_compra = $_POST['precio_compra'] ?? 0.0;
    $categoria = $_POST['categoria'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $proveedor_id = $_POST['proveedor_id'] ?? 0;

    $image_url = Null;

    // ----------- VERIFICA SI SE ENVIÓ UN ARCHIVO ---------
    if (isset($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $file_path = $folder_dir . $imageName;

        // ----------- GUARDA LA IMAGEN EN LA CARPETA IMAGES -----------
        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $file_path)) {

            $image_url = "http://" . $_ENV['LOCAL_IP'] . '/web-service-ef-tech/' . $file_path;
        }
    }

    $stmt = $conexion->prepare(
        "INSERT INTO `producto` 
            (nombre, descripcion, precio, precio_compra, image_url, categoria, stock, proveedor_id)
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssddssii",
        $nombre,
        $descripcion,
        $precio,
        $precio_compra,
        $image_url,
        $categoria,
        $stock,
        $proveedor_id
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Producto registrado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Hubo un error al registrar el producto: ' . $stmt->error
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible registrar el producto: ' . $e->getMessage()
    ]);
}

$conexion->close();
