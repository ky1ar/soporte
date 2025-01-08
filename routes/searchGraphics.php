<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;

    // Aseguramos que las fechas estén bien formateadas con tiempo
    $startDate .= ' 00:00:00';
    $endDate .= ' 23:59:59';

    // Consulta unificada
    $sql = "
        SELECT
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat1,
            SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8,
            (
                SELECT COUNT(*)
                FROM Training t
                WHERE t.training_state = 2
                AND t.training_date BETWEEN ? AND ?
            ) AS totalTrainings
        FROM Orders_Status os
        INNER JOIN Orders o ON os.orders = o.id
        INNER JOIN Users u ON o.worker = u.id
        WHERE os.dates BETWEEN ? AND ?
    ";

    // Establecemos los parámetros para la consulta
    $params = array($startDate, $endDate, $startDate, $endDate);

    // Si se proporciona el workerId, se añade el filtro para ese trabajador
    if (!is_null($workerId)) {
        $sql .= " AND u.id = ?";
        $params[] = $workerId;
    }

    // Preparamos la consulta
    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params));  // 's' para string, ya que son fechas y trabajador ID (string o int)

    if ($stmt->bind_param($types, ...$params)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                // Recogemos los resultados
                $stat1Count = $data['stat1'];
                $stat8Count = $data['stat8'];
                $totalTrainings = $data['totalTrainings'];

                // Mostramos los datos en formato JSON
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
