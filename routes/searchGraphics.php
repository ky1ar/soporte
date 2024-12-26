<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $startDate .= ' 00:00:00';
    $endDate .= ' 23:59:59';

    $sql = "
            SELECT
                SUM(CASE WHEN os.state = 9 THEN 1 ELSE 0 END) AS count_state_9,
                SUM(CASE WHEN os.state <> 9 THEN 1 ELSE 0 END) AS count_not_state_9
            FROM Orders os
            WHERE os.dates BETWEEN ? AND ?
        ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $startDate, $endDate);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                $stat1Count = $data['count_state_9'];
                $stat9Count = $data['count_not_state_9'];

                echo json_encode([
                    'stat1Count' => $stat1Count,
                    'stat9Count' => $stat9Count
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron resultados'
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'Error en la ejecución de la consulta'
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'error' => 'Error al preparar la consulta'
        ]);
    }
} else {
    echo json_encode([
        'error' => 'Fechas no proporcionadas'
    ]);
}
