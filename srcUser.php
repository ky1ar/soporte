<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document'])) {
    $document = $_POST['document'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE document = ?");
    $stmt->bind_param("s", $document);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(array('ky1ar' => true, 'name' => $row['name'], 'email' => $row['email'], 'phone' => $row['phone'], 'id' => $row['id']));
    } else {
        echo json_encode(array('ky1ar' => false));
    }
} else {
    echo json_encode(array('error' => 'Documento no proporcionado'));
}

$conn->close();
?>
