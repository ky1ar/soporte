<?php
$currentPage = "Consultas";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>

    <section id="frontSlider">
        <div class="wrapper">
            <img class="imaSop" width="1920" height="630" src="assets/img/consulta-pc.webp">
        </div>
    </section>
    <section id="pasos-consulta">
        <div class="t">
            <p>¿Cómo registrar un equipo <span>en Soporte Técnico?</span></p>
            <p>Te presentamos el paso a paso para introducir tu equipo en nuestro servicio.</p>
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
        <p class="o">Introduce tu núnero de orden aquí</p>
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
                <div>Celular: <span>*********</</span>div>
                <div>Nombre: <span>***************************</span><div>
                <hr>
                <div>Marca: <span>*********************</</span>div>
                <div>N° de serie: <span>*********************</</span>div>
                <div>Modelo: <span>*********************</</span>div>
                <div>Fecha de compra <span>** / ** / ****</span></div>
                <hr>
                <div>Fecha de ingreso <span>** / ** / ****</span></div>
                <div id="searchOrderMessage"></div>
                <button type="submit">Consultar</button>
            </form>
        </div>
    </section>
    <section id="con-servicios">
        <h1 class="tit">Precios del Servicio <span>Técnico K3D</span></h1>
        <p class="subt">Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, ut tempore. Totam dignissimos facere dolor sunt quaerat.</p>
        <div class="servicios">
            <a class="ser" href="servicios?id=1">
                <img src="./assets/img/con-s1.webp" alt="">
                <p>Cambio de Pantalla LED</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=2">
                <img src="./assets/img/con-s2.webp" alt="">
                <p>Cambio de FEP</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=3">
                <img src="./assets/img/con-s3.webp" alt="">
                <p>Cambio de Boquilla</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=4">
                <img src="./assets/img/con-s4.webp" alt="">
                <p>Mantenimiento Preventivo</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=5">
                <img src="./assets/img/con-s5.webp" alt="">
                <p>Servicio de Armado</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=6">
                <img src="./assets/img/con-s6.webp" alt="">
                <p>Cambio de Placa Electronica Principal</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=7">
                <img src="./assets/img/con-s7.webp" alt="">
                <p>Cambio de Fuente de Poder</p>
                <p>Desde S/100.00*</p>
            </a>
            <a class="ser" href="servicios?id=8">
                <img src="./assets/img/con-s8.webp" alt="">
                <p>Actualización de Firmware</p>
                <p>Desde S/100.00*</p>
            </a>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>