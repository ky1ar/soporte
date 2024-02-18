<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    $schedule = $_POST['schedule'];
    $date = $_POST['date'];
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

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Training WHERE document = ? AND training_state IN (0, 1)");
    $stmt->bind_param("s", $dniRUC);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existingCount = $row['count'];
    $stmt->close();

    if ($existingCount > 0) {
        $response['error'] = "Ya existe una capacitación registrada para este número de documento";
        echo json_encode($response);
        exit();
    }

    $conn->begin_transaction();

    try {
        
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Training WHERE training_date = ? AND training_start = ?");
        $stmt->bind_param("ss", $date, $schedule);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $existingCount = $row['count'];
        $stmt->close();

        if ($existingCount > 0) {
            $response['error'] = "Ya existe una capacitación registrada para esta fecha y horario";
            echo json_encode($response);
            exit();
        }

        $sql = "INSERT INTO Training (machine, document, name, phone, email, invoice, training_date, training_start, training_state) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $machineId, $dniRUC, $client, $phone, $email, $invoicePath, $date, $schedule);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        if ($count == 1) {
            $sql = "UPDATE Calendar SET state = 0 WHERE calendar_date = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $date);
            $stmt->execute();
            $stmt->close();
        }
        $response['success'] = '<div id="successSchedule">Todo Correcto</div>';
        echo json_encode($response);

    } catch (Exception $e) {
        
        $conn->rollback();
        //$response = array("error" => "Error en el registro: " . $e->getMessage());
        $response['error'] = 'Error Interno';
        echo json_encode($response);
    }
}
?>
