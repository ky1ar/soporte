<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Prueba</title>
</head>
<body>
    <form method="post" action="addOrder.php">
        <label for="order">Orden:</label>
        <input type="text" id="order" name="order" required><br><br>
        
        <label for="document">Documento:</label>
        <input type="text" id="document" name="document" required><br><br>
        
        <!-- <label for="clientID">ID del Cliente:</label>
        <input type="text" id="clientID" name="clientID" required><br><br> -->
        
        <label for="client">Cliente:</label>
        <input type="text" id="client" name="client" required><br><br>
        
        <label for="comments">Comentarios:</label>
        <input type="text" id="comments" name="comments"><br><br>
        
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <!-- <label for="changer">Changer:</label>
        <input type="text" id="changer" name="changer" required><br><br> -->
        
        <label for="phone">Teléfono:</label>
        <input type="tel" id="phone" name="phone" required><br><br>
        
        <label for="machine">Máquina:</label>
        <input type="text" id="machine" name="machine" required><br><br>
        
        <label for="machineID">ID de la Máquina:</label>
        <input type="text" id="machineID" name="machineID" required><br><br>
        
        <label for="date">Fecha:</label>
        <input type="date" id="date" name="date" required><br><br>
        
        <label for="worker">Trabajador:</label>
        <input type="text" id="worker" name="worker" required><br><br>
        
        <label for="type">Tipo:</label>
        <input type="text" id="type" name="type" required><br><br>
        
        <label for="origin">Origen:</label>
        <input type="text" id="origin" name="origin" required><br><br>
        
        <input type="submit" name="submit" value="Enviar">
    </form>
</body>
</html>
