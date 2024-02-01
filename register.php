<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="register_process.php" method="post">
        <label for="levels">Nivel:</label>
        <input type="text" id="levels" name="levels"><br><br>
        <label for="document">Documento:</label>
        <input type="text" id="document" name="document"><br><br>
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name"><br><br>
        <label for="phone">Teléfono:</label>
        <input type="text" id="phone" name="phone"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Registrarse">
    </form>
</body>
</html>
