<header id="navigationBar">
    <div class="wrapper">
        <a href="/"><img class="logo" width="150" height="42" src="assets/img/logod.webp" alt="Logo Krear 3D"></a>
        <ul>
            <li><a <?php if($currentPage == 'Taller') echo 'class="active"'; ?> href="/">Taller</a></li>
            <li><a <?php if($currentPage == 'Dashboard') echo 'class="active"'; ?> href="reporte">Dashboard</a></li>
            <li><a <?php if($currentPage == 'Capacitaciones') echo 'class="active"'; ?> href="training">Capacitaciones</a></li>
        </ul>
        <a class="link" href="/">Soporte<img class="hdr-wsp" width="16" height="16" src="assets/img/home.svg" alt="ico"></a>
    </div>
</header>