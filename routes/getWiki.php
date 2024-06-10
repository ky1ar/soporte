<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    try {
        $title = $_POST['title'];

        $sql = "SELECT * FROM Wiki WHERE title = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta SQL: ' . $conn->error);
        }

        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            
            // Reemplazar \n por <br> en los valores de los textos
            $textKeys = ['p1', 'coment1', 'pex1', 'p2', 'coment2', 'p3', 'pex3', 'p4', 'p5', 'p51', 'p52', 'p6', 'p61', 'p7', 'p71', 'p72', 'p73', 'p74', 'p8', 'p81', 'p82', 'p811', 'p821', 'p831', 'p841', 'p851', 'p861', 'p822', 'p823', 'p824', 'p825', 'p826', 'p8261', 'p9'];
            foreach ($textKeys as $key) {
                if (isset($data[$key])) {
                    $data[$key] = str_replace("\n", "<br>", $data[$key]);
                }
            }
            
            $response['success'] = true;
            $response['data'] = $data;
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontraron datos para el título proporcionado.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'Error: ' . $e->getMessage();
    } finally {
        $conn->close();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Solicitud inválida.';
}

echo json_encode($response);
?>
