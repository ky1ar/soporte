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
                <h1 class="title">Enviado con <span>Shalom</span></h1>
                <div class="status">
                    <div class="fases">
                        <span class="st one"></span>
                        <span class="bar"></span>
                        <span class="st two"></span>
                        <span class="bar"></span>
                        <span class="st tree"></span>
                    </div>
                    <div class="names">
                        <span>Comprado</span>
                        <span>Enviado</span>
                        <span>Entregado</span>
                    </div>
                </div>
                <div class="info">
                    <p class="cod">Codigos de Tracking: <span>44283715 / DHJD</span></p>
                    <div class="dat1">
                        <p>Origen: <span>Lima</span></p>
                        <p>Destino: <span>Piura</span></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id="viewPedidos">
            <div class="menu">
                <button onclick="mostrarFormSh(1)" class="active"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/04/logo-shalom.png" alt=""></button>
                <button onclick="mostrarFormSh(2)"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/04/logo-olva.png" alt=""></button>
                <button onclick="mostrarFormSh(3)"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/04/logo-marvisur.png" alt=""></button>
            </div>
            <div class="forms">
                <div id="formShalom" class="formulario active shalom">
                    <form id="rastreoForm">
                        <label>Número de Orden:</label>
                        <input type="text" name="numero" required><br><br>

                        <label>Código:</label>
                        <input type="text" name="codigo" required><br><br>

                        <button type="submit">Buscar</button>
                    </form>

                    <div id="resultado" style="margin-top: 20px;"></div>
                </div>

                <div id="formOlva" class="formulario olva">
                    <h2>Formulario Archivo 2</h2>
                    <p>Contenido del formulario 2.</p>
                </div>

                <div id="formMarvisur" class="formulario marvi">
                    <h2>Formulario Archivo 3</h2>
                    <p>Contenido del formulario 3.</p>
                </div>
            </div>
        </div> -->
    </div>
    <?php require_once 'includes/common/footer.php'; ?>
    <script src="/assets/js/pedidos.js"></script>
</body>

</html>