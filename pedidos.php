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

                <form action="" method="POST">
                    <label for="name">Documento:</label>
                    <input type="text" name="name" placeholder="Ingrese DNI o RUC">
                    <button>Consultar</button>
                </form>
            </div>
            <div id="listOrdersShipping">
                <div class="order">
                    <div class="head">
                        <p class="order">S232399</p>
                    </div>
                    <div class="cont">
                        <div class="info">
                            <p class="fecha">Llega el 2 de mayo</p>
                            <p class="status">Enviado</p>
                            <p class="details">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Suscipit, ex. Adipisci perferendis commodi tenetur esse laudantium obcaecati quibusdam fugiat a provident consequuntur? Ipsa officia optio eaque veritatis quasi, expedita laboriosam.</p>
                        </div>
                        <div class="actions">
                            <button>Rastrear</button>
                            <button>Obtener Ayuda</button>
                        </div>
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