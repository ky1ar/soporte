<?php 
$currentPage = "Citas"; 
require_once 'includes/globals.php'; 
require_once 'includes/header.php'; 
require_once 'includes/admin.php';
?>
</head>
<body>
    <?php 
    require_once 'includes/topBar.php';
    require_once 'includes/navBar.php';  
    ?>
    <section id="ky1-sdo">
        <div class="ky1-wrp">
            <form id="frm-cli" class="sdo-crd">
                <img src="assets/img/ord.svg" alt="ico" width="64" height="64">
                <h1>Consultar Orden de Servicio</h1>
                <p>Introduce el número de orden y el documento tal cual se muestra en la orden de ingreso proporcionada.</p>
                <input name="orders" id="ky1-sor" type="text" placeholder="Número de Orden">
                <input name="document" id="ky1-sdc" type="text" placeholder="Documento ( DNI/RUC )">
                <div id="errorDiv"></div>
                <button type="submit" class="lgn-log">Consultar Estado</button>
            </form>
           
        </div>
    </section>
    <?php 
    require_once 'includes/footer.php';
    ?>
</body>
</html>