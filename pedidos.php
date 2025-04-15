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
                    <input type="text" name="name" id="documento" placeholder="Ingrese DNI o RUC">
                    <button>Consultar</button>
                </form>
            </div>
            <div id="listOrdersShipping" style="display:none;">
                <h1 class="title">Mis Pedidos</h1>
                <!-- Aquí se agregarán las órdenes generadas por el JS mediante AJAX -->
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