<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/fav.png">
    <title>Krear 3D - Soporte Técnico</title>
    <?php require_once 'header.php'; ?>
</head>
<body>
    <div id="ky1-bar">
        <div class="ky1-wrp bar-enl"> 
            <a href="https://api.whatsapp.com/send?phone=51934760404" target="_blank" rel="nofollow"><img src="assets/img/wsp.svg" width="16" height="16" alt="ico">934 760 404</a>
            <a href="https://api.whatsapp.com/send?phone=51982001288" target="_blank" rel="nofollow"><img src="assets/img/wsp.svg" width="16" height="16" alt="ico">982 001 288</a>
            <a href="mailto:ventas2@krear3d.com" target="_blank" rel="nofollow"><img src="assets/img/eml.svg" width="16" height="16" alt="ico">ventas2@krear3d.com</a>
            <a href="https://bit.ly/2ZzWUeK" target="_blank" rel="nofollow"><img src="assets/img/map.svg" width="16" height="16" alt="ico">Calle Javier Fernández 262 Miraflores - Lima</a>
        </div>
    </div>

    <header id="ky1-hdr">
        <div class="ky1-wrp">
            <a href="/"><img class="hdr-lgo" width="150" height="42" src="assets/img/logod.webp" alt="Logo Krear 3D"></a>
            <ul>
                <li><a href="#">Consultar</a></li>
                <li><a href="capacitaciones">Capacitaciones</a></li>
                <li><a href="#">Conocimiento</a></li>
                <li><a href="#">Cursos</a></li>
            </ul>
            <a class="hdr-lnk" href="https://tiendakrear3d.com/">Tienda<img class="hdr-wsp" width="16" height="16" src="assets/img/tnd.svg" alt="ico"></a>
            <?php
            /*<div class="hdr-lft">
                <a href="admin.php">Administrador</a>
                <a href="login.php">Login</a>
            </div>*/
            ?>
        </div>
    </header>
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

    <footer>
        <div class="ftr-top">
            <div class="ky1-wrp">
                <div class="ftr-itm">
                    <img class="ftr-lgo" width="152" height="38" src="assets/img/logo.webp" alt="Krear 3D">
                    <ul>
                        <li><p>Calle Javier Fernandez - Miraflores - Lima</p></li>
                        <li><p>Lu - Sa de 9:00 am a 6:00 pm</p></li>
                    </ul>
                </div>
    
                <div class="ftr-itm">
                    <b>ATENCION AL CLIENTE</b>
                    <ul>
                        <li><a href="mailto:atencionalcliente@krear3d.com" target="_blank" rel="nofollow noopener">atencionalcliente@krear3d.com</a></li>
                        <li><p>Lu - Vi de 9:00 am a 6:00 pm</p></li>
                        <li><a href="https://api.whatsapp.com/send?phone=51981104030" target="_blank" rel="nofollow noopener"><img src="assets/img/wsp.svg" alt="Whatsapp" width="12" height="12">&nbsp;981 104 030</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="ftr-bot">
            <div class="ky1-wrp">
                <ul>
                    <li><a href="/terminos/terminos-condiciones-y-garantia/">POLÍTICAS Y CONDICIONES</a></li>
                    <li><a href="/terminos/politicas-de-garantia-y-soporte-tecnico/">POLÍTICAS DE GARANTÍA</a></li>
                    <li><a href="/terminos/politicas-de-envios-lima-y-provincias/">POLÍTICAS DE ENVÍOS</a></li>
                </ul>
                
                <span class="ftr-cpy">Fabricaciones Digitales del Perú S.A. | RUC 20556316890<br>Krear 3D © 2023. Todos los derechos reservados.</span>
            </div>
        </div>
    </footer>
</body>
</html>