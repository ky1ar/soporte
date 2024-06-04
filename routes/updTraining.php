<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_worker'])) {
    $id_worker = $_POST['id_worker'];
    $pre = $_POST['pre'];
    
    $sql = "UPDATE Training SET worker = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_worker, $pre);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => true));
        exit();
    }
}
$conn->close();
?>