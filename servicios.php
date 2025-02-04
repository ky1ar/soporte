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
                <th>Criterios de Tamaño</th>
            </tr>
            <tr>
                <td rowspan="4">Reemplazo de pantalla (precio según modelo)</td>
                <td>Tamaño</td>
                <td>Precio</td>
                <td>Medidas</td>
            </tr>
            <tr>
                <td>Pequeño</td>
                <td>S/. 80.00</td>
                <td>2.8 - 4.3 pulgadas</td>
            </tr>
            <tr>
                <td>Mediano</td>
                <td>S/. 100.00</td>
                <td>5.0 - 6.0 pulgadas</td>
            </tr>
            <tr>
                <td>Grande</td>
                <td>S/. 200.00</td>
                <td>7.0 - 9.0 pulgadas o más</td>
            </tr>
        </table>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>