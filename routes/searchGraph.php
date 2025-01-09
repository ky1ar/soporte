<?php
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos llegaron correctamente
if ($data === null) {
    echo json_encode(['error' => 'Error al decodificar los datos JSON: ' . json_last_error_msg()]);
    exit;
}

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

    // Inicializamos la respuesta
    $response = [
        'labels' => [],
        'data' => []
    ];

    // Consulta según la métrica seleccionada
    if ($metric === 'stat8') {
        // Consulta para 'stat8' (Equipos entregados)
        $sql = "SELECT u.id, u.name, SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8
                FROM Users u
                LEFT JOIN Orders o ON o.worker = u.id
                LEFT JOIN Orders_Status os ON os.orders = o.id
                WHERE os.dates BETWEEN ? AND ? 
                AND u.id IN ($validWorkersSubquery)
                GROUP BY u.id, u.name";

    } elseif ($metric === 'totalTrainings') {
        // Consulta para 'totalTrainings' (Capacitaciones finalizadas)
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

    } elseif ($metric === 'trabajo_realizado') {
        // Consulta para 'trabajo_realizado' (Trabajo realizado)
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
    } else {
        echo json_encode(['error' => 'Métrica no válida']);
        exit;
    }

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['error' => 'Error al preparar la consulta SQL']);
        exit;
    }

    $stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvieron resultados
    if ($result && $result->num_rows > 0) {
        // Rellenamos los datos de la respuesta
        while ($row = $result->fetch_assoc()) {
            $response['labels'][] = $row['name']; // Nombre del trabajador
            if ($metric === 'stat8') {
                $response['data'][] = $row['stat8']; // Equipos entregados
            } elseif ($metric === 'totalTrainings') {
                $response['data'][] = $row['totalTrainings']; // Capacitaciones finalizadas
            } elseif ($metric === 'trabajo_realizado') {
                $response['data'][] = $row['trabajo_realizado']; // Trabajo realizado
            }
        }
    } else {
        echo json_encode(['error' => 'No se encontraron resultados']);
        exit;
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
    exit;
} else {
    echo json_encode(['error' => 'Fecha de inicio y fin son requeridas']);
    exit;
}
?>
