<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

$response = array();

try {
    $sql = "SELECT * FROM STL";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Error en la preparación de la consulta SQL: ' . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $response['success'] = true;
        $response['data'] = $data;
    } else {
        $response['success'] = false;
        $response['message'] = "No se encontraron datos en la tabla STL.";
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
