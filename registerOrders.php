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
                <label for="order_number">Order Number:</label>
                <input type="text" id="order_number" name="order_number" required><br><br>

                <label for="agency_id">Agency ID:</label>
                <input type="number" id="agency_id" name="agency_id" required><br><br>

                <label for="admin_id">Admin ID:</label>
                <input type="number" id="admin_id" name="admin_id" required><br><br>

                <label for="code1">Code 1:</label>
                <input type="text" id="code1" name="code1" required><br><br>

                <label for="code2">Code 2:</label>
                <input type="text" id="code2" name="code2" required><br><br>

                <label for="client_id">Client ID:</label>
                <input type="number" id="client_id" name="client_id" required><br><br>

                <label for="client_document">Document:</label>
                <input type="text" id="client_document" name="client[document]" required><br><br>

                <label for="client_name">Name:</label>
                <input type="text" id="client_name" name="client[name]" required><br><br>

                <label for="client_phone">Phone:</label>
                <input type="text" id="client_phone" name="client[phone]" required><br><br>

                <input type="submit" value="Submit">
            </form>

        </div>
    </div>

    <?php require_once 'includes/common/footer.php'; ?>
    <script src="/assets/js/pedidos.js"></script>
</body>

</html>