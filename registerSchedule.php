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

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Training t INNER JOIN Training_Client tc ON t.client = tc.id WHERE tc.document = ? AND t.state NOT IN (0, 1)");
    $stmt->bind_param("s", $dniRUC);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existingCount = $row['count'];
    $stmt->close();

    if ($existingCount > 0) {
        $response = array("error" => "Ya existe un registro para este cliente");
        echo json_encode($response);
        exit();
    }

    $conn->begin_transaction();

    try {
        
        $sql = "INSERT INTO Training_Client (document, name, phone, email, invoice) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $dniRUC, $client, $phone, $email, $invoicePath);
        $stmt->execute();

        $newId = $stmt->insert_id;
        $stmt->close();

        $sql = "INSERT INTO Training (machine, client, training_date, schedule_id, state) VALUES (?, ?, ?, ?, 0)";
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
        $response = array("success" => "Todo correcto");
        echo json_encode($response);

    } catch (Exception $e) {
        
        $conn->rollback();
        //$response = array("error" => "Error en el registro: " . $e->getMessage());
        $response = array("error" => "Error Interno");
        echo json_encode($response);
    }
}
?>
