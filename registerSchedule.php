<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $scheduleId = $_POST['scheduleId'];
    $selectedDate = $_POST['selectedDate'];

    $dniRUC = $_POST['dniRUC'];
    $client = $_POST['client'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $machineId = $_POST['machineId'];

    $invoice = $_FILES['invoice'];
    $fileName = $invoice['name'];
    $tempFileName = $invoice['tmp_name'];

    $path = 'uploads/invoices/';
    $invoicePath = $path . $fileName;
    move_uploaded_file($tempFileName, $invoicePath);

    $sql = "INSERT INTO Training_Client (document, name, phone, email, invoice) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $dniRUC, $client, $phone, $email, $invoicePath);
    $stmt->execute();

    $newId = $stmt->insert_id;

    $sql = "INSERT INTO Training (machine, client, training_date, schedule_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $machineId, $newId, $selectedDate, $scheduleId);
    $stmt->execute();

    $stmt->close();
}
?>
