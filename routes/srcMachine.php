<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['machine'])) {
    $searchTerm = $_POST['machine'];

    // Consulta SQL actualizada para buscar por modelo, marca o slug
    $sql = "SELECT m.id, m.slug, CONCAT(b.name, ' ', m.model) AS fullName FROM Machine m JOIN Brand b ON m.brand = b.id WHERE CONCAT(b.name, ' ', m.model) LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Mostrar el resultado con la nueva columna
            echo "<p class='frm-sgs' data-id='" . $row['id'] . "' data-slug='" . $row['slug'] . "'>" . $row['fullName'] . "</p>";
        }
    } else {
        echo "<p class='frm-sgs'>No se encontraron resultados</p>";
    }


    /*$stmt = $conn->prepare("SELECT * FROM Machine WHERE model = ?");
    $stmt->bind_param("s", $machine);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(array('ky1ar' => true, 'image' => $row['slug'], 'id' => $row['id']));
    } else {
        echo json_encode(array('ky1ar' => false));
    }*/
} else {
    echo json_encode(array('error' => 'Documento no proporcionado'));
}

$conn->close();
