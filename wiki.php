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
        <img src="assets/img/stl-pc.webp">
    </section>
    <section class="menu-wiki">
        <ul>
            <li>
                <a href="#">INICIO</a>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOLUCIÓN DE PROBLEMAS</p>
                <ul>
                    <li><a href="#">Residuos En El Extrusor</a></li>
                    <li><a href="#">Problemas Con El Relleno</a></li>
                    <li><a href="#">Soportes Que Fallan</a></li>
                    <li><a href="#">Superficies En Contacto Con Soportes Con Mal Aspecto</a></li>
                    <li><a href="#">Ghosting</a></li>
                    <li><a href="#">Separación De Capas</a></li>
                    <li><a href="#">Puentes Feos</a></li>
                    <li><a href="#">Warping</a></li>
                    <li><a href="#">Subextrusión</a></li>
                    <li><a href="#">Test Del Benchy</a></li>
                    <li><a href="#">Hilos Y Goteo</a></li>
                    <li><a href="#">Prevención De Problemas De Extrusión</a></li>
                    <li><a href="#">Desplazamiento De Capas</a></li>
                    <li><a href="#">Problemas En La Primera Capa</a></li>
                    <li><a href="#">La Impresora No Enciende O Sigue Apagándose</a></li>
                    <li><a href="#">La Plataforma De Impresión No Se Está Calentando Correctamente</a></li>
                    <li><a href="#">Ruidos En El Extrusor</a></li>
                    <li><a href="#">El Filamento No Se Carga</a></li>
                    <li><a href="#">Uso Del Multímetro</a></li>
                    <li><a href="#">La Extrusión Se Detuvo A La Mitad De La Impresión</a></li>
                    <li><a href="#">Boquilla / Hotend Obstruidos</a></li>
                </ul>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">USANDO LA IMPRESORA</p>
                <ul>
                    <li><a href="#">Actualización Del Firmware</a></li>
                    <li><a href="#">Cambiar O Reemplazar La Boquilla</a></li>
                    <li><a href="#">Mantenimiento Habitual</a></li>
                    <li><a href="#">Cambiar O Reemplazar La Boquilla - Anycubic</a></li>
                    <li><a href="#">Cambiar O Reemplazar La Boquilla - Artillery</a></li>
                    <li><a href="#">Cambiar O Reemplazar La Boquilla - Creality</a></li>
                    <li><a href="#">Actualización Del Firmware - Artillery</a></li>
                    <li><a href="#">Actualización Del Firmware - Anycubic</a></li>
                    <li><a href="#">Actualización Del Firmware - Creality</a></li>
                    <li><a href="#">Materiales Flexibles</a></li>
                    <li><a href="#">PETG</a></li>
                    <li><a href="#">PLA</a></li>
                    <li><a href="#">Modelos 3D Con Errores</a></li>
                    <li><a href="#">Creando Tus Propios Modelos 3D</a></li>

                </ul>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">CALIBRACIÓN</p>
                <ul>
                    <li><a href="#">Nivelación De La Cama</a></li>
                    <li><a href="#">Corrección De La Nivelación De La Cama</a></li>
                    <li><a href="#">Calibración Del Flujo</a></li>

                </ul>
            </li>
            <li>
                <p class="toggle-menu"><img src="./assets/img/menu-clip.png" alt="">SOBRE TU IMPRESORA</p>
                <ul>
                    <li><a href="#">Documentación Con Fotos Y Videos</a></li>
                    <li><a href="#">Problemas Con Tus Tarjetas SD Y Unidades USB</a></li>
                    <li><a href="#">Manuales De Montaje</a></li>

                </ul>
            </li>
        </ul>
    </section>

    <?php require_once 'includes/common/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleMenus = document.querySelectorAll('.toggle-menu');

            toggleMenus.forEach(function(menu) {
                menu.addEventListener('click', function() {
                    var nextElement = menu.nextElementSibling;

                    if (nextElement && nextElement.tagName === 'UL') {
                        if (nextElement.style.display === 'none' || nextElement.style.display === '') {
                            nextElement.style.display = 'block';
                        } else {
                            nextElement.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>