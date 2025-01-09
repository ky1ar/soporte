<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['metric'])) {
    // Obtener parámetros de entrada
    $startDate = $_POST['start_date'] . ' 00:00:00';
    $endDate = $_POST['end_date'] . ' 23:59:59';
    $metric = $_POST['metric'];

    $validWorkersSubquery = "573, 193, 1, 638, 324, 2";
    $stat8Count = 0;
    $totalTrainings = 0;
    $trabajoRealizado = 0;

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
            echo json_encode(['error' => 'Métrica no válida']);
            exit;
    }
} else {
    echo json_encode(['error' => 'Fecha de inicio y fin son requeridas']);
}
