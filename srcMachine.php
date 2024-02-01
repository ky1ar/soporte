<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['machine'])) {
    $machine = $_POST['machine'];

    $sql = "SELECT * FROM Machine WHERE model LIKE '%$machine%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p class='frm-sgs' data-id='" . $row['id'] . "' data-slug='" . $row['slug'] . "'>" . $row['model'] . "</p>";
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
?>
