<?php
$currentPage = "Servicios";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
require_once 'includes/app/db.php';

if (isset($_GET['id'])) {
    $serviceId = $_GET['id'];
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
    if ($result->num_rows > 0) {
        $services = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $errorMessage = "No se encontraron servicios.";
    }
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
    <section id="frontSlider">
        <div class="wrapper">
            <img class="imaSop" width="1920" height="630" src="assets/img/consulta-pc.webp">
        </div>
    </section>

    <div id="sup-servicios">
        <?php if (isset($services) && count($services) > 0): ?>
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
                    // Obtener el número total de filas para el rowspan
                    $totalServices = count($services);
                    $first = true; // Flag para la primera fila
                    foreach ($services as $service) {
                        if ($first) {
                            echo "<tr>
                            <td rowspan='$totalServices'>{$service['descripcion']}</td>
                            <td>{$service['tamaño']}</td>
                            <td>{$service['precio']}</td>
                            <td>{$service['criterios']}</td>
                        </tr>";
                            $first = false; // Después de la primera fila, no usar rowspan
                        } else {
                            echo "<tr>
                            <td>{$service['tamaño']}</td>
                            <td>{$service['precio']}</td>
                            <td>{$service['criterios']}</td>
                        </tr>";
                        }
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