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
            <h2>Título 1</h2>
            <p>Contenido del primer div.</p>
        </div>
        <div class="cur-box">
            <h2>Título 2</h2>
            <p>Contenido del segundo div.</p>
        </div>
        <div class="cur-box">
            <h2>Título 3</h2>
            <p>Contenido del tercer div.</p>
        </div>
        <div class="cur-box">
            <h2>Título 4</h2>
            <p>Contenido del cuarto div.</p>
        </div>
    </section>

    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>