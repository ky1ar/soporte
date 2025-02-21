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
                <h1 class="t1">Tu primera impresión 3D empieza aquí</h1>
                <p class="p1">Completa el formulario y obtén nuestro<br><b>pack de STLs GRATIS</b> y dale forma a tus ideas.</p>
                <form method="POST" action="https://formsubmit.co/sistemas@krear3d.com" enctype="multipart/form-data">
                    <div class="formi">
                        <h1 class="t2">Rellena este formulario</h1>
                        <input type="text" maxlength="50" placeholder="Nombre Completo" name="nombre" required>
                        <input type="email" maxlength="30" placeholder="Correo Electrónico (Gmail*)" name="correo" required>
                        <input type="tel" maxlength="11" placeholder="DNI/RUC" name="documento" required>
                        <input type="tel" maxlength="9" placeholder="N° Celular" name="celular" required>
                        <input type="text" maxlength="11" placeholder="N° de Boleta o Factura" name="comprobante" required>
                    </div>
                    <input type="submit" value="Registrarse">
                </form>
            </div>
        </div>
    </div>
    <div class="categorias-stl-mid">
        <h1 class="t">Categorias</h1>
        <div class="swiffy-slider slider-nav- slider-nav-dark slider-nav-sm slider-nav-outside-expand slider-nav-visible slider-nav-autohide slider-indicators-outside">
            <div class="buttons">
                <button type="button" class="slider-nav"></button>
                <button type="button" class="slider-nav slider-nav-next"></button>
            </div>
            <ul class="slider-container">
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-anime.webp" alt="">
                        <p>Animes</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-chibi.webp" alt="">
                        <p>Chibi</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-funkos.webp" alt="">
                        <p>Funkos</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-flex.webp" alt="">
                        <p>Flexibles</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-marvel.webp" alt="">
                        <p>Marvel</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-dc.webp" alt="">
                        <p>DC Comics</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-sw.webp" alt="">
                        <p>Star Wars</p>
                    </div>
                </li>
                <li class="slide-visible">
                    <div class="categ">
                        <img src="assets/img/cat-stls-acces.webp" alt="">
                        <p>Accesorios</p>
                    </div>
                </li>
            </ul>

        </div>
    </div>
    <div class="s2-slider">
        <div class="swiffy-slider slider-nav-autoplay slider-indicators-round" data-slider-nav-autoplay-interval="3000">
            <ul class="slider-container">
                <li>
                    <img src="assets/img/banner-stls-s5.webp" alt="">
                </li>
                <li>
                    <img src="assets/img/banner-stls-s5.webp" alt="">
                </li>
                <li>
                    <img src="assets/img/banner-stls-s5.webp" alt="">
                </li>
            </ul>
            <ul class="slider-indicators">
                <li class="active"></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </div>

    <div id="stls-container"></div>
    <div class="page-stls">
        <button id="prevPage"><img src="assets/img/next-page.png" alt=""></button>
        <button id="nextPage"><img src="assets/img/next-page.png" alt=""></button>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>