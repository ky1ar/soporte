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
        srv.descripcion as intro,
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

    if ($stmt === false) { // Verifica si prepare() tuvo éxito
        die("Error en la consulta SQL: " . $conn->error . "<br>Consulta: " . $sql);
    }

    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) { // Verifica $result y num_rows
        $service = $result->fetch_assoc();
    } else {
        $errorMessage = "No se encontraron servicios.";
    }

    $stmt->close();
    $conn->close();
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
        <?php if (isset($service)): ?> <h1 class="title"><?php echo $service['nombre_servicio']; ?></h1>
            <p><?php echo $service['intro']; ?></p>
            <p><?php echo $service['cuestion1']; ?></p>
            <p><?php echo $service['dato1']; ?></p>
            <p><?php echo $service['dato2']; ?></p>
            <p><?php echo $service['dato3']; ?></p>
            <p><?php echo $service['dato4']; ?></p>
            <p><?php echo $service['dato5']; ?></p>
            <p><?php echo $service['cuestion2']; ?></p>
            <p><?php echo $service['dato6']; ?></p>
            <p><?php echo $service['dato7']; ?></p>
            <p><?php echo $service['dato8']; ?></p>
            <p><?php echo $service['dato9']; ?></p>
            <p><?php echo $service['dato10']; ?></p>
            <p class="resumen"><?php echo $service['dato11']; ?></p>
            <p><?php echo $service['descripcion']; ?></p>
            <p><?php echo $service['tamaño']; ?></p>
            <p><?php echo $service['precio']; ?></p>
            <p><?php echo $service['criterios']; ?></p>

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
                    <tr>
                        <td><?php echo $service['descripcion']; ?></td>
                        <td><?php echo $service['tamaño']; ?></td>
                        <td><?php echo $service['precio']; ?></td>
                        <td><?php echo $service['criterios']; ?></td>
                    </tr>
                </tbody>
            </table>

        <?php elseif (isset($errorMessage)): ?>
            <p><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <p class="advertencia">Nota: Los costos no incluyen repuestos y están sujetos a variaciones sin previo aviso.</p>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>