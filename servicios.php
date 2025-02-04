<?php
$currentPage = "Servicios";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>
    <div id="sup-servicios">
        <h1 class="title"></h1>
        <p class="par-ini"></p>
        <table>
            <tr>
                <th>Descripción</th>
                <th colspan="2">Costos</th>
            </tr>
            <tr>
                <td rowspan="3">Reemplazo de pantalla (precio según modelo)</td>
                <td>Tamaño</td>
                <td>Precio</td>
            </tr>
            <tr>
                <td>Pequeño</td>
                <td>S/. 80.00</td>
            </tr>
            <tr>
                <td>Mediano</td>
                <td>S/. 100.00</td>
            </tr>
            <tr>
                <td>Grande</td>
                <td>S/. 200.00</td>
            </tr>
        </table>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>