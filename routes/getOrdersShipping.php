<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'])) {
    $documento = trim($_POST['documento']);

    if (strlen($documento) !== 8 && strlen($documento) !== 11) {
        echo json_encode(['status' => 'error', 'message' => 'No tiene ningún registro']);
        exit;
    }

    $query = "
        SELECT 
            o.id_order,
            u.nombre AS nombre_usuario,
            u.documento,
            o.orden,
            o.agencia AS id_agencia,
            a.agencia_name AS nombre_agencia,
            o.code1,
            o.code2,
            s.status AS nombre_status
        FROM Orders_Shipping o
        INNER JOIN Users_Shipping u ON o.id_user = u.id_user
        LEFT JOIN Agency_Shipping a ON o.agencia = a.id_agencia
        LEFT JOIN Status_Shipping s ON o.status = s.id_status
        WHERE u.documento = ?
        ORDER BY o.fecha_creacion DESC
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        if (!empty($orders)) {
            echo json_encode(['status' => 'success', 'orders' => $orders]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No tiene ningún registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud no válida']);
}
