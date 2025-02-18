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

    <div class="free-stls">
        <div class="in">
            <h1>¿Te gustaría obtener más archivos STL?</h1>
            <p>Si ya eres cliente y estás interesado en ampliar tu colección de archivos STL, regístrate ahora. Evaluaremos tu solicitud y te enviaremos un paquete de archivos STL gratuitos como un beneficio adicional a tu compra. ¡No esperes más, regístrate hoy mismo!</p>
        </div>
        <div class="cont">
            <form action="">
                <input type="text" max="50">
                <input type="email" max="30">
                <input type="text" max="8">
                <input type="tel" max="9">
                <input type="submit" value="Enviar">
            </form>
        </div>
    </div>
    <!-- Contenedor para las tarjetas de STLs -->
    <div id="stls-container"></div>
    <!-- Controles de paginación -->
    <div class="page-stls">
        <button id="prevPage"><img src="assets/img/next-page.png" alt=""></button>
        <span id="pageIndicator"></span>
        <button id="nextPage"><img src="assets/img/next-page.png" alt=""></button>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>