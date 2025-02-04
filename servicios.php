<?php
$currentPage = "Servicios";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
require_once 'includes/app/db.php'; // Conectar a la base de datos

if (isset($_GET['id'])) {
    $serviceId = $_GET['id']; // Obtener el ID del servicio desde la URL

    // Realizar la consulta con el ID recibido
    $sql = "
    SELECT 
        s.id AS servicio_id,
        srv.nombre AS nombre_servicio,
        s.intro,
        s.descripcion,
        s.tamaño,
        s.precio,
        s.criterios
    FROM Servicios s
    INNER JOIN Servicio srv ON s.id_servicio = srv.id
    WHERE s.id_servicio = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Obtener todas las filas en un array
        $services = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $errorMessage = "No se encontraron servicios.";
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn = null;
}
?>

</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>

    <div id="sup-servicios">
        <?php if (isset($services) && count($services) > 0): ?>
            <!-- Mostrar el nombre y la introducción del primer servicio, por ejemplo -->
            <h1 class="title"><?php echo $services[0]['nombre_servicio']; ?></h1>
            <p class="intro"><?php echo $services[0]['intro']; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Tamaño</th>
                    <th>Precio</th>
                    <th>Criterios</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($services) && count($services) > 0) {
                    foreach ($services as $service) {
                        echo "<tr>
                        <td>{$service['descripcion']}</td>
                        <td>{$service['tamaño']}</td>
                        <td>{$service['precio']}</td>
                        <td>{$service['criterios']}</td>
                    </tr>";
                    }
                } elseif (isset($errorMessage)) {
                    echo "<tr><td colspan='4'>$errorMessage</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>