<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'] . ' 00:00:00';
    $endDate = $_POST['end_date'] . ' 23:59:59';
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    $sql = "
        SELECT
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat1,
            SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8,
            (
                SELECT COUNT(*)
                FROM Training t
                WHERE t.training_state = 2
                AND t.training_date BETWEEN ? AND ?
                " . (!is_null($workerId) ? "AND t.worker = ?" : "") . "
            ) AS totalTrainings
        FROM Orders_Status os
        INNER JOIN Orders o ON os.orders = o.id
        INNER JOIN Users u ON o.worker = u.id
        WHERE os.dates BETWEEN ? AND ?
        " . (!is_null($workerId) ? "AND u.id = ?" : "") . "
    ";

    // Configuramos los parámetros según si se incluye el workerId
    $params = [$startDate, $endDate, $startDate, $endDate];
    if (!is_null($workerId)) {
        $params[] = $workerId; // Para la subconsulta (Training)
        $params[] = $workerId; // Para el filtro principal
    }

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params));

    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                echo json_encode([
                    'stat1Count' => $data['stat1'],
                    'stat8Count' => $data['stat8'],
                    'totalTrainings' => $data['totalTrainings']
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
