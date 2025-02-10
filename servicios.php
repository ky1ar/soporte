<?php
$currentPage = "Servicios";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
require_once 'includes/app/db.php';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $serviceId = intval($_GET['id']);
} else {
    die("Servicio no encontrado.");
}

$sql = "
    SELECT 
        s.id AS servicio_id,
        srv.nombre AS nombre_servicio,
        srv.descripcion AS intro,
        srv.cuestion1,
        srv.dato1,
        srv.dato2,
        srv.dato3,
        srv.dato4,
        srv.dato5,
        srv.cuestion2,
        srv.dato6,
        srv.dato7,
        srv.dato8,
        srv.dato9,
        srv.dato10,
        srv.dato11,
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
            <p><?php echo $services[0]['intro']; ?></p>
            <p class="cuest"><?php echo $services[0]['cuestion1']; ?></p>
            <ul>
                <?php
                $dataFields = ['dato1', 'dato2', 'dato3', 'dato4', 'dato5'];

                foreach ($dataFields as $field) {
                    if (!empty($services[0][$field])) {
                        echo "<li>{$services[0][$field]}</li>";
                    }
                }
                ?>
            </ul>
            <p class="cuest"><?php echo $services[0]['cuestion2']; ?></p>
            <ul>
                <?php
                $dataFields = ['dato6', 'dato7', 'dato8', 'dato9', 'dato10'];

                foreach ($dataFields as $field) {
                    if (!empty($services[0][$field])) {
                        echo "<li>{$services[0][$field]}</li>";
                    }
                }
                ?>
            </ul>
            <p><?php echo $services[0]['dato11']; ?></p>
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
                    $totalServices = count($services);
                    $first = true;
                    foreach ($services as $service) {
                        if ($first) {
                            echo "<tr>
                            <td rowspan='$totalServices'>{$service['descripcion']}</td>
                            <td>{$service['tamaño']}</td>
                            <td>{$service['precio']}</td>
                            <td>{$service['criterios']}</td>
                        </tr>";
                            $first = false;
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
        <p class="advertencia">Nota: Los costos no incluyen repuestos y están sujetos a variaciones sin previo aviso.</p>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>