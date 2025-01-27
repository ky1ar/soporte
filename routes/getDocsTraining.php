<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trainingId'])) {
    try {
        $trainingId = $_POST['trainingId'];
        $sql = "SELECT pruebas, invoice FROM Training WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $trainingId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($pruebas, $invoice);
            $stmt->fetch();
            $response = [
                'success' => true,
                'pruebas' => $pruebas,
                'invoice' => $invoice
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No se encontraron datos para este ID.'
            ];
        }
        $stmt->close();
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ];
    }
    echo json_encode($response);
}
?>
