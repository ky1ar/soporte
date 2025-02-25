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
        // 🟢 1️⃣ Correo para sistemas@krear3d.com
        $destinatario_sistemas = "sistemas@krear3d.com";
        $asunto_sistemas = "Solicitud de Acceso al Drive de STLs";
        $mensaje_sistemas = '
        <html>
        <body>
            <div style="background-color: rgb(231, 231, 231); width: 480px; margin: auto; border-radius: 16px; padding: 16px;">
                <h3 style="text-align: center;">Datos del Nuevo Usuario Registrado</h3>
                <p><strong>Nombre:</strong> ' . $nombre . '</p>
                <p><strong>Correo:</strong> ' . $correo . '</p>
                <p><strong>DNI/RUC:</strong> ' . $documento . '</p>
                <p><strong>Celular:</strong> ' . $celular . '</p>
                <p><strong>Comprobante:</strong> ' . $comprobante . '</p>
            </div>
        </body>
        </html>';

        $headers_sistemas = "MIME-Version: 1.0" . "\r\n";
        $headers_sistemas .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers_sistemas .= "From: Soporte Krear3D <web@soporte.krear3d.com>" . "\r\n";

        mail($destinatario_sistemas, $asunto_sistemas, $mensaje_sistemas, $headers_sistemas);

        // 🟢 2️⃣ Correo para el usuario registrado ($correo)
        $destinatario_usuario = $correo;
        $asunto_usuario = "Bienvenido a Krear3D - Acceso al Drive de STLs";
        $mensaje_usuario = '
        <html>
        <body>
            <div style="background-color: rgb(210, 250, 210); width: 480px; margin: auto; border-radius: 16px; padding: 16px;">
                <h3 style="text-align: center;">¡Bienvenido a Krear3D!</h3>
                <p>Hola <strong>' . $nombre . '</strong>,</p>
                <p>Gracias por registrarte para acceder a nuestros archivos STLs.</p>
                <p>Tu solicitud será revisada y pronto recibirás una confirmación.</p>
                <p>Si tienes dudas, contáctanos en <strong>soporte@krear3d.com</strong>.</p>
                <p style="text-align: center;">¡Gracias por confiar en nosotros!</p>
            </div>
        </body>
        </html>';

        $headers_usuario = "MIME-Version: 1.0" . "\r\n";
        $headers_usuario .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers_usuario .= "From: Soporte Krear3D <web@soporte.krear3d.com>" . "\r\n";

        mail($destinatario_usuario, $asunto_usuario, $mensaje_usuario, $headers_usuario);

        echo json_encode(["exito" => true, "mensaje" => "Registro exitoso y correos enviados"]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al registrar"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
}

