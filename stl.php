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

    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>