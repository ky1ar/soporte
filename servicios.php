<?php
$currentPage = "Servicios";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';

// Obtener el id_servicio de la URL
$id_servicio = isset($_GET['id_servicio']) ? $_GET['id_servicio'] : 0;

if ($id_servicio) {
    // Conexión a la base de datos y consulta
    $query = "SELECT * FROM Servicios WHERE id_servicio = :id_servicio";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
    $stmt->execute();
    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>

    <div id="sup-servicios">
        <?php if ($servicio): ?>
            <h1 class="title"><?php echo htmlspecialchars($servicio['descripcion']); ?></h1>
            <p class="par-ini"><?php echo htmlspecialchars($servicio['intro']); ?></p>
            <table>
                <tr>
                    <th>Descripción</th>
                    <th colspan="2">Costos</th>
                    <th>Criterios de Tamaño</th>
                </tr>
                <tr>
                    <td rowspan="3"><?php echo htmlspecialchars($servicio['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($servicio['tamaño']); ?></td>
                    <td><?php echo htmlspecialchars($servicio['precio']); ?></td>
                    <td><?php echo htmlspecialchars($servicio['criterios']); ?></td>
                </tr>
                <!-- Aquí podrías agregar más filas según el tamaño o el tipo de servicio -->
            </table>
        <?php else: ?>
            <p>Servicio no encontrado.</p>
        <?php endif; ?>
    </div>

    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>
