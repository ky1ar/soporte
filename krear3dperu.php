<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/fav.png">
    <title>Krear 3D - Soporte Técnico</title>
    <?php require_once 'header.php'; ?>
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