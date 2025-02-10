<?php
$currentPage = "Servicios";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
require_once 'includes/app/db.php';

if (isset($_GET['id'])) {
    $serviceId = $_GET['id'];
    $sql_servicio = "  -- Consulta para la tabla 'Servicio' (srv)
    SELECT 
        srv.descripcion AS desc,
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
        srv.dato11
    FROM Servicio srv
    WHERE srv.id_servicio = ?";

    $stmt_servicio = $conn->prepare($sql_servicio);

    if ($stmt_servicio === false) {
        die("Error en la consulta SQL de 'Servicio': " . $conn->error . "<br>Consulta: " . $sql_servicio);
    }

    $stmt_servicio->bind_param("i", $serviceId);
    $stmt_servicio->execute();
    $result_servicio = $stmt_servicio->get_result();

    if ($result_servicio && $result_servicio->num_rows > 0) {
        $servicio_data = $result_servicio->fetch_assoc(); // Datos para los párrafos <p>
    } else {
        $errorMessage = "No se encontraron detalles del servicio.";
    }

    $stmt_servicio->close();


    $sql_servicios = "  -- Consulta para la tabla 'Servicios' (s) - para la tabla
    SELECT 
        s.id AS servicio_id,
        s.descripcion,
        s.tamaño,
        s.precio,
        s.criterios
    FROM Servicios s
    WHERE s.id_servicio = ?";

    $stmt_servicios = $conn->prepare($sql_servicios);

    if ($stmt_servicios === false) {
        die("Error en la consulta SQL de 'Servicios': " . $conn->error . "<br>Consulta: " . $sql_servicios);
    }

    $stmt_servicios->bind_param("i", $serviceId);
    $stmt_servicios->execute();
    $result_servicios = $stmt_servicios->get_result();

    $servicios_table_data = []; // Array para almacenar los datos de la tabla
    if ($result_servicios && $result_servicios->num_rows > 0) {
        while ($row = $result_servicios->fetch_assoc()) {
            $servicios_table_data[] = $row;
        }
    }

    $stmt_servicios->close();
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
    <?php if (isset($servicio_data)): ?>  <h1 class="title"><?php echo $service['nombre_servicio']; ?></h1>
            <p><?php echo $servicio_data['desc']; ?></p>
            <p><?php echo $servicio_data['cuestion1']; ?></p>
            <p><?php echo $servicio_data['dato1']; ?></p>
            <p><?php echo $servicio_data['dato2']; ?></p>
            <p><?php echo $servicio_data['dato3']; ?></p>
            <p><?php echo $servicio_data['dato4']; ?></p>
            <p><?php echo $servicio_data['dato5']; ?></p>
            <p><?php echo $servicio_data['cuestion2']; ?></p>
            <p><?php echo $servicio_data['dato6']; ?></p>
            <p><?php echo $servicio_data['dato7']; ?></p>
            <p><?php echo $servicio_data['dato8']; ?></p>
            <p><?php echo $servicio_data['dato9']; ?></p>
            <p><?php echo $servicio_data['dato10']; ?></p>
            <p class="resumen"><?php echo $servicio_data['dato11']; ?></p>
        <?php elseif (isset($errorMessage)): ?>
            <p><?php echo $errorMessage; ?></p>
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
                <?php if (isset($servicios_table_data) && count($servicios_table_data) > 0): ?>
                    <?php foreach ($servicios_table_data as $row): ?>
                        <tr>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td><?php echo $row['tamaño']; ?></td>
                            <td><?php echo $row['precio']; ?></td>
                            <td><?php echo $row['criterios']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <p class="advertencia">Nota: Los costos no incluyen repuestos y están sujetos a variaciones sin previo aviso.</p>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>