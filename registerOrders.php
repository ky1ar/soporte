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
    <div class="registerTrackings">
        <form action="#" method="POST">
            <label for="order_number">Order Number:</label>
            <input type="text" id="order_number" name="order_number" value="S432432" required><br><br>

            <label for="agency_id">Agency ID:</label>
            <input type="number" id="agency_id" name="agency_id" value="1" required><br><br>

            <label for="admin_id">Admin ID:</label>
            <input type="number" id="admin_id" name="admin_id" value="3" required><br><br>

            <label for="code1">Code 1:</label>
            <input type="text" id="code1" name="code1" value="484848" required><br><br>

            <label for="code2">Code 2:</label>
            <input type="text" id="code2" name="code2" value="12" required><br><br>

            <label for="client_id">Client ID:</label>
            <input type="number" id="client_id" name="client_id" value="756" required><br><br>

            <label for="client_document">Document:</label>
            <input type="text" id="client_document" name="client[document]" value="01234567" required><br><br>

            <label for="client_name">Name:</label>
            <input type="text" id="client_name" name="client[name]" value="Kenny Muñoz Serafin" required><br><br>

            <label for="client_phone">Phone:</label>
            <input type="text" id="client_phone" name="client[phone]" value="946887982" required><br><br>

            <input type="submit" value="Submit">
        </form>
    </div>

    <?php require_once 'includes/common/footer.php'; ?>
    <script src="/assets/js/pedidos.js"></script>
</body>

</html>