<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['machineVal'])) {
    $machineVal = $_POST['machineVal'];

    $sql = "SELECT m.id, m.slug, CONCAT(b.name, ' ', m.model) AS fullName FROM Machine m JOIN Brand b ON m.brand = b.id WHERE CONCAT(b.name, ' ', m.model) LIKE '%$machineVal%' AND b.id != 41 AND m.filtro = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p class='suggestionsRow' data-id='" . $row['id'] . "' data-slug='" . $row['slug'] . "'>" . $row['fullName'] . "</p>";
        }
    } else {
        echo "<p class='suggestionsRow'>No se encontraron resultados</p>";
    }
} else {
    echo json_encode(array('error' => 'Documento no proporcionado'));
}

$conn->close();
