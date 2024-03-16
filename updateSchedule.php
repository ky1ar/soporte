<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    $scheduleId = $_POST['scheduleId'];
    $trainingWorker = $_POST['trainingWorker'];
    $meet = $_POST['meet'];

    $sql_email_and_name = "SELECT email, name FROM Training WHERE id = ?";
    $stmt_email_and_name = $conn->prepare($sql_email_and_name);
    $stmt_email_and_name->bind_param("i", $scheduleId);
    $stmt_email_and_name->execute();
    $stmt_email_and_name->bind_result($approved_email, $name);
    $stmt_email_and_name->fetch();
    $stmt_email_and_name->close();

    $sql_name = "SELECT name FROM Users WHERE id = ?";
    $stmt_name = $conn->prepare($sql_name);
    $stmt_name->bind_param("i", $trainingWorker);
    $stmt_name->execute();
    $stmt_name->bind_result($worker_name);
    $stmt_name->fetch();
    $stmt_name->close();

    $sql_date_and_time = "SELECT training_date, training_start FROM Training WHERE id = ?";
    $stmt_date_and_time = $conn->prepare($sql_date_and_time);
    $stmt_date_and_time->bind_param("i", $scheduleId);
    $stmt_date_and_time->execute();
    $stmt_date_and_time->bind_result($training_date, $training_start);
    $stmt_date_and_time->fetch();
    $stmt_date_and_time->close();

    $nombre_dia = date('l', strtotime($training_date));
    $nombre_mes = date('F', strtotime($training_date));
    $hora_minutos = date('H:i', strtotime($training_start));


    $sql = "UPDATE Training SET worker = ?, meet = ?, training_state = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $trainingWorker, $meet, $scheduleId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {

        $title = 'Krear 3D - Confirmación de Capacitación';
        $emailTemplate = './includes/template/approvedSchedule.html';
        $htmlContent = file_get_contents($emailTemplate);
        $placeholders = array('%CLIENT%', '%MEET%', '%WORKER%', '%DIA%', '%MES%', '%HORA%');
        $values = array($name, $meet, $worker_name, $nombre_dia, $nombre_mes, $hora_minutos);
        $htmlContent = str_replace($placeholders, $values, $htmlContent);

        $emailHeader = "MIME-Version: 1.0" . "\r\n";
        $emailHeader .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $emailHeader .= "From: Krear 3D - Soporte <web@soporte.krear3d.com>\r\n";
        $emailHeader .= "Reply-To: soporte@krear3d.com\r\n";

        $resultado = mail($approved_email, $title, $htmlContent, $emailHeader);

        if ($resultado) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Error al enviar el correo electrónico';
        }
    } else {
        $response['error'] = 'Error Interno';
    }
    $stmt->close();
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
