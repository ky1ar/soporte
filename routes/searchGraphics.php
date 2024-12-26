<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Asegurar que la fecha de inicio tenga la hora a las 00:00:00
    $startDate .= ' 00:00:00';

    // Asegurar que la fecha de fin tenga la hora a las 23:59:59
    $endDate .= ' 23:59:59';

    $sql = "
        SELECT
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat_1_count,
            SUM(CASE WHEN os.stat = 9 THEN 1 ELSE 0 END) AS stat_9_count
        FROM Orders_Status os
        WHERE os.dates BETWEEN ? AND ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $startDate, $endDate);

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
