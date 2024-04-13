<?php
$currentPage = "Iniciar sesión";
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php';
require_once 'includes/common/header_admin.php';
?>
</head>

<body>
    <section id="login">
        <div class="modal">
            <div class="left">
                <img src="assets/img/logod.webp" alt="Perfil">
                <div>
                    <h1>Bienvenido</h1>
                    <p>Ingresa tus datos para iniciar sesión.</p>
                </div>
                <form id="loginForm">
                    <div>
                        <label for="email">Correo electrónico</label>
                        <input name="email" type="text" placeholder="Ingresa tu correo">
                    </div>
                    <div>
                        <label for="pass">Contraseña</label>
                        <input name="pass" type="password" placeholder="Ingresa tu contraseña">
                    </div>
                    <div id="errorDiv"></div>
                    <button type="submit">Iniciar sesión</button>
                </form>
            </div>
            <div class="right">
                <img src="assets/img/login.webp" alt="">
            </div>
        </div>
    </section>
</body>

</html>
