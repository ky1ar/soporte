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
    <div class="container-banner-stls">
        <div class="free-stls">
            <div class="ref">
               <img src="assets/img/first-banner-top-stls.webp" alt="">
            </div>
            <div class="cont">
                <form method="POST" action="https://formsubmit.co/sistemas@krear3d.com" enctype="multipart/form-data">
                    <div class="formi">
                        <input type="text" maxlength="50" placeholder="Nombre Completo" name="nombre" required>
                        <input type="email" maxlength="30" placeholder="Correo" name="correo" required>
                        <input type="tel" maxlength="11" placeholder="RUC o DNI" name="documento" required>
                        <input type="tel" maxlength="9" placeholder="Celular" name="celular" required>
                        <label for="comprobante">Adjunta tu comprobante de pago:</label>
                        <input type="file" name="comprobante" accept=".jpg, .jpeg, .png, .webp, .pdf" required>
                    </div>
                    <input type="submit" value="Enviar">
                </form>
            </div>
        </div>
    </div>
    <section class="xbanner-stls">
        <img src="assets/img/stl-pc.webp">
    </section>


    <div id="stls-container"></div>
    <div class="page-stls">
        <button id="prevPage"><img src="assets/img/next-page.png" alt=""></button>
        <span id="pageIndicator"></span>
        <button id="nextPage"><img src="assets/img/next-page.png" alt=""></button>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>