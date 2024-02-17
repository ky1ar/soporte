<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

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

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM Training t INNER JOIN Training_Client tc ON t.client = tc.id WHERE tc.document = ? AND t.state IN (0, 1)");
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
        // Bloquear la fila en la tabla Calendar para evitar conflictos de concurrencia
        $stmt = $conn->prepare("SELECT * FROM Calendar WHERE calendar_date = ? FOR UPDATE");
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $stmt->close();

        // Realizar la verificación para asegurarse de que la fila no haya sido bloqueada por otro cliente
        $stmt = $conn->prepare("SELECT state FROM Calendar WHERE calendar_date = ?");
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $state = $row['state'];
        $stmt->close();

        if ($state == 1) {
            // Si el estado es 1, significa que ya está reservado por otro cliente
            $response['error'] = "Este horario ya ha sido reservado por otro cliente";
            echo json_encode($response);
            exit();
        }

        // Realizar las inserciones en Training_Client y Training
        $stmt = $conn->prepare("INSERT INTO Training_Client (document, name, phone, email, invoice) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $dniRUC, $client, $phone, $email, $invoicePath);
        $stmt->execute();
        $newId = $stmt->insert_id;
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO Training (machine, client, training_date, schedule_id, state) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("iisi", $machineId, $newId, $selectedDate, $scheduleId);
        $stmt->execute();
        $stmt->close();

        // Actualizar el estado de Calendar a 0 si count es 1
        if ($count == 1) {
            $stmt = $conn->prepare("UPDATE Calendar SET state = 0 WHERE calendar_date = ?");
            $stmt->bind_param("s", $selectedDate);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();

        $response['success'] = '<div id="successSchedule">Todo Correcto</div>';
        echo json_encode($response);

    } catch (Exception $e) {
        
        $conn->rollback();
        $response['error'] = 'Error Interno';
        echo json_encode($response);
    }
}
?>
