<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $comentarios = $_POST['comentarios'];

    if (isset($_FILES['pruebas']) && $_FILES['pruebas']['error'] === 0) {
        $$pruebas = $_FILES['pruebas'];
        $fileExt = pathinfo($pruebas['name'], PATHINFO_EXTENSION); 
        $currentDate = date('Ymd'); // Fecha actual en formato AAAAMMDD
        $uniqueCode = strtoupper(bin2hex(random_bytes(3))); // Generar un código único
        $uniqueFileName = $currentDate . '_' . $uniqueCode . '.' . $fileExt; // Formar el nombre con la fecha y el código
        $uploadDir = "../uploads/invoices/"; // Directorio de destino
        $pruebas_ruta = $uploadDir . $uniqueFileName;
        if (move_uploaded_file($tempFileName, $pruebas_ruta)) {
        } else {
            echo json_encode(array("success" => false, "message" => "Error al guardar el archivo"));
            exit();
        }
    } else {
        $pruebas_ruta = null;
    }

    $sql = "
        INSERT INTO Training (id, comentarios, pruebas)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        comentarios = VALUES(comentarios),
        pruebas = IFNULL(VALUES(pruebas), pruebas); 
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id, $comentarios, $pruebas_ruta);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => true));
        exit();
    } else {
        echo json_encode(array("success" => false, "message" => "No se realizaron cambios."));
        exit();
    }
}

$conn->close();
