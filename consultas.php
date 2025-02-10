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
            <!-- <p>Introduce el número de orden y el documento tal cual se muestra en la orden de ingreso proporcionada.</p> -->
        </div>
    </section>

    <section id="searchOrderBox">
        <div class="wrapper">
            <div class="guide"><img src="assets/img/guia-pre.webp" alt=""></div>
            <form id="searchOrder">
                <img src="assets/img/ord.svg" alt="ico" width="64" height="64">
                <h1>Consultar Orden de Servicio</h1>
                <p>Introduce el número de orden y el documento tal cual se muestra en la orden de ingreso proporcionada.</p>
                <input id="orderNumber" type="text" placeholder="Número de Orden">
                <input id="document" type="text" placeholder="Documento ( DNI/RUC )">
                <div id="searchOrderMessage"></div>
                <button type="submit">Consultar Estado</button>
            </form>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>