<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    $startDate .= ' 00:00:00';
    $endDate .= ' 23:59:59';

    // Consulta para obtener los conteos de los estados 1 y 9
    $sql = "SELECT
            SUM(CASE WHEN o.state = 1 THEN 1 ELSE 0 END) AS state_1_count,
            SUM(CASE WHEN o.state = 9 THEN 1 ELSE 0 END) AS state_9_count
        FROM Orders o
        INNER JOIN Users u ON o.worker = u.id
        WHERE o.dates BETWEEN ? AND ?";

    $params = array($startDate, $endDate);
    if (!is_null($workerId)) {
        $sql .= " AND u.id = ?";  // Filtramos si se recibe un worker_id
        $params[] = $workerId;
    }

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params));

    // Ejecutar la consulta para obtener los estados 1 y 9
    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            // Obtener el resultado de los conteos de los estados
            if ($data) {
                $stat1Count = $data['stat_1_count'];
                $stat9Count = $data['stat_9_count'];

                // Consulta para obtener el conteo de las filas en la tabla Training
                $trainingSql = "
                    SELECT COUNT(*) AS num_rows
                    FROM Training t
                    INNER JOIN Users u ON t.worker = u.id
                    WHERE t.training_state = 2
                    AND t.training_date BETWEEN ? AND ?
                ";

                // Si se recibe un worker_id, añadimos el filtro correspondiente
                if (!is_null($workerId)) {
                    $trainingSql .= " AND t.worker = ?";
                    $paramsTraining = array($startDate, $endDate, $workerId);
                    $typesTraining = 'sss';  // El workerId es un string
                } else {
                    // Si no hay worker_id, no se filtra por worker
                    $paramsTraining = array($startDate, $endDate);
                    $typesTraining = 'ss';  // Solo las fechas
                }

                $trainingStmt = $conn->prepare($trainingSql);
                $trainingStmt->bind_param($typesTraining, ...$paramsTraining);
                $trainingStmt->execute();
                $trainingResult = $trainingStmt->get_result();
                $trainingData = $trainingResult->fetch_assoc();

                $totalTrainings = $trainingData ? $trainingData['num_rows'] : 0;

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
