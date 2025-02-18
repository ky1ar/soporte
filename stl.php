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
                <input type="text" maxlength="50" placeholder="Nombre Completo" required>
                <input type="email" maxlength="30" placeholder="Correo" required>
                <input type="tel" maxlength="11" placeholder="RUC o DNI" required>
                <input type="tel" maxlength="9" placeholder="Celular" required>
                <label for="comprobante">Adjunta tu comprobante de pago:</label>
                <input type="file" name="comprobante" required>
                <input type="submit" value="Enviar">
            </form>
        </div>
    </div>
    <div id="stls-container"></div>
    <div class="page-stls">
        <button id="prevPage"><img src="assets/img/next-page.png" alt=""></button>
        <span id="pageIndicator"></span>
        <button id="nextPage"><img src="assets/img/next-page.png" alt=""></button>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>