<header id="navigationBar">
    <div class="wrapper">
        <a href="/"><img class="logo" width="150" height="42" src="assets/img/logod.webp" alt="Logo Krear 3D"></a>
        <ul>
            <li><a <?php if($currentPage == 'Soporte Técnico') echo 'class="active"'; ?> href="/grid">Soporte Técnico</a></li>
            <li><a <?php if($currentPage == 'Capacitaciones') echo 'class="active"'; ?> href="training">Capacitaciones</a></li>
            <?php if( $s_levels == 3 || $s_levels == 4 ):?>
                <li><a <?php if($currentPage == 'Historial') echo 'class="active"'; ?> href="report">Historial</a></li>
                <li><a <?php if($currentPage == 'Técnicos') echo 'class="active"'; ?> href="#">Técnicos</a></li>
                <li><a <?php if($currentPage == 'Clientes') echo 'class="active"'; ?> href="#">Clientes</a></li>
            <?php endif;?>
        </ul>
        <a class="link" href="/">Ayuda<img class="hdr-wsp" width="16" height="16" src="assets/img/home.svg" alt="ico"></a>
    </div>
</header>