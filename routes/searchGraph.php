<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data['start_date']) && isset($data['end_date'])) {
    $startDate = $data['start_date'] . ' 00:00:00';
    $endDate = $data['end_date'] . ' 23:59:59';
    $metric = isset($data['metric']) ? $data['metric'] : '';

    if ($metric === 'stat8') {
        $sql = "SELECT u.id, u.name, SUM(CASE WHEN os.stat = 8 THEN 1 ELSE 0 END) AS stat8
                FROM Users u
                LEFT JOIN Orders o ON o.worker = u.id
                LEFT JOIN Orders_Status os ON os.orders = o.id
                WHERE os.dates BETWEEN ? AND ?
                GROUP BY u.id, u.name";
    } elseif ($metric === 'totalTrainings') {
        $sql = "SELECT u.id, u.name, IFNULL(t.totalTrainings, 0) AS totalTrainings
                FROM Users u
                LEFT JOIN (
                    SELECT t.worker, COUNT(*) AS totalTrainings
                    FROM Training t
                    WHERE t.training_state = 2
                    AND t.training_date BETWEEN ? AND ?
                    GROUP BY t.worker
                ) t ON t.worker = u.id";
    } elseif ($metric === 'trabajo_realizado') {
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
                GROUP BY u.id, u.name";
    } else {
        // Opción por defecto ("Todos")
        $sql = "SELECT 
                    SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat1
                FROM Orders_Status os
                WHERE os.dates BETWEEN ? AND ?";
    }

    $stmt = $conn->prepare($sql);
    if ($metric === 'totalTrainings' || $metric === 'trabajo_realizado') {
        $stmt->bind_param('ssss', $startDate, $endDate, $startDate, $endDate);
    } else {
        $stmt->bind_param('ss', $startDate, $endDate);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($data);
    exit;
} else {
    echo json_encode(['error' => 'Fechas no proporcionadas']);
    exit;
}
