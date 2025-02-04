<?php
// routes/getServicios.php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['serviceId'])) {
    try {
        $serviceId = $_POST['serviceId'];
        $response = [];

        $sql = "
        SELECT 
            s.id AS servicio_id,
            srv.nombre AS nombre_servicio,
            s.intro,
            s.descripcion,
            s.tamaño,
            s.precio,
            s.criterios
        FROM Servicios s
        INNER JOIN Servicio srv ON s.id_servicio = srv.id
        WHERE s.id_servicio = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['success'] = true;
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $response['success'] = false;
            $response['message'] = 'No se encontraron servicios.';
        }

        echo json_encode($response);  // Devolver los datos como JSON
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn = null;
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida']);
}

