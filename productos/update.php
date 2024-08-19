<?php

include '../conexion.php';

header('Content-Type: application/json');

$folder_dir = "images/products/";

$product_id = $_POST['id'] ?? null;

if (!$product_id) {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el id del producto'
    ]);
    exit;
}

// --------- OBTENER URL ACTUAL DE LA IMAGEN ----------

$stmt = $conexion->prepare("SELECT image_url FROM producto WHERE id=?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($currentImageUrl);
$stmt->fetch();
$stmt->close();

$image_url = $currentImageUrl;

// ---------- EN CASO SE SUBA UNA IMAGEN -------------

if (isset($_FILES['image']['name'])) {
    $imageName = basename($_FILES['image']['name']);
    $file_path = $folder_dir . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], '../' . $file_path)) {

        $server_url = "http://" . $_ENV['LOCAL_IP'] . '/';
        $image_url = $server_url . 'web-service-ef-tech/' .$file_path;

        $currentImageServerUrl = "../" . str_replace($server_url . "web-service-ef-tech/", "", $currentImageUrl);

        if (file_exists($currentImageServerUrl)) {
            unlink($currentImageServerUrl);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No fue posible guardar la imagen'
        ]);
        exit;
    }
}


$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$precio = $_POST['precio'] ?? 0.0;
$precio_compra = $_POST['precio_compra'] ?? 0.0;
$categoria = $_POST['categoria'] ?? '';
$stock = $_POST['stock'] ?? 0;
$proveedor_id = $_POST['proveedor_id'] ?? 0;

try {
    $query = "UPDATE producto SET 
            nombre = ?,
            descripcion = ?,
            precio = ?, 
            precio_compra = ?, 
            image_url = ?, 
            categoria = ?, 
            stock = ?, 
            proveedor_id = ? WHERE id = ?";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param(
        "ssddssiii",
        $nombre,
        $descripcion,
        $precio,
        $precio_compra,
        $categoria,
        $stock,
        $proveedor_id,
        $product_id
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Producto actualizado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No fue posible actualizar el producto: ' . $stmt->error
        ]);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'No fue posible actualizar el producto: ' . $e->getMessage()
    ]);
}

$conexion->close();
