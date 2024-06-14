<?php
$currentPage = "STLs";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>

    <section class="xbanner-stls">
        <img src="assets/img/stl-pc.webp">
    </section>

    <!-- Contenedor para las tarjetas de STLs -->
    <div id="stls-container"></div>

    <!-- Controles de paginación -->
    <div class="pagination">
        <button id="prevPage">Anterior</button>
        <span id="pageIndicator">Página 1</span>
        <button id="nextPage">Siguiente</button>
    </div>

    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>