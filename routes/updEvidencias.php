<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $comentarios = $_POST['comentarios'];
    
    if (isset($_FILES['pruebas']) && $_FILES['pruebas']['error'] === 0) {
        $pruebas = $_FILES['pruebas'];
        $fileName = $pruebas['name'];
        $tempFileName = $pruebas['tmp_name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueCode = chr(rand(65, 90)) . rand(0, 9);
        $currentDateTime = date('YmdHis');
        $uniqueFileName =$currentDateTime . '-' . $uniqueCode . '.' . $fileExt;
        $uploadDir = "../uploads/invoices/";
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
?>
