<?php
$currentPage = "Registro de Tracking";
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
    <div class="wrapper">
        <div id="registerTrackings">
            <form action="#" method="POST" class="form">
                <h1 class="title">Registro de Tracking</h1>

                <div class="order">
                    <input type="text" id="order_number" name="order_number" placeholder="N° Orden" maxlength="6" required>
                </div>
                <div class="doc">
                    <input type="text" id="document" name="document" placeholder="DNI o RUC" maxlength="11" required>
                </div>
                <div class="name">
                    <input type="text" id="name" name="name" placeholder="Nombre o Razón Social" maxlength="40" required>
                </div>
                <div class="phone">
                    <input type="tel" id="phone" name="phone" placeholder="Celular" maxlength="9" required>
                </div>
                <div class="agen-tit">
                    <p>Agencia</p>
                </div>
                <div class="agen">
                    <select name="agency" id="agency" required>
                        <option value="1">Shalom</option>
                        <option value="2">Olva</option>
                        <option value="3">Marvisur</option>
                    </select>
                </div>
                <div class="track">
                    <input type="text" id="code1" name="code1" required>
                    <input type="text" id="code2" name="code2" required>
                </div>
                <button class="ins">Agregar Registro</button>
            </form>

        </div>
    </div>

    <?php require_once 'includes/common/footer.php'; ?>
    <script src="/assets/js/tracking.js"></script>
</body>

</html>