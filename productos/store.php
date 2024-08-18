<?php

include '../conexion.php';

header('Content-Type: application/json');

$folder_dir = "images/products/";

try {
    // ------ OBTENER LOS PARAMETROS ENVIADOS ----------

    $nombre = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $precio_compra = $_POST['precio_compra'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    $stock = $_POST['stock'] ?? null;
    $proveedor_id = $_POST['proveedor_id'] ?? null;

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
