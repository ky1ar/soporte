<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    $scheduleId = $_POST['scheduleId'];
    $rejectText = $_POST['rejectText'];

    $sql = "UPDATE Training SET details = ?, training_state = 3 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $rejectText, $scheduleId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        echo json_encode($response);
    } else {
        $response['error'] = 'Error Interno';
        echo json_encode($response);
    }
    $stmt->close();
    exit();
}
?>
