<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    $startDate .= ' 00:00:00';
    $endDate .= ' 23:59:59';

    // Construir la consulta SQL
    $sql = "
        SELECT
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat1,
            SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8
        FROM Orders_Status os
        INNER JOIN Orders o ON os.orders = o.id
        INNER JOIN Users u ON o.worker = u.id
        WHERE os.dates BETWEEN ? AND ?
    ";
    
    // Agregar condición adicional si se proporciona workerId
    if (!is_null($workerId)) {
        $sql .= " AND u.id = ?";
    }

    // Depuración: Mostrar la consulta antes de ejecutarla
    echo "Consulta SQL: $sql\n";

    $params = array($startDate, $endDate);
    if (!is_null($workerId)) {
        $params[] = $workerId;
    }

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
        exit;
    }

    // Definir tipos de parámetros
    $types = str_repeat('s', count($params)); 

    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                $stat1Count = $data['stat1'];
                $stat8Count = $data['stat8'];

                // Consulta para el total de entrenamientos
                $trainingSql = "
                    SELECT COUNT(*) AS num_rows
                    FROM Training t
                    INNER JOIN Users u ON t.worker = u.id
                    WHERE t.training_state = 2
                    AND t.training_date BETWEEN ? AND ?
                ";

                $paramsTraining = array($startDate, $endDate);
                $typesTraining = 'ss';

                if (!is_null($workerId)) {
                    $trainingSql .= " AND t.worker = ?";
                    $paramsTraining[] = $workerId;
                    $typesTraining .= 'i';
                }

                $trainingStmt = $conn->prepare($trainingSql);
                if ($trainingStmt === false) {
                    echo json_encode(['error' => 'Error al preparar la consulta de entrenamiento']);
                    exit;
                }

                $trainingStmt->bind_param($typesTraining, ...$paramsTraining);
                $trainingStmt->execute();
                $trainingResult = $trainingStmt->get_result();
                $trainingData = $trainingResult->fetch_assoc();

                $totalTrainings = $trainingData ? $trainingData['num_rows'] : 0;

                echo json_encode([
                    'stat1Count' => $stat1Count,
                    'stat8Count' => $stat8Count,
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
