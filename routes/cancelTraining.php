<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['trainingId'])) {
    $response = array();

    try {
        $conn->begin_transaction();

        $trainingId = $_POST['trainingId'];

        // Obtener la fecha y el tipo de programación (default o custom)
        $sql_training = "SELECT training_date, email, name, training_start FROM Training WHERE id = ?";
        $stmt_training = $conn->prepare($sql_training);
        $stmt_training->bind_param("i", $trainingId);
        $stmt_training->execute();
        $stmt_training->bind_result($training_date, $email, $name, $training_start);
        $stmt_training->fetch();
        $stmt_training->close();

        // Actualizar el estado de la capacitación
        $sql_update = "UPDATE Training SET training_state = 4 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        if (!$stmt_update) {
            throw new Exception("Error en la preparación de la consulta para actualizar la capacitación");
        }
        $stmt_update->bind_param("i", $trainingId);
        $stmt_update->execute();
        $result = $stmt_update->affected_rows;
        $stmt_update->close();

        if ($result <= 0) {
            throw new Exception("No se pudo actualizar el registro");
        }

        // Verificar si la programación es Default o Custom y actualizar el estado de la fecha
        $sql_check_custom = "SELECT id FROM Custom_Schedule WHERE t_date = ? AND h_start = ?";
        $stmt_check_custom = $conn->prepare($sql_check_custom);
        $stmt_check_custom->bind_param("ss", $training_date, $training_start);
        $stmt_check_custom->execute();
        $stmt_check_custom->store_result();
        $is_custom_schedule = $stmt_check_custom->num_rows > 0;
        $stmt_check_custom->close();

        if ($is_custom_schedule) {
            // Lógica para Custom_Schedule
            $sql_check_available_custom = "SELECT COUNT(*) as count FROM Custom_Schedule cs 
                                           LEFT JOIN Training t ON cs.h_start = t.training_start AND cs.t_date = t.training_date 
                                           WHERE cs.t_date = ? AND (t.id IS NULL OR t.training_state = 4)";
            $stmt_check_available_custom = $conn->prepare($sql_check_available_custom);
            $stmt_check_available_custom->bind_param("s", $training_date);
            $stmt_check_available_custom->execute();
            $result_check_available_custom = $stmt_check_available_custom->get_result();
            $row_check_available_custom = $result_check_available_custom->fetch_assoc();
            $available_custom = $row_check_available_custom['count'];
            $stmt_check_available_custom->close();

            if ($available_custom > 0) {
                // Si hay horarios disponibles, actualizar el estado de la fecha en Calendar
                $sql_calendar = "UPDATE Calendar SET state = 1 WHERE calendar_date = ?";
                $stmt_calendar = $conn->prepare($sql_calendar);
                if (!$stmt_calendar) {
                    throw new Exception("Error en la preparación de la consulta para actualizar el calendario");
                }
                $stmt_calendar->bind_param("s", $training_date);
                $stmt_calendar->execute();
                $stmt_calendar->close();
            }
        } else {
            // Lógica para Default_Schedule (ya implementada)
            $sql_calendar = "UPDATE Calendar SET state = 1 WHERE calendar_date = ?";
            $stmt_calendar = $conn->prepare($sql_calendar);
            if (!$stmt_calendar) {
                throw new Exception("Error en la preparación de la consulta para actualizar el calendario");
            }
            $stmt_calendar->bind_param("s", $training_date);
            $stmt_calendar->execute();
            $stmt_calendar->close();
        }

        // Envío de correo y confirmación de éxito
        $title = 'Eliminación de Capacitación';
        $emailTemplate = '../includes/template/cancelTraining.html';
        $htmlContent = file_get_contents($emailTemplate);
        $placeholders = array('%CLIENT%',);
        $values = array($name);
        $htmlContent = str_replace($placeholders, $values, $htmlContent);

        $emailHeader = "MIME-Version: 1.0" . "\r\n";
        $emailHeader .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $emailHeader .= "From: Krear 3D<web@soporte.krear3d.com>\r\n";
        $emailHeader .= "Reply-To: soporte@krear3d.com\r\n";

        $resultado = mail($email, $title, $htmlContent, $emailHeader);

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
