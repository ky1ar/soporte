<?php
require_once '../includes/app/db.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // Obtener las fechas de inicio y fin del formulario
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Consulta SQL para contar los valores de stat 1 y stat 9 en el rango de fechas
    $sql = "
        SELECT
            SUM(CASE WHEN os.stat = 1 THEN 1 ELSE 0 END) AS stat_1_count,
            SUM(CASE WHEN os.stat = 9 THEN 1 ELSE 0 END) AS stat_9_count
        FROM orders_status os
        WHERE os.dates BETWEEN ? AND ?
    ";
    
    // Preparar y ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $startDate, $endDate); // Vinculamos las fechas
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Guardar los resultados
        $stat1Count = $data['stat_1_count'];
        $stat9Count = $data['stat_9_count'];

        // Devolver los resultados como un array
        echo json_encode([
            'stat1Count' => $stat1Count,
            'stat9Count' => $stat9Count
        ]);
    } else {
        // En caso de error en la preparación de la consulta
        echo json_encode([
            'error' => 'Error al ejecutar la consulta'
        ]);
    }
} else {
    // En caso de que no se hayan recibido las fechas
    echo json_encode([
        'error' => 'Fechas no proporcionadas'
    ]);
}
?>
