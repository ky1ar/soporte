<?php
$data = json_decode(file_get_contents('php://input'), true);

// Depuración: Verificar si los datos fueron recibidos correctamente
if ($data === null) {
    echo "Error al decodificar los datos JSON";
    exit;
}

echo "Datos recibidos: ";
print_r($data); // Muestra los datos recibidos

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data['start_date']) && isset($data['end_date'])) {
    $startDate = $data['start_date'] . ' 00:00:00';
    $endDate = $data['end_date'] . ' 23:59:59';
    $metric = isset($data['metric']) ? $data['metric'] : '';

    echo "Fecha de inicio: $startDate\n"; // Depuración
    echo "Fecha de fin: $endDate\n"; // Depuración
    echo "Métrica seleccionada: $metric\n"; // Depuración

    // Subconsulta de trabajadores válidos
    $validWorkersSubquery = "
        SELECT id 
        FROM Users 
        WHERE levels IN (2, 3) AND id != 203
    ";

    // Inicializamos el array de respuesta
    $response = [
        'labels' => [],
        'data' => []
    ];

    // Condiciones para la métrica seleccionada
    if ($metric === 'stat8') {
        // Consulta para 'stat8' (Equipos entregados)
        $sql = "SELECT u.id, u.name, SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8
                FROM Users u
                LEFT JOIN Orders o ON o.worker = u.id
                LEFT JOIN Orders_Status os ON os.orders = o.id
                WHERE os.dates BETWEEN ? AND ? 
                AND u.id IN ($validWorkersSubquery)
                GROUP BY u.id, u.name";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo "Error al preparar la consulta: " . $conn->error;
            exit;
        }
        
        echo "Consulta preparada correctamente.\n"; // Depuración

        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verificar si la consulta devolvió resultados
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo "Datos obtenidos para 'stat8':\n";
            print_r($data); // Depuración

            // Rellenamos el array de respuesta con los valores
            foreach ($data as $row) {
                $response['labels'][] = $row['name']; // Nombre del trabajador
                $response['data'][] = $row['stat8']; // Valor de stat8
            }
        } else {
            echo "No se encontraron resultados para 'stat8'.\n"; // Depuración
        }
    }
    elseif ($metric === 'totalTrainings') {
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

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo "Error al preparar la consulta: " . $conn->error;
            exit;
        }

        echo "Consulta preparada correctamente.\n"; // Depuración

        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verificar si la consulta devolvió resultados
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo "Datos obtenidos para 'totalTrainings':\n";
            print_r($data); // Depuración

            // Rellenamos el array de respuesta con los valores
            foreach ($data as $row) {
                $response['labels'][] = $row['name']; // Nombre del trabajador
                $response['data'][] = $row['totalTrainings']; // Valor de totalTrainings
            }
        } else {
            echo "No se encontraron resultados para 'totalTrainings'.\n"; // Depuración
        }
    }
    elseif ($metric === 'trabajo_realizado') {
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

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo "Error al preparar la consulta: " . $conn->error;
            exit;
        }

        echo "Consulta preparada correctamente.\n"; // Depuración

        $stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verificar si la consulta devolvió resultados
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo "Datos obtenidos para 'trabajo_realizado':\n";
            print_r($data); // Depuración

            // Rellenamos el array de respuesta con los valores
            foreach ($data as $row) {
                $response['labels'][] = $row['name']; // Nombre del trabajador
                $response['data'][] = $row['trabajo_realizado']; // Valor de trabajo_realizado
            }
        } else {
            echo "No se encontraron resultados para 'trabajo_realizado'.\n"; // Depuración
        }
    }

    // Devolver la respuesta en formato JSON
    echo "Respuesta final: \n";
    print_r($response); // Depuración
    echo json_encode($response);
    exit;
} else {
    echo "Método o parámetros incorrectos.\n";
}
?>
