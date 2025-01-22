<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $comentarios = $_POST['comentarios'];
    $pruebas = $_POST['pruebas'];

    // Si pruebas no tiene un valor válido, no se actualiza
    if ($pruebas !== 'undefined' && $pruebas !== null && !empty($pruebas)) {
        $pruebas_ruta = "../uploads/invoices/" . $pruebas;
    } else {
        // Si no hay archivo, se mantiene el valor actual de pruebas
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
