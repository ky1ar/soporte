<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dniRucVal'])) {
    $dniRucVal = $_POST['dniRucVal'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE document = ?");
    $stmt->bind_param("s", $dniRucVal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(
            array(
                'ky1ar' => true, 
                'client' => $row['name'], 
                'email' => $row['email'], 
                'phone' => $row['phone'], 
                'clientId' => $row['id']
            )
        );
    } else {
        echo json_encode(array('ky1ar' => false));
    }
} else {
    echo json_encode(array('error' => 'Documento no proporcionado'));
}

$conn->close();
?>
