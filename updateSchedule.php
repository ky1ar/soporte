<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();

    $scheduleId = $_POST['scheduleId'];
    $trainingWorker = $_POST['trainingWorker'];
    $meet = $_POST['meet'];

    echo "scheduleId: " . $scheduleId . "<br>";
    echo "trainingWorker: " . $trainingWorker . "<br>";
    echo "meet: " . $meet . "<br>";

    $sql_email = "SELECT email FROM Training WHERE id = ?";
    $stmt_email = $conn->prepare($sql_email);
    $stmt_email->bind_param("i", $scheduleId);
    $stmt_email->execute();
    $stmt_email->bind_result($approved_email);
    $stmt_email->fetch();
    $stmt_email->close();

    $sql_name = "SELECT name FROM Users WHERE id = ?";
    $stmt_name = $conn->prepare($sql_name);
    $stmt_name->bind_param("i", $trainingWorker);
    $stmt_name->execute();
    $stmt_name->bind_result($worker_name);
    $stmt_name->fetch();
    $stmt_name->close();


    $sql = "UPDATE Training SET worker = ?, meet = ?, training_state = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $trainingWorker, $meet, $scheduleId);
    $stmt->execute();

    echo "La actualización se realizó correctamente. $affected_rows";
    if ($stmt->affected_rows > 0) {
        $title = 'Krear 3D - Confirmación de Capacitación';
        $emailTemplate = './includes/template/approvedSchedule.php';
        $htmlContent = file_get_contents($emailTemplate);

        $emailHeader = "MIME-Version: 1.0" . "\r\n";
        $emailHeader .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $emailHeader .= "From: Krear 3D - Soporte <web@soporte.krear3d.com>\r\n";
        $emailHeader .= "Reply-To: soporte@krear3d.com\r\n";

        $resultado = mail($approved_email, $title, $htmlContent, $emailHeader);

        if ($resultado) {
            $response['success'] = true;
            echo json_encode($response);
        } else {
            $response['error'] = 'Error al enviar el correo electrónico';
            echo json_encode($response);
        }
    } else {
        echo "No se pudo realizar la actualización correctamente.";
        $response['error'] = 'Error Interno';
        echo json_encode($response);
    }

    $stmt->close();
    exit();
}
