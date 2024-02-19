<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    $scheduleId = $_POST['scheduleId'];
    $trainingWorker = $_POST['trainingWorker'];
    $meet = $_POST['meet'];

    $sql = "UPDATE Training SET worker = ?, meet = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $trainingWorker, $meet, $scheduleId);
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
