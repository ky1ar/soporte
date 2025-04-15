<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

$response = array();

try {
    // Obtener itemsPerPage y page desde POST (ya no desde GET)
    $itemsPerPage = isset($_POST['itemsPerPage']) ? intval($_POST['itemsPerPage']) : 4;
    $currentPage = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Obtener el total de STL en la base de datos
    $totalQuery = "SELECT COUNT(*) as total FROM STL";
    $totalResult = $conn->query($totalQuery);
    $totalRow = $totalResult->fetch_assoc();
    $totalItems = $totalRow['total'];

    // Consulta para obtener los datos paginados
    $sql = "SELECT * FROM STL ORDER BY registro DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $itemsPerPage, $offset);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $response['success'] = true;
        $response['data'] = $data;
        $response['totalItems'] = $totalItems;
        $response['itemsPerPage'] = $itemsPerPage;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error en la consulta: ' . $stmt->error;
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
