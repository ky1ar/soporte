<?php
$currentPage = "Soporte";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/home.css">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-42H4RQXRQG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-42H4RQXRQG');
    </script>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>
    <section>
        <div class="home-banner">
            <img src="assets/img/home-pc.webp">
        </div>
    </section>
    <section id="pasos-consulta">
        <div class="t">
            <p>¿Cómo registrar un equipo <span>en Soporte Técnico?</span></p>
            <p>Te presentamos el paso a paso para ingresar tu equipo en nuestro servicio.</p>
        </div>
        <div class="pasos">
            <div class="pas">
                <p>1</p>
                <p>Contáctanos</p>
                <p>Comunícate con nuestra área de soporte al +51 970 539 751 y describe el problema o inconveniente que presenta tu equipo.</p>
            </div>
            <div class="pas">
                <p>2</p>
                <p>Interna tu equipo</p>
                <p>Ingresa el producto a nuestros locales autorizados previa coordinación.</p>
            </div>
            <div class="pas">
                <p>3</p>
                <p>Seguimiento</p>
                <p>Ten a la mano el número de orden, tu DNI o RUC y podrás consultar el estado de tu equipo de forma rápida y sencilla.</p>
            </div>
            <div class="pas">
                <p>4</p>
                <p>¡Listo para entrega!</p>
                <p>Nuestro equipo de soporte solucionará tu caso y podrás recogerlo en la fecha indicada.</p>
            </div>
        </div>
    </section>
    <section id="searchOrderBox">
        <p class="o">Introduce tu número de orden aquí</p>
        <div class="wrapper">
            <div class="cons">
                <p class="d">Introduce tu número de documento aquí (DNI / RUC)</p>
                <p>Consultar <br><span>Orden de Servicio</span></p>
                <p>Introduce el número de orden y el documento tal cual se muestra en la orden de ingreso proporcionada.</p>
            </div>
            <form id="searchOrder">
                <div>N°: <input id="orderNumber" type="text" placeholder="Orden"></div>
                <hr>
                <p class="g">GUÍA DE INTERNAMIENTO</p>
                <hr>
                <div>RUC/DNI: <input id="document" type="text" placeholder="Documento"></div>
                <div>Celular: <span>*********</span></div>
                <div>Nombre: <span>***************************</span></div>
                <hr>
                <div>Marca: <span>*********************</span></div>
                <div>N° de serie: <span>*********************</span></div>
                <div>Modelo: <span>*********************</span></div>
                <div>Fecha de compra <span>** / ** / ****</span></div>
                <hr>
                <div>Fecha de ingreso <span>** / ** / ****</span></div>
                <div id="searchOrderMessage"></div>
                <div class="c1">
                </div>
                <div class="c2">
                </div>
                <button type="submit">Consultar</button>
            </form>
            <div class="docu">
                Introduce tu número de documento aquí (DNI / RUC)
            </div>
        </div>
    </section>
    <section id="con-servicios">
        <h1 class="tit">Tarifario de Servicio <span>Técnico K3D</span></h1>
        <p class="subt">Nos especializamos en brindarte el mejor servicio técnico para mantener tu equipo en óptimas condiciones. Desde mantenimiento preventivo hasta el reemplazo de piezas clave, nuestro equipo de expertos está listo para ayudarte.</p>
        <div class="servicios">
            <a class="ser" href="/cambio-de-pantalla-lcd">
                <img src="./assets/img/con-s1.webp" alt="">
                <p>Cambio de Pantalla LCD</p>
                <p>Desde S/80.00*</p>
            </a>
            <a class="ser" href="/cambio-de-fep">
                <img src="./assets/img/con-s2.webp" alt="">
                <p>Cambio de FEP</p>
                <p>Desde S/40.00*</p>
            </a>
            <a class="ser" href="/cambio-de-boquilla">
                <img src="./assets/img/con-s3.webp" alt="">
                <p>Cambio de Boquilla</p>
                <p>Desde S/40.00*</p>
            </a>
            <a class="ser" href="/mantenimiento-preventivo">
                <img src="./assets/img/con-s4.webp" alt="">
                <p>Mantenimiento Preventivo</p>
                <p>Desde S/60.00*</p>
            </a>
            <a class="ser" href="/servicio-de-armado">
                <img src="./assets/img/con-s5.webp" alt="">
                <p>Servicio de Armado</p>
                <p>Desde S/80.00*</p>
            </a>
            <a class="ser" href="/cambio-de-placa-electronica-principal">
                <img src="./assets/img/con-s6.webp" alt="">
                <p>Cambio de Placa Electronica Principal</p>
                <p>Desde S/180.00*</p>
            </a>
            <a class="ser" href="/cambio-de-fuente-de-poder">
                <img src="./assets/img/con-s7.webp" alt="">
                <p>Cambio de Fuente de Poder</p>
                <p>Desde S/100.00*</p>
            </a>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>