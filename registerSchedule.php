<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $scheduleId = $_POST['scheduleId'];
    $selectedDate = $_POST['selectedDate'];
    $count = $_POST['count'];

    $dniRUC = $_POST['dniRUC'];
    $client = $_POST['client'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $machineId = $_POST['machineId'];

    $invoice = $_FILES['invoice'];
    $fileName = $invoice['name'];
    $tempFileName = $invoice['tmp_name'];

    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExt;

    $path = 'uploads/invoices/';
    $invoicePath = $path . $uniqueFileName;
    move_uploaded_file($tempFileName, $invoicePath);

    $conn->begin_transaction();

    try {
        
        $sql = "INSERT INTO Training_Client (document, name, phone, email, invoice) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $dniRUC, $client, $phone, $email, $invoicePath);
        $stmt->execute();

        $newId = $stmt->insert_id;
        $stmt->close();

        $sql = "INSERT INTO Training (machine, client, training_date, schedule_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $machineId, $newId, $selectedDate, $scheduleId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        if ($count == 1) {
            $sql = "UPDATE Calendar SET state = 0 WHERE calendar_date = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selectedDate);
            $stmt->execute();
            $stmt->close();
        }
    } catch (Exception $e) {
        
        $conn->rollback();
        echo "Error en el registro: " . $e->getMessage();
    }
}
?>
