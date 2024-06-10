<?php
$currentPage = "Wiki";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>

    <section class="xbanner-wiki">
        <img src="assets/img/wiki-pc.webp">
    </section>
    <section class="data-wiki">
        <section class="menu-wiki">
            <ul>
                <li>
                    <a href="/wiki">INICIO</a>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOLUCIÓN DE PROBLEMAS</p>
                    <ul>
                        <li><a href="#">Residuos en el extrusor</a></li>
                        <li><a href="#">Problemas con el relleno</a></li>
                        <li><a href="#">Soportes que fallan</a></li>
                        <li><a href="#">Superficies en contacto con soportes con mal aspecto</a></li>
                        <li><a href="#">Ghosting</a></li>
                        <li><a href="#">Separación de capas</a></li>
                        <li><a href="#">Puentes feos</a></li>
                        <li><a href="#">Warping</a></li>
                        <li><a href="#">Subextrusión</a></li>
                        <li><a href="#">Test del Benchy</a></li>
                        <li><a href="#">Hilos y goteo</a></li>
                        <li><a href="#">Prevención de problemas de extrusión</a></li>
                        <li><a href="#">Desplazamiento de capas</a></li>
                        <li><a href="#">Problemas en la primera capa</a></li>
                        <li><a href="#">La impresora no enciende o sigue apagándose</a></li>
                        <li><a href="#">La plataforma de impresión no se está calentando correctamente</a></li>
                        <li><a href="#">Ruidos en el extrusor</a></li>
                        <li><a href="#">El filamento no se carga</a></li>
                        <li><a href="#">Uso del multímetro</a></li>
                        <li><a href="#">La extrusión se detuvo a la mitad de la impresión</a></li>
                        <li><a href="#">Boquilla / hotend obstruidos</a></li>
                    </ul>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">USANDO LA IMPRESORA</p>
                    <ul>
                        <li><a href="#">Mantenimiento habitual</a></li>
                        <li><a href="#">Cambiar o reemplazar la boquilla - Anycubic</a></li>
                        <li><a href="#">Cambiar o reemplazar la boquilla - Artillery</a></li>
                        <li><a href="#">Cambiar o reemplazar la boquilla - Creality</a></li>
                        <li><a href="#">Actualización del firmware - Artillery</a></li>
                        <li><a href="#">Actualización del firmware - Anycubic</a></li>
                        <li><a href="#">Actualización del firmware - Creality</a></li>
                        <li><a href="#">Materiales flexibles</a></li>
                        <li><a href="#">PETG</a></li>
                        <li><a href="#">PLA</a></li>
                        <li><a href="#">Modelos 3D con errores</a></li>
                        <li><a href="#">Creando tus propios modelos 3D</a></li>


                    </ul>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">CALIBRACIÓN</p>
                    <ul>
                        <li><a href="#">Nivelación de la cama</a></li>
                        <li><a href="#">Corrección de la nivelación de la cama</a></li>
                        <li><a href="#">Calibración del flujo</a></li>


                    </ul>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOBRE TU IMPRESORA</p>
                    <ul>
                        <li><a href="#">Documentación con fotos y videos</a></li>
                        <li><a href="#">Problemas con tarjetas SD y unidades USB</a></li>
                        <li><a href="#">Manuales de montaje</a></li>


                    </ul>
                </li>
            </ul>
        </section>
        <section class="data">
            <h1 class="title">Bienvenido al Wiki de KREAR 3D</h1>
        </section>
    </section>

    <?php require_once 'includes/common/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleMenus = document.querySelectorAll('.toggle-menu');

            toggleMenus.forEach(function(menu) {
                menu.addEventListener('click', function() {
                    var nextElement = menu.nextElementSibling;
                    var imgElement = menu.querySelector('img');

                    if (nextElement && nextElement.tagName === 'UL') {
                        if (nextElement.classList.contains('show')) {
                            nextElement.classList.remove('show');
                            imgElement.classList.remove('rotate');
                        } else {
                            nextElement.classList.add('show');
                            imgElement.classList.add('rotate');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>