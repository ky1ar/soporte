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
        $headers_sistemas .= "From: Soporte Krear 3D <web@soporte.krear3d.com>" . "\r\n";

        mail($destinatario_sistemas, $asunto_sistemas, $mensaje_sistemas, $headers_sistemas);

        $destinatario_usuario = $correo;
        $asunto_usuario = "Gracias por Registrarte al Pack de 100 STLs K3D";
        $mensaje_usuario = '
        <html>
        <body style="margin: 0; padding: 0; background-color: rgb(240, 240, 240); style="font-family: Arial, Helvetica, sans-serif;"">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="center">
                <table role="presentation" width="960" cellspacing="0" cellpadding="0" border="0" style="background-color: #fdfdfdc9; margin-top: 30px;border-radius: 20px; overflow: hidden;">
                    <!-- Logo -->
                    <tr>
                    <td align="center" bgcolor="#ea7134" style="padding: 32px 0;">
                        <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/01/logo-bn11.png" width="192" alt="Logo" />
                    </td>
                    </tr>
                    <!-- Sección de bienvenida -->
                    <tr>
                    <td align="center" style="padding: 10px 0 0 0;">
                        <table role="presentation" width="50%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td valign="middle" width="50%" align="left">
                            <h1 style="margin: 0 0 14px 0; font-weight: 500; font-size: 24px; line-height: 1;">¡GRACIAS POR <span style="font-size: 36.8px;"><b>REGISTRARTE!</b></span></h1>
                            <p style="margin: 0 0 16px 0; line-height: 1.2;">Estamos revisando tu solicitud y si todo<br />
                                está en orden, pronto tendrás acceso<br />
                                al <span style="color: #ea7134;"><b>Pack de 100 STLs K3D.</b></span></p>
                            <p style="margin: 0; line-height: 1.2;"><b>Recibirás la invitación a tu correo registrado:</b></p>
                            <p style="margin: 0;">' . $correo . '</p>
                            </td>
                            <td valign="middle" width="50%" align="right">
                            <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/pack-stls-plant-k1.png" width="352" alt="Pack STLs" />
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                <!-- Sección con los clips -->
                <tr>
                    <td align="center">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                        <td width="128" height="40" align="center">
                            <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/plant-clip-x1.png" width="128" height="" alt="" style="display: block;">
                        </td>
                        <td bgcolor="#ffffff" align="center" style="padding: 32px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td align="center">
                                <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/plant-stls-adv-x1.png" width="160" alt="Advertencia" />
                                </td>
                                <td style="padding-left: 24px;">
                                <p style="margin: 8px 0; line-height: 1.2;">Si no recibes el correo en un <b>plazo de 48 horas</b>, es posible<br />
                                    que se encuentre un error en el ingreso de tus datos.</p>
                                <b>Te recomendamos revisarlos y registrarte nuevamente.</b>
                                </td>
                            </tr>
                            </table>
                        </td>
                        <td width="128" height="40" align="center">
                            <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/plant-clip-x2.png" width="128" height="" alt="" style="display: block;">
                        </td>
                        </tr>
                    </table>
                    </td>
                </tr>

                    <!-- Pie con redes sociales -->
                    <tr>
                    <td bgcolor="#182d36" align="center" style="color: white; padding: 32px;">
                        <p style="margin: 0 0 8px 0;">Si tienes una duda o consulta, contáctanos por nuestras redes sociales:</p>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td><a href="https://www.facebook.com/krear3d/" target="_blank"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2024/06/facebook_x.png" width="24" alt="Facebook" /></a></td>
                            <td width="8"></td>
                            <td><a href="https://www.instagram.com/krear3d_peru/" target="_blank"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2024/06/instagram_x.png" width="24" alt="Instagram" /></a></td>
                            <td width="8"></td>
                            <td><a href="https://www.tiktok.com/@krear3d_peru" target="_blank"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/logo-tiktok-v2.png" width="24" alt="TikTok" /></a></td>
                            <td width="8"></td>
                            <td><b>@krear3dperu</b>&nbsp;o correo:&nbsp;<b><a href="mailto:marketing@krear3d.com" style="color: white;">marketing@krear3d.com</a></b></td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                </td>
            </tr>
            </table>
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
