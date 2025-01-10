<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['metric'])) {
    // Obtener parámetros de entrada
    $startDate = $_POST['start_date'] . ' 00:00:00';
    $endDate = $_POST['end_date'] . ' 23:59:59';
    $workerId = isset($_POST['worker_id']) ? $_POST['worker_id'] : null;
    $metric = $_POST['metric'];

    // Usar workerId o subconsulta según corresponda
    $validWorkersSubquery = $workerId ? $workerId : "SELECT id FROM Users WHERE levels IN (2, 3) AND id NOT IN (203, 1, 573)";

    $startDateObj = new DateTime($_POST['start_date']);
    $endDateObj = new DateTime($_POST['end_date']);
    $previoFinal = $startDateObj->modify('-1 day')->format('Y-m-d 23:59:59');
    $daysDifference = $startDateObj->diff($endDateObj)->days;
    $previoInicial = $startDateObj->modify("-$daysDifference days")->format('Y-m-d 00:00:00');

    $stat8Count = 0;
    $totalTrainings = 0;
    $trabajoRealizado = 0;

    // Debug para verificar valores de las fechas
    echo "Previo Inicial: $previoInicial<br>";
    echo "Previo Final: $previoFinal<br>";

    switch ($metric) {

        case 'stat8':
            $sql = "SELECT u.id, u.name, SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8
                    FROM Users u
                    LEFT JOIN Orders o ON o.worker = u.id
                    LEFT JOIN Orders_Status os ON os.orders = o.id
                    WHERE os.dates BETWEEN ? AND ? 
                    AND u.id IN ($validWorkersSubquery)
                    GROUP BY u.id, u.name";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'name' => $row['name'],
                        'valor' => $row['stat8']
                    ];
                }
                echo json_encode($data);
            } else {
                echo json_encode([]);
            }
            $stmt->close();
            break;

        case 'totalTrainings':
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
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'name' => $row['name'],
                        'valor' => $row['totalTrainings']
                    ];
                }
                echo json_encode($data);
            } else {
                echo json_encode([]);
            }
            $stmt->close();
            break;

        case 'trabajo_realizado':
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

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = [
                        'name' => $row['name'],
                        'valor' => $row['trabajo_realizado']
                    ];
                }
                echo json_encode($data);
            } else {
                echo json_encode([]);
            }
            $stmt->close();
            break;

        default:
            $isWorkerValid = !empty($workerId) && is_numeric($workerId);

            $sql = "SELECT 
                " . ($isWorkerValid ? "" : "SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat1, ") . "
                SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8,
                (
                    SELECT COUNT(*)
                    FROM Training t
                    WHERE t.training_state = 2
                    AND t.training_date BETWEEN ? AND ?
                    AND t.worker IN ($validWorkersSubquery)
                ) AS totalTrainings,
                (
                    SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) + 
                    (
                        SELECT COUNT(*)
                        FROM Training t
                        WHERE t.training_state = 2
                        AND t.training_date BETWEEN ? AND ?
                        AND t.worker IN ($validWorkersSubquery)
                    )
                ) AS totalSum
            FROM Orders_Status os
            INNER JOIN Orders o ON os.orders = o.id
            INNER JOIN Users u ON o.worker = u.id
            WHERE os.dates BETWEEN ? AND ?
            AND u.levels IN (2, 3)
            AND u.id NOT IN (203, 1, 573)
            AND u.id IN ($validWorkersSubquery)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $startDate, $endDate, $startDate, $endDate, $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($isWorkerValid) {
                        $data[] = [
                            ['name' => 'Equipos Reparados', 'valor' => $row['stat8']],
                            ['name' => 'Capacitaciones Realizadas', 'valor' => $row['totalTrainings']],
                            ['name' => 'Total de Actividad', 'valor' => $row['totalSum']]
                        ];
                    } else {
                        $data[] = [
                            ['name' => 'Equipos Ingresados', 'valor' => $row['stat1']],
                            ['name' => 'Equipos Reparados', 'valor' => $row['stat8']],
                            ['name' => 'Capacitaciones Realizadas', 'valor' => $row['totalTrainings']],
                        ];
                    }
                }
                $formattedData = array_merge(...$data);
                echo json_encode($formattedData);
            } else {
                echo json_encode([]);
            }
            $stmt->close();

            break;
    }
} else {
    echo json_encode(['error' => 'Fecha de inicio y fin son requeridas']);
}
