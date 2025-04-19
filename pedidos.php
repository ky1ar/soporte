<?php
$currentPage = "Pedidos";
require_once 'includes/app/db.php';
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
    <div class="wrapper">
        <div class="containerShip">
            <div id="formPedidos">
                <h1 class="t">Consultar Pedidos</h1>
                <img src="/assets/img/icon-cons-14.webp" alt="">
                <p class="par">Consulta el estado de tus pedidos ingresando tu número de documento (DNI o RUC) en el siguiente formulario.</p>

                <form action="" method="POST" id="formConsulta">
                    <label for="name">Documento:</label>
                    <input type="text" name="name" id="documento" placeholder="Ingrese DNI o RUC" maxlength="11">
                    <button>Consultar</button>
                </form>
            </div>
            <div id="listOrdersShipping">
                <h1 class="title">Mis Pedidos</h1>
            </div>
        </div>
        <div id="orderInfo">
            <div class="content">
                <span class="loaders"></span>
                <div class="head">
                    <h1 class="title">Enviado con <span></span></h1>
                    <p class="estado-actual"></p>
                </div>
                <div class="status">
                    <div class="fases">
                        <span class="st one"></span>
                        <span class="bar"></span>
                        <span class="st two"></span>
                        <span class="bar"></span>
                        <span class="st tree"></span>
                    </div>
                    <div class="names">
                        <span>Agencia</span>
                        <span>En ruta</span>
                        <span>Entregado</span>
                    </div>
                </div>
                <div class="info">
                    <p class="cod">Tracking: <span></span></p>
                    <div class="dat1">
                        <p class="ori">Origen: <span></span></p>
                        <p class="des">Destino: <span></span></p>
                    </div>
                    <div class="line">
                        <div class="fas entregado">
                            <p class="status"><img src="assets/img/sh-entregado.webp" alt=""> Entregado</p>
                            <span class="dash"></span>
                            <p class="date"></p>
                        </div>
                        <div class="fas ruta">
                            <p class="status"><img src="assets/img/sh-envio.webp" alt=""> En ruta</p>
                            <span class="dash"></span>
                            <p class="date"></p>
                        </div>
                        <div class="fas agencia">
                            <p class="status"><img src="assets/img/sh-agencia.webp" alt=""> En agencia</p>
                            <span class="dash"></span>
                            <p class="date"></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
    <script src="/assets/js/pedidos.js"></script>
</body>

</html>