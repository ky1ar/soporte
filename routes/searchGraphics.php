<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'] . ' 00:00:00';
    $endDate = $_POST['end_date'] . ' 23:59:59';
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    // Consulta combinada que cuenta los stats y trainings
    $sql = "
        SELECT
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat_1_count,
            SUM(CASE WHEN os.stat = 9 THEN 1 ELSE 0 END) AS stat_9_count,
            COUNT(CASE WHEN t.training_state = 2 THEN 1 ELSE NULL END) AS total_trainings
        FROM Orders_Status os
        INNER JOIN Orders o ON os.orders = o.id
        INNER JOIN Users u ON o.worker = u.id
        LEFT JOIN Training t ON u.id = t.worker
        WHERE os.dates BETWEEN ? AND ? 
          AND t.training_date BETWEEN ? AND ?
    ";

    $params = [$startDate, $endDate, $startDate, $endDate];
    if (!is_null($workerId)) {
        $sql .= " AND u.id = ?";
        $params[] = $workerId;
    }

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $types = str_repeat('s', count($params));
        if ($stmt->bind_param($types, ...$params)) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();

                // Verificar si se obtuvo algún resultado y devolver la respuesta
                echo json_encode([
                    'stat1Count' => $data['stat_1_count'] ?? 0,
                    'stat9Count' => $data['stat_9_count'] ?? 0,
                    'totalTrainings' => $data['total_trainings'] ?? 0
                ]);
            } else {
                echo json_encode([ 'error' => 'Error en la ejecución de la consulta' ]);
            }
        } else {
            echo json_encode([ 'error' => 'Error al preparar la consulta' ]);
        }
        $stmt->close();
    } else {
        echo json_encode([ 'error' => 'Error al preparar la consulta' ]);
    }
} else {
    echo json_encode([ 'error' => 'Fechas no proporcionadas' ]);
}
?>
