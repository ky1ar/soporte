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
            <img src="assets/img/home-ubi.png" alt="">
            <p>Calle Tutumo 116, Surco</p>
        </div>
        <div class="card">
            <h1>Horario</h1>
            <img src="assets/img/calendario.png" alt="">
            <p>Lun - Sab de 9:00 a 1:00 pm <br>Lun - Vie de 2:00 a 6:00 pm</p>
        </div>
    </section>
    <section class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3900.881126920144!2d-77.02178992418398!3d-12.12028514327561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8050a2d4db7%3A0x22544c87aa238139!2sCalle%20Javier%20Fernandez%20262%2C%20Miraflores%2015047!5e0!3m2!1ses-419!2spe!4v1717449142718!5m2!1ses-419!2spe" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>