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
    <section class="menu-wiki-movil">
        <ul>
            <li>
                <a href="/wiki">INICIO</a>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOLUCIÓN DE PROBLEMAS</p>
                <ul>
                    <li>Residuos en el extrusor</li>
                    <li>Problemas con el relleno</li>
                    <li>Soportes que fallan</li>
                    <li>Superficies en contacto con soportes con mal aspecto</li>
                    <li>Ghosting</li>
                    <li>Separación de capas</li>
                    <li>Puentes feos</li>
                    <li>Warping</li>
                    <li>Subextrusión</li>
                    <li>Test del Benchy</li>
                    <li>Hilos y goteo</li>
                    <li>Prevención de problemas de extrusión</li>
                    <li>Desplazamiento de capas</li>
                    <li>Problemas en la primera capa</li>
                    <li>La impresora no enciende o sigue apagándose</li>
                    <li>La plataforma de impresión no se está calentando correctamente</li>
                    <li>Ruidos en el extrusor</li>
                    <li>El filamento no se carga</li>
                    <li>Uso del multímetro</li>
                    <li>La extrusión se detuvo a la mitad de la impresión</li>
                    <li>Boquilla / hotend obstruidos</li>
                </ul>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">USANDO LA IMPRESORA</p>
                <ul>
                    <li>Mantenimiento habitual</li>
                    <li>Cambiar o reemplazar la boquilla - Anycubic</li>
                    <li>Cambiar o reemplazar la boquilla - Artillery</li>
                    <li>Cambiar o reemplazar la boquilla - Creality</li>
                    <li>Actualización del firmware - Artillery</li>
                    <li>Actualización del firmware - Anycubic</li>
                    <li>Actualización del firmware - Creality</li>
                    <li>Materiales flexibles</li>
                    <li>PETG</li>
                    <li>PLA</li>
                    <li>Modelos 3D con errores</li>
                    <li>Creando tus propios modelos 3D</li>
                </ul>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">CALIBRACIÓN</p>
                <ul>
                    <li>Nivelación de la cama</li>
                    <li>Corrección de la nivelación de la cama</li>
                    <li>Calibración del flujo</li>
                </ul>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOBRE TU IMPRESORA</p>
                <ul>
                    <li>Documentación con fotos y videos</li>
                    <li>Problemas con tarjetas SD y unidades USB</li>
                    <li>Manuales de montaje</li>
                </ul>
            </li>
        </ul>
    </section>
    <section class="xbanner-wiki">
        <img src="assets/img/wiki-pc.webp">
    </section>
    <section class="data-wiki">
        <!-- <section class="menu-wiki">
            <ul>
                <li>
                    <a href="/wiki">INICIO</a>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOLUCIÓN DE PROBLEMAS</p>
                    <ul>
                        <li>Residuos en el extrusor</li>
                        <li>Problemas con el relleno</li>
                        <li>Soportes que fallan</li>
                        <li>Superficies en contacto con soportes con mal aspecto</li>
                        <li>Ghosting</li>
                        <li>Separación de capas</li>
                        <li>Puentes feos</li>
                        <li>Warping</li>
                        <li>Subextrusión</li>
                        <li>Test del Benchy</li>
                        <li>Hilos y goteo</li>
                        <li>Prevención de problemas de extrusión</li>
                        <li>Desplazamiento de capas</li>
                        <li>Problemas en la primera capa</li>
                        <li>La impresora no enciende o sigue apagándose</li>
                        <li>La plataforma de impresión no se está calentando correctamente</li>
                        <li>Ruidos en el extrusor</li>
                        <li>El filamento no se carga</li>
                        <li>Uso del multímetro</li>
                        <li>La extrusión se detuvo a la mitad de la impresión</li>
                        <li>Boquilla / hotend obstruidos</li>
                    </ul>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">USANDO LA IMPRESORA</p>
                    <ul>
                        <li>Mantenimiento habitual</li>
                        <li>Cambiar o reemplazar la boquilla - Anycubic</li>
                        <li>Cambiar o reemplazar la boquilla - Artillery</li>
                        <li>Cambiar o reemplazar la boquilla - Creality</li>
                        <li>Actualización del firmware - Artillery</li>
                        <li>Actualización del firmware - Anycubic</li>
                        <li>Actualización del firmware - Creality</li>
                        <li>Materiales flexibles</li>
                        <li>PETG</li>
                        <li>PLA</li>
                        <li>Modelos 3D con errores</li>
                        <li>Creando tus propios modelos 3D</li>
                    </ul>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">CALIBRACIÓN</p>
                    <ul>
                        <li>Nivelación de la cama</li>
                        <li>Corrección de la nivelación de la cama</li>
                        <li>Calibración del flujo</li>
                    </ul>
                </li>
                <li>
                    <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOBRE TU IMPRESORA</p>
                    <ul>
                        <li>Documentación con fotos y videos</li>
                        <li>Problemas con tarjetas SD y unidades USB</li>
                        <li>Manuales de montaje</li>
                    </ul>
                </li>
            </ul>
        </section> -->

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