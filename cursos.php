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

    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>