<?php
$currentPage = "STL";
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>

    <section class="xbanner-stls">
        <img src="assets/img/stl-pc.webp">
    </section>

    <section class="stls">
        <div class="card-stl">
            <img src="assets/img/pika.png" alt="">
            <h1>OBJETO1</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos asperiores eligendi consequuntur soluta itaque fugiat debitis in esse odit.</p>
            <button>DESCARGA AQUÍ</button>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>