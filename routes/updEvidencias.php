<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $comentarios = $_POST['comentarios'];
    $pruebas = $_FILES['pruebas'];

    // Verifica si se proporcionó un archivo
    if ($pruebas['error'] === 0) {
        $fileName = $pruebas['name'];
        $tempFileName = $pruebas['tmp_name'];
        
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExt;

        // Define el directorio donde se guardarán los archivos
        $uploadDir = "../uploads/invoices/";
        $pruebas_ruta = $uploadDir . $uniqueFileName;

        // Mueve el archivo a la carpeta de destino
        if (move_uploaded_file($tempFileName, $pruebas_ruta)) {
            // Si el archivo se movió correctamente, proceder con la inserción en la base de datos
        } else {
            echo json_encode(array("success" => false, "message" => "Error al guardar el archivo"));
            exit();
        }
    } else {
        // Si no se proporciona un archivo, no se cambia el valor de pruebas
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
