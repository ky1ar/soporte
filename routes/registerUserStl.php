<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $documento = trim($_POST["documento"]);
    $celular = trim($_POST["celular"]);
    $comprobante = trim($_POST["comprobante"]);

    if (!preg_match("/@gmail\.com$/", $correo)) {
        echo json_encode(["exito" => false, "mensaje" => "El correo debe ser de Gmail (@gmail.com)"]);
        exit;
    }
    if (!ctype_digit($documento)) {
        echo json_encode(["exito" => false, "mensaje" => "El DNI/RUC debe contener solo números"]);
        exit;
    }
    if (!ctype_digit($celular)) {
        echo json_encode(["exito" => false, "mensaje" => "Ingresa un número de celular válido"]);
        exit;
    }

    // Verificar si el correo o DNI ya existen
    $sql = "SELECT id FROM Users_stls WHERE correo = ? OR documento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $documento);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["exito" => false, "mensaje" => "El correo o DNI ya están registrados"]);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    // Insertar usuario en la BD
    $sql = "INSERT INTO Users_stls (nombre, correo, documento, celular, comprobante) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $correo, $documento, $celular, $comprobante);

    if ($stmt->execute()) {
        $destinatario = "adrianndc2023@gmail.com"; // 🔹 Reemplaza con el correo que debe recibir la notificación
        $asunto = "Solcitud de Acceso al Drive de STLs";
        $mensaje = "
        <html>
        <head><title>Nueva Solicitud</title></head>
        <body>
            <h3>Se ha registrado un nuevo usuario:</h3>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Correo:</strong> $correo</p>
            <p><strong>DNI/RUC:</strong> $documento</p>
            <p><strong>Celular:</strong> $celular</p>
            <p><strong>Comprobante:</strong> $comprobante</p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: web@soporte.krear3d.com" . "\r\n";

        if (mail($destinatario, $asunto, $mensaje, $headers)) {
            echo json_encode(["exito" => true, "mensaje" => "Registro exitoso y correo enviado"]);
        } else {
            echo json_encode(["exito" => true, "mensaje" => "Registro exitoso, pero fallo en el envío de correo"]);
        }
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al registrar"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
}
