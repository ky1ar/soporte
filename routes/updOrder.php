<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['now_ord'])) {
    $now_ord = $_POST['now_ord'];   
    $now_stt = $_POST['now_stt'];
    $changer = $_POST['changer'];
    $notes = $_POST['notes'];
    $check = $_POST['check'];
    $now_stt++;
    $today = date('Y-m-d H:i:s');
    
    $sql = "UPDATE Orders SET state = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $now_stt, $now_ord);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
  
        $sql = "INSERT INTO Orders_Status (orders, stat, changer, dates, notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiss", $now_ord, $now_stt, $changer, $today, $notes);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $sql = "SELECT o.number, c.name, c.email, s.slug, s.description FROM Orders o INNER JOIN Users c ON o.client = c.id INNER JOIN Status s ON o.state = s.id WHERE o.id = $now_ord";

            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if( $check === 'true' ){ 
                    $para = $row['email'];
                    $asunto = $row['description'];
            
                    // Contenido HTML del mensaje
                    $mensaje = "
                    <table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' style='background-color: #f5f5f5; font-family: Arial; font-size: 16px;'>
                        <tbody>
                        <tr>
                            <td align='center' style='padding:40px 0 0 0;'>
                            <table border='0' cellpadding='0' cellspacing='0' width='600' style='background-color: #fff; border-radius: 8px; border: 1px solid #ebebeb;'>
                                <tbody>
                                <tr>
                                    <td align='center'>
                                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #213236; border-radius: 8px 8px 0 0; border-bottom: solid 8px #e55b27; width: 100%;'>
                                        <tbody>
                                        <tr>
                                            <td align='center' style='padding:20px 0 0 0;'>
                                            <img width='200' height='64' src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/krear3d.png' alt='Logo'>
                                            <img width='600' height='1' src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/pix.png' alt='Logo'>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </td>  
                                </tr>
    
                                <tr>
                                    <td align='center' style='padding:32px 24px;'>
                                    <table border='0' cellpadding='0' cellspacing='0' width='550'>
                                        <tbody>
                                        <tr> 
                                            <td align='center'>
                                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                <tbody>
                                                <tr>
                                                    <td align='center'>
                                                    <img src='https://ky1soporte.krear3d.com/assets/img/{$row['slug']}.png' alt='Logo' width='80' height='80'>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
    
                                        <tr>
                                            <td align='center'>
                                            <h1 style='font-size: 20px;line-height: 24px;margin: 0;margin-top: 32px;'>Hola {$row['name']},</h1>
                                            <p style='margin-top: 32px;'>Te informamos que el estado de tu equipo se ha actualizado, puedes revisarlo en el siguiente link, el número de Orden registrado es <b>{$row['number']}</b></p>
                                            <table border='0' cellpadding='0' cellspacing='0'>
                                                <tbody>
                                                <tr>
                                                    <td align='center' style='padding:16px'>
                                                    <table border='0' cellpadding='16' cellspacing='0' style='background-color: #e55b27; border-radius: 30px;'>
                                                        <tbody>
                                                        <tr>
                                                            <td align='center' style='padding:16px 24px;'>
                                                            <a href='http://soporte.krear3d.com' style='color: #ffffff;text-decoration: none;font-weight: 600;'>Consulta el estado de tu Orden</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
    
                                            <p style='margin: 24px 0; font-weight: 600; font-size: 14px;'>Atento a nuestras redes sociales:</p>
    
                                            <table border='0' cellpadding='12' cellspacing='8' style='border-collapse:initial;'>
                                                <tbody>
                                                <tr>
                                                    <td align='center' style='background-color:#213236;border-radius: 30px;'>
                                                    <a href='https://www.instagram.com/krear3d_peru/' target='_blank'><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/ins.png' width='20' height='20' alt='ico'></a>
                                                    </td>
                                                    <td align='center' style='background-color:#213236;border-radius: 30px;'>
                                                    <a href='https://www.tiktok.com/@krear3d_peru ' target='_blank'><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/tik.png' width='20' height='20' alt='ico'></a>
                                                    </td>
                                                    <td align='center' style='background-color:#213236;border-radius: 30px;'>
                                                    <a href=' https://facebook.com/krear3d/' target='_blank'><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/fbk.png' width='20' height='20' alt='ico'></a>
                                                    </td>
                                                    <td align='center' style='background-color:#213236;border-radius: 30px;'>
                                                    <a href='https://www.youtube.com/user/Krear3D' target='_blank'><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/you.png' width='20' height='20' alt='ico'></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>  
                                    </td>  
                                </tr>
    
                                <tr>
                                    <td align='center'>
                                    <table border='0' cellpadding='24' cellspacing='0' width='100%' style='background-color: #213236; border-radius: 0 0 8px 8px; border-top: solid 8px #e55b27; width: 100%;'>
                                        <tbody>
                                        <tr>
                                            <td align='center' style='width:100%;'>
                                            <p style='margin: 0; font-size: 13px; line-height: 20px;color: #bdbaba;'><b style='color: #fff;'>Atención al cliente:</b> Si tienes más dudas o consultas, envíanos fotos o videos de lo sucedido al número de soporte técnico vía WhatsApp <a href='https://api.whatsapp.com/send?phone=51970539751' style='color: #eb5b27;'><b>+51 970 539 751</b></a>. La atención es únicamente por chat, por el momento <b style='color: #fff;'>no se atienden llamadas</b>.</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </td>  
                                </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
    
                        <tr>
                            <td align='center'>
                                <table border='0' cellpadding='40' cellspacing='0' width='600'>
                                    <tbody>
                                        <tr>
                                            <td align='center' style='color:#8a8a8a;font-size: 12px;'>
                                                <p style='margin: 0;'>Krear 3D - Fabricaciones Digitales del Peru S.A.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>";
            
                    // Cabeceras para el correo electrónico
                    $cabeceras = "MIME-Version: 1.0" . "\r\n";
                    $cabeceras .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $cabeceras .= "From: Krear 3D - Soporte <web@soporte.krear3d.com>\r\n";
                    $cabeceras .= "Reply-To: soporte@krear3d.com\r\n";
            
                    // Envío del correo
                    $resultado = mail($para, $asunto, $mensaje, $cabeceras);
                    
                    // Verificar si el correo se envió correctamente
                    if ($resultado) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $agent = $_SERVER['HTTP_USER_AGENT'];

                        $ipReadable = inet_ntop(inet_pton($ip));
                        $ipv4 = null;
                        if (filter_var($ipReadable, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                            $ipv4 = $ipReadable;
                        } elseif (filter_var($ipReadable, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                            $ipv4 = inet_ntop(substr(inet_pton($ipReadable), 12));
                        }

                        $sql = "INSERT INTO History (users, orders, actions, ip, agent, other) VALUES (?, ?, 9, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("iisss", $changer, $now_ord, $ipv4, $agent, $now_stt);
                        $stmt->execute();
                
                        if ($stmt->affected_rows > 0) {
                            echo json_encode(array("success" => true));
                            exit();
                        } else {
                            echo json_encode(array("success" => false, "message" => "Orden no encontrada, verifica los datos."));
                            exit();
                        }
                    } 
                } else {
                    echo json_encode(array("success" => true));
                    exit();
                }
                
            }
        }
    }
}

$conn->close();
?>
