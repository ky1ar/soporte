<?php
$currentPage = "Test";
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
            <img style="width: 100%;" height="630" src="assets/slider/banner_basec.webp">
        </div>
    </section>
    <section class="sec-fdm">
        <div class="et">
            <p>TECNOLOGÍA FDM</p>
        </div>
        <div class="carousel-container">
            <div class="carousel">
                <div class="carousel-item">
                    <div class="card">
                        <div class="card-image">
                            <img src="assets/img/test.jpg" alt="Imagen de ejemplo">
                        </div>
                        <div class="card-content">
                            <div class="card-text">
                                <img class="icon-search" src="assets/img/search_y1.png" alt="">
                                <p class="small-text">26 mayo 2024</p>
                            </div>
                            <div class="card-description">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda, autem nesciunt rerum error dolor, libero earum expedita nihil illo tempo</p>
                                <p class="truncate">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad aspernatur dolor natus, alias animi vitae quisquam illum. Suscipit id aliquid at molestiae, ipsa placeat rem maxime, hic quod explicabo deserunt.</p>
                            </div>
                            <button class="card-button">Leer más</button>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card">
                        <div class="card-image">
                            <img src="assets/img/test.jpg" alt="Imagen de ejemplo">
                        </div>
                        <div class="card-content">
                            <div class="card-text">
                                <img class="icon-search" src="assets/img/search_y1.png" alt="">
                                <p class="small-text">26 mayo 2024</p>
                            </div>
                            <div class="card-description">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda, autem nesciunt rerum error dolor, libero earum expedita nihil illo tempo</p>
                                <p class="truncate">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad aspernatur dolor natus, alias animi vitae quisquam illum. Suscipit id aliquid at molestiae, ipsa placeat rem maxime, hic quod explicabo deserunt.</p>
                            </div>
                            <button class="card-button">Leer más</button>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="card">
                        <div class="card-image">
                            <img src="assets/img/test.jpg" alt="Imagen de ejemplo">
                        </div>
                        <div class="card-content">
                            <div class="card-text">
                                <img class="icon-search" src="assets/img/search_y1.png" alt="">
                                <p class="small-text">26 mayo 2024</p>
                            </div>
                            <div class="card-description">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda, autem nesciunt rerum error dolor, libero earum expedita nihil illo tempo</p>
                                <p class="truncate">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad aspernatur dolor natus, alias animi vitae quisquam illum. Suscipit id aliquid at molestiae, ipsa placeat rem maxime, hic quod explicabo deserunt.</p>
                            </div>
                            <button class="card-button">Leer más</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="prev" onclick="prevSlide()">Anterior</button>
            <button class="next" onclick="nextSlide()">Siguiente</button>
        </div>
    </section>

    <?php require_once 'includes/common/footer.php'; ?>

    <script src="./assets/js/efects.js"></script>
</body>

</html>