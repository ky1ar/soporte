<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders'])) {
    $orders = $_POST['orders'];

    $sql = "SELECT id FROM Orders WHERE number = '$orders'";
    $result = $conn->query($sql);

    $existe = ($result->num_rows > 0) ? 'existe' : false;

    // Devolver la respuesta en formato JSON
    echo json_encode(array('response' => $existe));
}
$conn->close();
?>
