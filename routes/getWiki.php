<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
    $title = $_POST['title'];
    // Prepara la consulta SQL
    $stmt = $conn->prepare("SELECT * FROM Wiki WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si se encontraron resultados
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(array("success" => true, "data" => $data));
    } else {
        echo json_encode(array("success" => false, "message" => "No se encontraron datos para el título proporcionado."));
    }

    $stmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Solicitud inválida."));
}

$conn->close();
