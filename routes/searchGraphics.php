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
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat_1_count,
            SUM(CASE WHEN os.stat = 9 THEN 1 ELSE 0 END) AS stat_9_count
        FROM Orders_Status os
        INNER JOIN Orders o ON os.orders = o.id
        INNER JOIN Users u ON o.worker = u.id
        WHERE os.dates BETWEEN ? AND ?
    ";

    $params = array($startDate, $endDate);
    if (!is_null($workerId)) {
        $sql .= " AND u.id = ?";
        $params[] = $workerId;
    }

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params)); 

    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                $stat1Count = $data['stat_1_count'];
                $stat9Count = $data['stat_9_count'];

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
?>