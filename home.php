<?php
$currentPage = "Test";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"> 
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
            <h1>GARANTIA EXTENDIDA</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quibusdam quos modi, similique dolorem esse tenetur numquam vitae repellendus sapiente eveniet quidem expedita libero laboriosam. Est placeat quibusdam soluta quis maiores.</p>
            <button>COTIZAR</button>
        </div>
        <div class="section-item">
            <h1>ENTRENAMIENTO CERTIFICADO</h1>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laborum sequi sed iure recusandae sunt voluptatibus sit officia possimus veniam molestias impedit numquam doloribus ratione qui fugit, magni aperiam! Unde, laudantium!</p>
            <button>COTIZAR</button>
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
                    <li>Si tu equipo presenta fallas o problemas de configuración, debes comunicarte con nosotros por mensaje al WhatsApp <a href="https://wa.me/51970539751">+51 970 539 751</a>detallando los inconvenientes. No olvides indicar: marca, modelo, número de serie y comprobante de pago.</li>
                    <li>Te daremos toda la asistencia necesaria para solucionar el problema de forma virtual y, de ser necesario, te pediremos que internes tu equipo en nuestro centro de soporte técnico oficial ubicado en Calle Tutumo 116, Surco.</li>
                    <li>Al ingresar tu equipo, registraremos todos los datos como marca, modelo, número de serie, accesorios y podrás indicar el problema que encontraste. Recuerda que, con tu DNI o RUC y el número de la orden, podrás consultar el estado de tu equipo en tiempo real y conocer en qué etapa se encuentra: <a href="https://soporte.krear3d.com/">https://soporte.krear3d.com/</a></li>
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
                <p>En promedio las fallas de los equipos se solucionan en una semana, ya que contamos con un amplio stock de repuestos e insumos de todas las marcas que representamos. De ser un caso más complejo, la primera etapa clave que es el diagnóstico que puede demorar hasta 10 días y posteriormente la etapa final de la reparación otros 10 días hábiles. Recuerda que, con tu DNI o RUC y el número de la orden, podrás consultar el estado de tu equipo a tiempo real, conocer qué técnico se está encargando, en qué etapa se encuentra: <a href="https://soporte.krear3d.com/">https://soporte.krear3d.com/</a>
                </p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">"¿Cuándo no podrás aplicar la garantía?"</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>Si no realizas una buena instalación o armado, puedes malograr tu equipo debido a una mala conexión, voltaje incorrecto, rotura de componentes, etc. Recuerda que en Perú es 220V la alimentación común. En caso de no manipularlo correctamente, produciendo daños, rayaduras, derrames de líquidos, etc. Ten cuidado con los equipos de resinas. Los componentes que se desgastan debido a la cantidad de horas que usas tu equipo, como boquillas, plataformas, pantallas, etc.</p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Qué recomendaciones debes seguir?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>Revisa los manuales de usuario, tutoriales, guía de inicio y las instrucciones provistas con el producto, porque los equipos son dispositivos electrónicos sofisticados. Debido a la alta precisión mecánica y piezas electrónicas, debes realizar un mantenimiento preventivo cada 3 meses para prevenir cualquier problema.</p>
            </div>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>

    <script src="./assets/js/efects.js"></script>
</body>

</html>