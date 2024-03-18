<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = array();

    try {
        $conn->begin_transaction();

        $trainingId = $_POST['trainingId'];

        $sql_date = "SELECT training_date FROM Training WHERE id = ?";
        $stmt_email = $conn->prepare($sql_date);
        $stmt_email->bind_param("i", $trainingId);
        $stmt_email->execute();
        $stmt_email->bind_result($training_date);
        $stmt_email->fetch();
        $stmt_email->close();


        $sql = "UPDATE Training SET training_state = 4 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta para actualizar la capacitación");
        }
        $stmt->bind_param("i", $trainingId);
        $stmt->execute();

        $result = $stmt->affected_rows;
        $stmt->close();
        if ($result <= 0) {
            throw new Exception("No se pudo actualizar el registro");
        }

        $sql = "UPDATE Calendar SET state = 1 WHERE calendar_date = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta para actualizar el calendario");
        }
        $stmt->bind_param("s", $training_date);
        $stmt->execute();

        $result = $stmt->affected_rows;
        $stmt->close();
        // if ($result <= 0) {
        //     throw new Exception("No se pudo actualizar el calendario");
        // }
        // Envío de correo electrónico
        $title = 'Krear 3D - Eliminación de Capacitación';
        $emailTemplate = './includes/template/rejectedSchedule.php';
        $htmlContent = file_get_contents($emailTemplate);

        $emailHeader = "MIME-Version: 1.0" . "\r\n";
        $emailHeader .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $emailHeader .= "From: Krear 3D - Soporte <web@soporte.krear3d.com>\r\n";
        $emailHeader .= "Reply-To: soporte@krear3d.com\r\n";

        $resultado = mail($rejected_email, $title, $htmlContent, $emailHeader);

        if ($resultado) {
            $response['success'] = true;
        } else {
            throw new Exception("Error al enviar el correo electrónico");
        }

        $conn->commit();
        echo json_encode($response);
    } catch (Exception $e) {
        $conn->rollback();

        $response['error'] = $e->getMessage();
        echo json_encode($response);
    } finally {
        $conn->close();
    }
} else {
    $response['error'] = "Solicitud no válida";
    echo json_encode($response);
}
