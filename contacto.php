<?php
$currentPage = "Contacto";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>
    <section class="xbanner-top">
        <img src="assets/img/banner-contacto-pc.webp">
    </section>
    <section class="contact-info">
        <div class="card">
            <h1>Email</h1>
            <img src="assets/img/home-email.png" alt="">
            <p>soporte@krear3d.com</p>
        </div>
        <div class="card">
            <h1>WhatsApp</h1>
            <img src="assets/img/whatsapp.png" alt="">
            <p>+51 970 539 751</p>
        </div>
        <div class="card">
            <h1>Encuéntranos</h1>
            <img src="asets/img/home-ubi.png" alt="">
            <p>Calle Tutumo 116, Surco</p>
        </div>
        <div class="card">
            <h1>Horario</h1>
            <img src="asets/img/calendar.png" alt="">
            <p>Lun - Sab de 9:00 a 1:00 pm <br>Lun - Vie de 2:00 a 6:00 pm</p>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>