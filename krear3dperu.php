<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: krear3dperu");
    exit();
}

$s_id = $_SESSION['user_id'];
$s_levels = $_SESSION['user_levels'];
$s_name = $_SESSION['user_name'];
$s_nick = $_SESSION['user_nick'];
$s_role = $_SESSION['user_role'];

$currentPage = "Taller"; 
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php'; 
require_once 'includes/common/header_admin.php';
$stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin']; 
?>
</head>
<body>
    <section id="ky1-lgn">
        <div class="ky1-cnt">
            <div class="lgn-lft">
                <div class="lgn-flx">
                    <h1>Bienvenido nuestro portal de Soporte Técnico</h1>
                    <h3>Accede y podrás</h3>
                    <ul>
                        <li>Comunicarte con nuestros técnicos</li>
                        <li>Realizar seguimiento de tus órdenes</li>
                    </ul>
                    <p>Recuerda que al usar nuestro portal estas aceptando nuestras Políticas y Condiciones de nuestro sitio.</p>
                </div>
            </div>
            <form id="frm-log" class="lgn-rgt">
                <img src="assets/img/logod.webp" alt="Perfil">
                <p>Ingresa tus datos para iniciar sesión.</p>
                <input name="email" type="text" placeholder="Correo electrónico">
                <input name="pass" type="password" placeholder="Contraseña">
                <!--<a href="#">He olvidado mi contraseña</a>-->
                <div id="errorDiv"></div>
                <button type="submit" class="lgn-log">Ingresar</button>
                <!--<a class="lgn-rgt" href="/">Regístrate</a>-->
            </form>
            
        </div>
    </section>
</body>
</html>