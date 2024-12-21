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
        FROM Orders_Status os
        WHERE os.dates BETWEEN ? AND ?
    ";
    
    // Preparar y ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Vinculamos las fechas a los parámetros de la consulta
        $stmt->bind_param("ss", $startDate, $endDate);

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            // Verificamos si hay resultados
            if ($data) {
                $stat1Count = $data['stat_1_count'];
                $stat9Count = $data['stat_9_count'];

                // Devolver los resultados como un array JSON
                echo json_encode([
                    'stat1Count' => $stat1Count,
                    'stat9Count' => $stat9Count
                ]);
            } else {
                echo json_encode([
                    'error' => 'No se encontraron resultados para las fechas proporcionadas'
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'Error al ejecutar la consulta: ' . $stmt->error
            ]);
        }

        $stmt->close(); // Cerrar el statement
    } else {
        // Error al preparar la consulta
        echo json_encode([
            'error' => 'Error al preparar la consulta: ' . $conn->error
        ]);
    }
} else {
    // En caso de que no se hayan recibido las fechas
    echo json_encode([
        'error' => 'Fechas no proporcionadas'
    ]);
}
?>
