<?php
require_once '../includes/app/db.php';

// Establece el encabezado de contenido JSON
header('Content-Type: application/json');

// Inicializa una respuesta por defecto
$response = array("success" => false, "message" => "Solicitud inválida.");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
    $title = $_POST['title'];

    // Prepara la consulta SQL
    $stmt = $conn->prepare("SELECT * FROM Wiki WHERE title = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica si se encontraron resultados
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $response = array("success" => true, "data" => $data);
        } else {
            $response['message'] = "No se encontraron datos para el título proporcionado.";
        }

        $stmt->close();
    } else {
        $response['message'] = "Error en la preparación de la consulta SQL.";
    }
} else {
    $response['message'] = "Solicitud inválida.";
}

// Envía la respuesta como JSON
echo json_encode($response);

$conn->close();
?>
