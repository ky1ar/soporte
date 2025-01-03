<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    $startDate .= ' 00:00:00';
    $endDate .= ' 23:59:59';

    $sql = "
        SELECT
               stat_counts.stat_1_count,
               stat_counts.stat_9_count,
               training_counts.total_trainings
        FROM Users u
        -- Subconsulta para los conteos de Orders_Status (stat_1_count y stat_9_count)
        INNER JOIN (
            SELECT o.worker AS user_id,
                   SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat_1_count,
                   SUM(CASE WHEN os.stat = 9 THEN 1 ELSE 0 END) AS stat_9_count
            FROM Orders_Status os
            INNER JOIN Orders o ON os.orders = o.id
            WHERE os.dates BETWEEN ? AND ?
            GROUP BY o.worker
        ) AS stat_counts ON stat_counts.user_id = u.id
        -- Subconsulta para los conteos de Training (total_trainings)
        LEFT JOIN (
            SELECT t.worker AS user_id,
                   COUNT(CASE WHEN t.training_state = 2 THEN 1 ELSE NULL END) AS total_trainings
            FROM Training t
            WHERE t.training_date BETWEEN ? AND ?
              AND t.training_state = 2
            GROUP BY t.worker
        ) AS training_counts ON training_counts.user_id = u.id
    ";

    // Parámetros para las fechas
    $params = array($startDate, $endDate, $startDate, $endDate);

    // Si se proporciona worker_id, lo agregamos a la consulta
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
                // Solo enviar los stats y total_trainings en la respuesta
                echo json_encode([
                    'stat1Count' => $data['stat_1_count'],
                    'stat9Count' => $data['stat_9_count'],
                    'totalTrainings' => $data['total_trainings']
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
