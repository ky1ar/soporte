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
    <section id="">
        <div class="">
            <img style="width: 100%;" height="630" src="assets/slider/banner-home.webp">
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
                <p class="question-text">¿Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet corrupti quidem optio1?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>1Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit itaque hic autem saepe quibusdam, quam ab expedita voluptatum repellat neque eum illum assumenda nostrum fuga temporibus numquam. Recusandae, id natus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis odit reiciendis, obcaecati assumenda eum natus tempore, laudantium ea alias architecto hic id voluptatem ratione est cumque nam aliquam praesentium voluptate?</p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet corrupti quidem optio2?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>2Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit itaque hic autem saepe quibusdam, quam ab expedita voluptatum repellat neque eum illum assumenda nostrum fuga temporibus numquam. Recusandae, id natus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis odit reiciendis, obcaecati assumenda eum natus tempore, laudantium ea alias architecto hic id voluptatem ratione est cumque nam aliquam praesentium voluptate?</p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet corrupti quidem optio3?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>3Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit itaque hic autem saepe quibusdam, quam ab expedita voluptatum repellat neque eum illum assumenda nostrum fuga temporibus numquam. Recusandae, id natus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis odit reiciendis, obcaecati assumenda eum natus tempore, laudantium ea alias architecto hic id voluptatem ratione est cumque nam aliquam praesentium voluptate?</p>
            </div>
        </div>

        <div class="faq">
            <div class="question">
                <p class="question-text">¿Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet corrupti quidem optio4?</p>
                <button class="collapsible" id="open"><img src="assets/img/row-down2.png" alt=""></button>
            </div>
            <div class="content">
                <p>4Lorem ipsum dolor sit amet consectetur, adipisicing elit. Velit itaque hic autem saepe quibusdam, quam ab expedita voluptatum repellat neque eum illum assumenda nostrum fuga temporibus numquam. Recusandae, id natus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis odit reiciendis, obcaecati assumenda eum natus tempore, laudantium ea alias architecto hic id voluptatem ratione est cumque nam aliquam praesentium voluptate?</p>
            </div>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>

    <script src="./assets/js/efects.js"></script>
</body>

</html>