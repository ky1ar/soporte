<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trainingId'])) {
    try {
        $trainingId = $_POST['trainingId'];
        $response = [];

        $days = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
        $months = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre",  "noviembre", "diciembre");

        $sql_training =
        "SELECT
        t.worker as t_worker,
        t.training_state as t_state,
        t.training_date as t_date,
        t.training_start as t_start,
        t.name as c_name,
        t.phone as c_phone,
        t.document as c_document,
        t.email as c_email,
        t.meet as t_meet,
        t.invoice as t_invoice,
        m.model as m_model,
        m.slug as m_slug,
        w.name as w_name
        FROM Training t
        INNER JOIN Machine m ON t.machine = m.id
        INNER JOIN Users w ON t.worker = w.id
        WHERE t.id = ?";

        $stmt_training = $conn->prepare($sql_training);
        $stmt_training->bind_param("i", $trainingId);
        $stmt_training->execute();
        $result = $stmt_training->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $sqlTrainingCount = 
            "SELECT 
                model,
                document,
                COUNT(*) AS t_count
            FROM Training t 
            INNER JOIN Machine m ON t.machine = m.id
            WHERE t.document = ?
            AND m.slug = ?
            AND t.training_state = 2";

            $trainingCount = $conn->prepare($sqlTrainingCount);
            $trainingCount->bind_param("ss", $row['c_document'], $row['m_slug']);
            $trainingCount->execute();
            $trainingCountResult = $trainingCount->get_result();
            $trainingCountRow = $trainingCountResult->fetch_assoc();

            $selectedDate = new DateTime($row['t_date']);
            $month = $selectedDate->format('n');
            $dayName = $days[$selectedDate->format('w')];
            $day = $selectedDate->format('j');

            $response['success'] = [
                'day' => $day,
                'count' => $trainingCountRow['t_count'],
                'date' => $row['t_date'],
                'dayName' => $dayName,
                'month' => $months[$month - 1],
                'schedule' => substr($row['t_start'], 0, 5),
                'name' => $row['c_name'],
                'phone' => $row['c_phone'],
                'document' => $row['c_document'],
                'email' => $row['c_email'],
                'model' => $row['m_model'],
                'slug' => $row['m_slug'],
                'worker' => $row['w_name'],
                'id_worker' => $row['t_worker'],
                't_state' => $row['t_state'],
                'meet' => $row['t_meet'],
                'invoice' => $row['t_invoice']
            ];
        } else {
            $response['success'] = false;
        }
        echo json_encode($response);
    } catch (Exception $e) {

        $response['error'] = $e->getMessage();
        echo json_encode($response);
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn = null;
    }
} else {
    $response['error'] = 'Error Interno';
    echo json_encode($response);
}
