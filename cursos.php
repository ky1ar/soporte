<?php
$currentPage = "Test2";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/conocimiento.css">

</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>
    <section id="">
        <div class="">
            <img style="width: 100%;" height="630" src="assets/slider/banner-cursos.webp">
        </div>
    </section>
    <section class="cur">
        <div class="cur-box">
            <h2>100% ONLINE</h2>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Qui beatae sit molestiae, magni, quis deserunt saepe vero non, ipsum ad debitis venia</p>
        </div>
        <div class="cur-box">
            <h2>TIEMPO</h2>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Qui beatae sit molestiae, magni, quis deserunt saepe vero non, ipsum ad debitis venia</p>
        </div>
        <div class="cur-box">
            <h2>METODOLOGÍA</h2>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Qui beatae sit molestiae, magni, quis deserunt saepe vero non, ipsum ad debitis venia</p>
        </div>
        <div class="cur-box">
            <h2>GRATUITO</h2>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Qui beatae sit molestiae, magni, quis deserunt saepe vero non, ipsum ad debitis venia</p>
        </div>
    </section>
    <section class="cur2">
        <div class="cur2-p">
            <div class="left-stack">
                <div>
                    <img src="imagen1.jpg" alt="Imagen 1">
                </div>
                <div>
                    <img src="imagen2.jpg" alt="Imagen 2">
                </div>
                <div>
                    <img src="imagen3.jpg" alt="Imagen 3">
                </div>
            </div>
            <div class="right-column">
                <div>
                    <h2>Título 1</h2>
                    <p>Contenido del primer div.</p>
                </div>
                <div>
                    <h2>Título 2</h2>
                    <p>Contenido del segundo div.</p>
                </div>
                <div>
                    <h2>Título 3</h2>
                    <p>Contenido del tercer div.</p>
                </div>
            </div>
        </div>
        <div class="cur2-p">
            <div class="left-stack">
                <div>
                    <img src="imagen1.jpg" alt="Imagen 1">
                </div>
                <div>
                    <img src="imagen2.jpg" alt="Imagen 2">
                </div>
                <div>
                    <img src="imagen3.jpg" alt="Imagen 3">
                </div>
            </div>
            <div class="right-column">
                <div>
                    <h2>Título 1</h2>
                    <p>Contenido del primer div.</p>
                </div>
                <div>
                    <h2>Título 2</h2>
                    <p>Contenido del segundo div.</p>
                </div>
                <div>
                    <h2>Título 3</h2>
                    <p>Contenido del tercer div.</p>
                </div>
            </div>
        </div>
    </section>


    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>