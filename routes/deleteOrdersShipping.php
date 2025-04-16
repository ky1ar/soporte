<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');
$eliminarQuery = "DELETE FROM Orders_Shipping WHERE fecha_creacion < NOW() - INTERVAL 30 DAY";
if ($conn->query($eliminarQuery)) {
    echo json_encode(['status' => 'success', 'message' => 'Registros antiguos eliminados exitosamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar registros: ' . $conn->error]);
}
?>
