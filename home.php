<?php
$currentPage = "Test";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/home.css">

</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>
    <section>
        <div class="home-banner">
            <img src="assets/slider/banner-home.webp">
        </div>
    </section>
    <section class="s1">
        <div class="section-item">
            <h1>CONSULTAS</h1>
            <p>Verifica el estado de tu equipo ingresado a soporte técnico a tiempo real.</p>
            <button>INGRESAR</button>
        </div>
        <div class="section-item">
            <h1>CAPACITACIONES</h1>
            <p>Ahorra tiempo y agenda tu mismo la capacitación en el horario que tengas disponible.</p>
            <button>INGRESAR</button>
        </div>
        <div class="section-item">
            <h1>WIKI</h1>
            <p>Encuentra todo el contenido para que te vuelvas un experto.</p>
            <button>INGRESAR</button>
        </div>
        <div class="section-item">
            <h1>STLs</h1>
            <p>Descarga de forma gratuita todos los diseños de nuestras redes</p>
            <button>INGRESAR</button>
        </div>
        <div class="section-item">
            <h1>SLICERS</h1>
            <p>Accede a los programas más avanzados para tus equipos</p>
            <button>INGRESAR</button>
        </div>
    </section>

    <section class="body-s1">
        <img style="width: 100%;" height="630" src="assets/slider/banner2.png">
        <h1 class="title-qs">PREGUNTAS FRECUENTES</h1>
        <div class="faq">
            <div class="question">
                <p class="question-text">¿Tienes fallas en tu equipo?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <ol>
                    <li>Si tu equipo presenta fallas o problemas de configuración, debes comunicarte con nosotros por mensaje al WhatsApp <a href="https://wa.me/51970539751" target="_blank">+51 970 539 751</a> detallando los inconvenientes. No olvides indicar: marca, modelo, número de serie y comprobante de pago.</li><br>
                    <li>Te daremos toda la asistencia necesaria para solucionar el problema de forma virtual y, de ser necesario, te pediremos que internes tu equipo en nuestro centro de soporte técnico oficial ubicado en <b>Calle Tutumo 116, Surco</b>.</li><br>
                    <li>Al ingresar tu equipo, registraremos todos los datos como marca, modelo, número de serie, accesorios y podrás indicar el problema que encontraste. Recuerda que, con tu DNI o RUC y el número de la orden, podrás consultar el estado de tu equipo en tiempo real y conocer en qué etapa se encuentra: <a href="https://soporte.krear3d.com/" target="_blank">https://soporte.krear3d.com/</a></li><br>
                    <li>Si la falla es de fábrica, no te preocupes que estarás cubierto por la garantía y no tendrás que pagar nada. En caso de que se trate de un servicio de reparación o mantenimiento, te daremos todos los costos antes de iniciar.</li>
                </ol>
            </div>

        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Cuántos días demoran las reparaciones?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>En promedio las fallas de los equipos se solucionan en una semana, ya que contamos con un amplio stock de repuestos e insumos de todas las marcas que representamos. De ser un caso más complejo, la primera etapa clave que es el diagnóstico que puede demorar hasta 10 días y posteriormente la etapa final de la reparación otros 10 días hábiles.<br><br>
                    Recuerda que, con tu DNI o RUC y el número de la orden, podrás consultar el estado de tu equipo a tiempo real, conocer qué técnico se está encargando, en qué etapa se encuentra: <a href="https://soporte.krear3d.com/">https://soporte.krear3d.com/</a>
                </p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Cuándo no podrás aplicar la garantía?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>Si no realizas una buena instalación o armado, puedes malograr tu equipo debido a una mala conexión, voltaje incorrecto, rotura de componentes, etc.
                    <br><br>
                    Recuerda que en Perú es 220V la alimentación común.
                    <br><br>
                    En caso de no manipularlo correctamente, produciendo daños, rayaduras, derrames de líquidos, etc. Ten cuidado con los equipos de resinas. Los componentes que se desgastan debido a la cantidad de horas que usas tu equipo, como boquillas, plataformas, pantallas, etc.
                </p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Qué recomendaciones debes seguir?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>Revisa los manuales de usuario, tutoriales, guía de inicio y las instrucciones provistas con el producto, porque los equipos son dispositivos electrónicos sofisticados.
                    <br><br>
                    Debido a la alta precisión mecánica y piezas electrónicas, debes realizar un mantenimiento preventivo cada 3 meses para prevenir cualquier problema.
                </p>
            </div>
        </div>
    </section>
    <section class="home-contact">
        <h1>CONTÁCTANOS</h1>
        <div class="container-cards">
            <div class="card-info">
                <h1>Email</h1>
                <img src="assets/img/home-email.png" alt="">
                <p>soporte@krear3d.com</p>
            </div>
            <div class="card-info">
                <h1>WhatsApp</h1>
                <img src="assets/img/whatsapp.png" alt="">
                <p>+51 970 539 751</p>
            </div>
            <div class="card-info">
                <h1>Encuéntranos</h1>
                <img src="assets/img/home-ubi.png" alt="">
                <p>Calle Tutumo 116, Surco</p>
            </div>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>

    <script src="./assets/js/efects.js"></script>
</body>

</html>