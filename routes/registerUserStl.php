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
        <body>
            <div class="plant-silv" style="background-color: rgb(241, 241, 241); width: 960px; margin: auto; border-radius: 16px; display: flex; align-items: center; flex-direction: column; overflow: hidden;">
            <div class="top" style="background-color: #ea7134; width: 100%; display: flex;">
                <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/01/logo-bn11.png" alt="" style="width: 192px; margin: 32px auto;" />
            </div>
            <div class="sec1" style="display: flex; align-items: center; justify-content: center; padding-top: 16px;">
                <div class="txt" style="width: 272px;">
                <h1 style="margin: 0; font-weight: 500;">¡GRACIAS POR <span style="font-size: 36.8px;"><b>REGISTRARTE!</b></span></h1>
                <p class="p1" style="margin: 8px 0;">Estamos revisando tu solicitud y si todo<br />
                    esta en orden, pronto tendrás acceso<br />
                    al <span style="color: #ea7134;"><b>Pack de 100 STLs K3D.</b></span></p>
                <p class="p2" style="margin: 8px 0 0 0; white-space: nowrap;"><b>Recibirás la invitación a tu correo registrado:</b></p>
                <p class="email" style="margin: 0;">' . $correo . '</p>
                </div>
                <div class="part2" style="z-index: 1;">
                <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/pack-stls-plant-k1.png" alt="" style="width: 352px; margin-bottom: -48px;" />
                </div>
            </div>
            <div class="sec2" style="background-color: white; align-items: center; justify-content: center; width: 100%; position: relative;">
                <div class="clip1" style="width: 128px; height: 80px; border-radius: 0 16px 16px 0; background-color: #ea7134; position: absolute; top: 50%; transform: translateY(-50%);"></div>
                <div class="cont" style="display: flex; align-items: center; justify-content: center; gap: 48px; padding: 32px 0;">
                <div class="adv">
                    <img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/plant-stls-adv-x1.png" alt="" style="width: 160px;" />
                </div>
                <div class="txt" style="margin-top: -32px;">
                    <p style="margin: 8px 0;">Si no recibes el correo en un <b>plazo de 48 horas</b>, es posible<br />
                    que se encuentre un error en el ingreso de tus datos.</p>
                    <b>Te recomendamos revisarlos y registrarte nuevamente.</b>
                </div>
                </div>
                <div class="clip2" style="width: 128px; height: 80px; border-radius: 16px 0 0 16px; background-color: #ea7134; position: absolute; top: 50%; transform: translateY(-50%); right: 0;"></div>
            </div>
            <div class="sec3" style="background-color: #182d36; width: 100%; color: white; text-align: center; padding: 32px;">
                <p style="margin: 0 0 8px 0;">Si tienes una duda o consulta, contáctanos por nuestras redes sociales:</p>
                <div class="redes" style="display: flex; align-items: center; justify-content: center;">
                <a href="https://www.facebook.com/krear3d/" target="_blank" style="text-decoration: none; color: white;"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2024/06/facebook_x.png" alt="" style="width: 24px; margin-right: 8px;" /></a>
                <a href="https://www.instagram.com/krear3d_peru/" target="_blank" style="text-decoration: none; color: white;"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2024/06/instagram_x.png" alt="" style="width: 24px; margin-right: 8px;" /></a>
                <a href="https://www.tiktok.com/@krear3d_peru" target="_blank" style="text-decoration: none; color: white;"><img src="https://www.tiendakrear3d.com/wp-content/uploads/2025/02/logo-white-tiktok.png" alt="" style="width: 24px; margin-right: 8px;" /></a>
                <b>@krear3dperu</b>&nbsp;o correo:&nbsp;<b><a href="mailto:marketing@krear3d.com" style="text-decoration: none; color: white;">marketing@krear3d.com</a></b>
                </div>
            </div>
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
