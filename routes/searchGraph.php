
<?php

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data['start_date']) && isset($data['end_date'])) {
    $startDate = $data['start_date'] . ' 00:00:00';
    $endDate = $data['end_date'] . ' 23:59:59';
    $metric = isset($data['metric']) ? $data['metric'] : '';

    // Subconsulta de trabajadores válidos
    $validWorkersSubquery = "
        SELECT id 
        FROM Users 
        WHERE levels IN (2, 3) AND id != 203
    ";

    // Para la métrica 'stat8'
    if ($metric === 'stat8') {
        $sql = "SELECT u.id, u.name, SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8
                FROM Users u
                LEFT JOIN Orders o ON o.worker = u.id
                LEFT JOIN Orders_Status os ON os.orders = o.id
                WHERE os.dates BETWEEN ? AND ? 
                AND u.id IN ($validWorkersSubquery)
                GROUP BY u.id, u.name";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Respuesta para la métrica 'stat8'
        $response = [
            'labels' => [],
            'data' => []
        ];

        foreach ($data as $row) {
            $response['labels'][] = $row['name']; // Nombre del trabajador
            $response['data'][] = $row['stat8']; // Total de registros con stat = 8
        }

        echo json_encode($response);
        exit;
    }
    
    // Para la métrica 'totalTrainings'
    elseif ($metric === 'totalTrainings') {
        $sql = "SELECT u.id, u.name, IFNULL(t.totalTrainings, 0) AS totalTrainings
                FROM Users u
                LEFT JOIN (
                    SELECT t.worker, COUNT(*) AS totalTrainings
                    FROM Training t
                    WHERE t.training_state = 2
                    AND t.training_date BETWEEN ? AND ?
                    GROUP BY t.worker
                ) t ON t.worker = u.id
                WHERE u.id IN ($validWorkersSubquery)";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Respuesta para la métrica 'totalTrainings'
        $response = [
            'labels' => [],
            'data' => []
        ];

        foreach ($data as $row) {
            $response['labels'][] = $row['name']; // Nombre del trabajador
            $response['data'][] = $row['totalTrainings']; // Total de capacitaciones finalizadas
        }

        echo json_encode($response);
        exit;
    }

    // Para la métrica 'trabajo_realizado'
    elseif ($metric === 'trabajo_realizado') {
        $sql = "SELECT u.id, u.name, 
                    SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8,
                    IFNULL(t.totalTrainings, 0) AS totalTrainings,
                    (SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) + IFNULL(t.totalTrainings, 0)) AS trabajo_realizado
                FROM Users u
                LEFT JOIN Orders o ON o.worker = u.id
                LEFT JOIN Orders_Status os ON os.orders = o.id
                LEFT JOIN (
                    SELECT t.worker, COUNT(*) AS totalTrainings
                    FROM Training t
                    WHERE t.training_state = 2
                    AND t.training_date BETWEEN ? AND ?
                    GROUP BY t.worker
                ) t ON t.worker = u.id
                WHERE os.dates BETWEEN ? AND ? 
                AND u.id IN ($validWorkersSubquery)
                GROUP BY u.id, u.name";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Respuesta para la métrica 'trabajo_realizado'
        $response = [
            'labels' => [],
            'data' => []
        ];

        foreach ($data as $row) {
            $response['labels'][] = $row['name']; // Nombre del trabajador
            $response['data'][] = $row['trabajo_realizado']; // Total de trabajo realizado
        }

        echo json_encode($response);
        exit;
    }

    // Si no se especifica métrica, devolver todas las métricas
    else {
        $sql = "
            SELECT u.id, u.name,
                (SELECT COUNT(*) 
                 FROM Orders_Status os 
                 WHERE os.stat = 1 
                 AND os.dates BETWEEN ? AND ?
                 AND os.orders IN (SELECT id FROM Orders WHERE worker IN ($validWorkersSubquery))) AS stat1Count,
                (SELECT COUNT(*) 
                 FROM Orders_Status os 
                 WHERE os.stat = 8 
                 AND os.dates BETWEEN ? AND ?
                 AND os.orders IN (SELECT id FROM Orders WHERE worker IN ($validWorkersSubquery))) AS stat8Count,
                (SELECT COUNT(*) 
                 FROM Training t 
                 WHERE t.training_state = 2 
                 AND t.training_date BETWEEN ? AND ?
                 AND t.worker IN ($validWorkersSubquery)) AS totalTrainings";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // Respuesta para la métrica por defecto
        $response = [
            'labels' => [],
            'data' => []
        ];

        foreach ($data as $row) {
            $response['labels'][] = $row['name']; // Nombre del trabajador
            $response['data'][] = [
                'stat1' => $row['stat1Count'],
                'stat8' => $row['stat8Count'],
                'totalTrainings' => $row['totalTrainings']
            ];
        }

        echo json_encode($response);
        exit;
    }
}
?>
