<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders'])) {
    $orders = $_POST['orders'];
    $worker = $_POST['worker'];
    $type = $_POST['type'];
    $paid = $_POST['paid'];
    $origin = $_POST['origin'];
    
    $sql = "UPDATE Orders SET worker = ?, type = ?, paid = ?, origin = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $worker, $type, $paid, $origin, $orders);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => true));
        exit();
    }
}
$conn->close();
?>
