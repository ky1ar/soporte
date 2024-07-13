<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

$response = array();

try {
    // Configuración de la paginación
    $itemsPerPage = 8; // Cambiar según tu necesidad
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Consulta para obtener los datos paginados
    $sql = "SELECT * FROM STL ORDER BY registro DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $itemsPerPage, $offset);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        if (!empty($data)) {
            $response['success'] = true;
            $response['data'] = $data;
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontraron datos en la tabla STL.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Error en la ejecución de la consulta: ' . $stmt->error;
    }

    $stmt->close();
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    $conn->close();
}

echo json_encode($response);
?>
