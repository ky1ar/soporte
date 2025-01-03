<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    // Asegurando el formato de las fechas
    $startDate .= ' 00:00:00';
    $endDate .= ' 23:59:59';

    // Consulta para contar los stats y los entrenamientos
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

    $params = array($startDate, $endDate, $startDate, $endDate);
    if (!is_null($workerId)) {
        $sql .= " AND u.id = ?";
        $params[] = $workerId;
    }

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params));  // 's' para cadenas de texto

    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                $stat1Count = $data['stat_1_count'];
                $stat9Count = $data['stat_9_count'];
                $totalTrainings = $data['total_trainings'];

                echo json_encode([
                    'stat1Count' => $stat1Count,
                    'stat9Count' => $stat9Count,
                    'totalTrainings' => $totalTrainings
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
?>
