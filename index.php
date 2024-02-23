<?php 
$currentPage = "Citas"; 
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
            <img width="1920" height="630" src="assets/slider/b1.webp">
            <p>Estamos encantados de que inicies tu travesía con nosotros. Prepara tu equipo, lleva a cabo las primeras pruebas y comencemos juntos.</p>
        </div>
    </section>

    <section id="searchOrderBox">
        <div class="wrapper">
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