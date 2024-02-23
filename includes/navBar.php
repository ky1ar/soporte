<header id="ky1-hdr">
    <div class="ky1-wrp">
        <a href="/"><img class="hdr-lgo" width="150" height="42" src="assets/img/logod.webp" alt="Logo Krear 3D"></a>
        <ul>
            <li><a <?php if($currentPage == 'Citas') echo 'class="active"'; ?> href="/">Consultar</a></li>
            <li><a <?php if($currentPage == 'Capacitaciones') echo 'class="active"'; ?> href="capacitaciones">Capacitaciones</a></li>
            <li><a <?php if($currentPage == 'Conocimiento') echo 'class="active"'; ?> href="#">Conocimiento</a></li>
            <li><a <?php if($currentPage == 'Cursos') echo 'class="active"'; ?> href="#">Cursos</a></li>
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