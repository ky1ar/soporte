<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    try {
        $conn->begin_transaction();

        $scheduleId = $_POST['scheduleId'];
        $rejectText = $_POST['rejectText'];
        $date = $_POST['date'];

        $sql = "UPDATE Training SET details = ?, training_state = 3 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta para actualizar la capacitación");
        }
        $stmt->bind_param("si", $rejectText, $scheduleId);
        $stmt->execute();

        $result = $stmt->affected_rows;
        $stmt->close();
        if ($result <= 0) {
            throw new Exception("No se pudo actualizar el registro");
        }

        $sql = "UPDATE Calendar SET state = 1 WHERE calendar_date = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta para actualizar el calendario");
        }
        $stmt->bind_param("s", $date);
        $stmt->execute();

        $result = $stmt->affected_rows;
        $stmt->close();
        if ($result <= 0) {
            throw new Exception("No se pudo actualizar el calendario");
        }
        $conn->commit();

        $response['success'] = true;
        echo json_encode($response);

    } catch (Exception $e) {
        $conn->rollback();
        
        $response['error'] = $e->getMessage();
        echo json_encode($response);

    } finally {
        $conn->close();
    }
} else {
    $response['error'] = "Solicitud no válida";
    echo json_encode($response);
}
?>
