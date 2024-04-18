<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    $schedule = $_POST['schedule'];
    $date = $_POST['date'];
    $count = $_POST['count'];

    $dniRUC = $_POST['dniRUC'];
    $client = $_POST['client'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $machineId = $_POST['machineId'];

    $meet = $_FILES['meet'];
    $phone = str_replace(' ', '', $phone);

    if (strpos($phone, '+') === 0) {
        $phone = substr($phone, 1);
    }
    if (strpos($phone, '51') !== 0 && preg_match('/^9\d{8}$/', $phone)) {
        $phone = '51' . $phone;
    }
    
    $sqlgetWorker = "SELECT id, name FROM Users WHERE (levels = 2 OR levels = 3) ORDER BY levels DESC LIMIT 1";
    $getWorker = $conn->prepare($sqlgetWorker);
    $getWorker->execute();
    $getWorkerResult = $getWorker->get_result();
    $getWorkerRow = $getWorkerResult->fetch_assoc();
    $worker_name = $getWorkerRow['name'];
    $worker = $getWorkerRow['id'];

    $newDate = new DateTime($date);
    $day = $newDate->format('d');
    $meses = array(
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    );
    $month = $meses[intval($newDate->format('m')) - 1];
    $year = $newDate->format('Y');
    
    $stmt_date_and_time->bind_result($numero_dia, $nombre_dia, $nombre_mes, $hora_minutos);
  

    $conn->begin_transaction();

    try {

        $stmt = $conn->prepare("SELECT COUNT(*) as count
        FROM Training
        WHERE training_date = ?
        AND training_start = ?
        AND (training_state != 3 AND training_state != 4)");
        $stmt->bind_param("ss", $date, $schedule);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $existingCount = $row['count'];
        $stmt->close();

        if ($existingCount > 0) {
            $response['error'] = "Ya existe una capacitación registrada para esta fecha y horario";
            echo json_encode($response);
            exit();
        }

        $sqlgetWorker = "SELECT id, name FROM Users WHERE (levels = 2 OR levels = 3) ORDER BY levels DESC LIMIT 1";
        $getWorker = $conn->prepare($sqlgetWorker);
        $getWorker->execute();
        $getWorkerResult = $getWorker->get_result();
        $getWorkerRow = $getWorkerResult->fetch_assoc();
        $worker = $getWorkerRow['id'];

        $sql = "INSERT INTO Training (machine, worker, document, name, phone, email, training_date, training_start, training_state, meet) VALUES (?, $worker, ?, ?, ?, ?, ?, ?, 1, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $machineId, $dniRUC, $client, $phone, $email, $date, $schedule, $meet);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        if ($count == 1) {
            $sql = "UPDATE Calendar SET state = 0 WHERE calendar_date = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $date);
            $stmt->execute();
            $stmt->close();
        }

        $title = 'Capacitación Agendada';
        $emailTemplate = '../includes/template/approvedSchedule.html';
        $htmlContent = file_get_contents($emailTemplate);
        $placeholders = array('%CLIENT%', '%MEET%', '%WORKER%', '%DIA%','%DIA_N%', '%MES%', '%HORA%');
        $values = array($client, $meet, $worker_name, $nombre_dia, $numero_dia, $nombre_mes, $hora_minutos);
        $htmlContent = str_replace($placeholders, $values, $htmlContent);

        $emailHeader = "MIME-Version: 1.0" . "\r\n";
        $emailHeader .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $emailHeader .= "From: Krear 3D<soporte@krear3d.com>\r\n";
        $emailHeader .= "Reply-To: soporte@krear3d.com\r\n";

        $resultado = mail($approved_email, $title, $htmlContent, $emailHeader);

        if ($resultado) {
            $response['success'] = true;
            echo json_encode($response);
            exit();
        } else {
            $response['error'] = 'Error al enviar el correo electrónico';
            echo json_encode($response);
            exit();
        }
    } catch (Exception $e) {

        $conn->rollback();
        //$response = array("error" => "Error en el registro: " . $e->getMessage());
        $response['error'] = 'Error Interno';
        echo json_encode($response);
        exit();
    }
}
