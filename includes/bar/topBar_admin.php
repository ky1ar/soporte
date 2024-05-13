<div id="topBar">
    <div class="wrapper">
        <a href="/grid" class="home"><img src="assets/img/home.svg" width="16" height="16" alt="ico">Krear 3D</a>
        <div id="userMenu">
            <span>Hola, <?= $s_name ?></span>
            <img alt="" class="image" src="assets/img/profile.webp" width="16" height="16">
            <div id="userMenuExtented">
                <div class="content">
                    <img alt="" src="assets/img/profile.webp" width="80" height="80">
                    <div>
                        <span><?= $s_name ?></span>
                        <p><?= $s_role ?></p>
                    </div>
                    <a href="proLogout">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
