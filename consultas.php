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
    <section id="pasos">
        <div class="sec1">
            <div class="t">
                <p>¿Cómo registrar un equipo <span>en Soporte Técnico?</span></p>
                <p>Te presentamos el paso a paso para introducir tu equipo en nuestro servicio.</p>
            </div>
            <div class="pasos">
                <div class="p">
                    <p>1</p>
                    <p>Contáctanos</p>
                    <p>Comunícate con nuestra área de soporte al +51 970 539 751 y describe el problema o inconveniente que presenta tu equipo.</p>
                </div>
                <div class="p">
                    <p>2</p>
                    <p>Interna tu equipo</p>
                    <p>Ingresa el producto a nuestros locales autorizados previa coordinación.</p>
                </div>
                <div class="p">
                    <p>3</p>
                    <p>Seguimiento</p>
                    <p>Ten a la mano el número de orden, tu DNI o RUC y podrás consultar el estado de tu equipo de forma rápida y sencilla.</p>
                </div>
                <div class="p">
                    <p>4</p>
                    <p>¡Listo para entrega!</p>
                    <p>Nuestro equipo de soporte solucionará tu caso y podrás recogerlo en la fecha indicada.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="searchOrderBox">
        <div class="wrapper">
            <p class="d">Introduce tu número de documento aquí (DNI / RUC)</p>
            <p class="o">Introduce tu núnero de orden aquí</p>
            <form id="searchOrder">
                <h1>Consultar Orden de Servicio</h1>
                <p>Introduce el número de orden y el documento tal cual se muestra en la orden de ingreso proporcionada.</p>
                <input id="orderNumber" type="text" placeholder="Número de Orden">
                <input id="document" type="text" placeholder="Documento ( DNI/RUC )">
                <div id="searchOrderMessage"></div>
                <button type="submit">Consultar Estado</button>
            </form>
        </div>
    </section>
    <section id="">

    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>