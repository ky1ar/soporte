<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $comentarios = $_POST['comentarios'];
    
    // Asegúrate de que el archivo 'pruebas' esté presente
    if (isset($_FILES['pruebas']) && $_FILES['pruebas']['error'] === 0) {
        $pruebas = $_FILES['pruebas'];
        $fileName = $pruebas['name'];
        $tempFileName = $pruebas['tmp_name'];
        
        // Generar un nombre único para el archivo
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExt;

        // Directorio donde se almacenarán los archivos
        $uploadDir = "../uploads/invoices/";
        $pruebas_ruta = $uploadDir . $uniqueFileName;

        // Mueve el archivo cargado a la carpeta destino
        if (move_uploaded_file($tempFileName, $pruebas_ruta)) {
            // Archivo guardado correctamente
        } else {
            echo json_encode(array("success" => false, "message" => "Error al guardar el archivo"));
            exit();
        }
    } else {
        // Si no se subió un archivo, dejamos el valor de pruebas como null
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
