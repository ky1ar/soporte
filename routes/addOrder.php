<?php
require_once '../includes/app/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order'])) {

    $order      = $_POST['order'];
    $document   = $_POST['document'];
    $clientID   = $_POST['clientID'];
    $client     = $_POST['client'];
    $comments   = $_POST['comments'];
    $email      = $_POST['email'];
    $changer    = $_POST['changer'];
    $phone      = $_POST['phone'];
    $machine    = $_POST['machine'];
    $machineID  = $_POST['machineID'];
    $date       = $_POST['date'];
    $dateF      = date('Y-m-d H:i:s', strtotime($date . ' 00:00:00'));
    $worker     = $_POST['worker'];
    $type       = $_POST['type'];
    $origin     = $_POST['origin'];

    $phone = str_replace(' ', '', $phone);

    if (strpos($phone, '+') === 0) {
        $phone = substr($phone, 1);
    }
    if (strpos($phone, '51') !== 0 && preg_match('/^9\d{8}$/', $phone)) {
        $phone = '51' . $phone;
    }


    if ($clientID == '') {

        $sql = "INSERT INTO Users (levels, document, name, nick, role, image, phone, email, pass) VALUES (1, '$document', '$client', '', '', '', '$phone', '$email', 'password')";
        if ($conn->query($sql) === TRUE) {

            $stmt = $conn->prepare("SELECT id FROM Users WHERE document = ?");
            $stmt->bind_param("s", $document);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $clientID = $row['id'];
        }
    }
    //echo $dateF;
    $sql = "INSERT INTO Orders (number, machine, client, worker, type, origin, state, comments, dates) VALUES ('$order', $machineID, $clientID, $worker, $type, $origin, 1, '$comments', '$dateF')";

    if ($conn->query($sql) === TRUE) {

        $stmt = $conn->prepare("SELECT id FROM Orders WHERE number = ?");
        $stmt->bind_param("s", $order);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $ordersID = $row['id'];
    }

    $sql = "INSERT INTO Orders_Status (orders, stat, changer, dates ) VALUES ($ordersID, 1, $changer, '$dateF')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => true));
        exit();
    } /*else {
        echo "Error al insertar datos: " . $conn->error;
    }*/
}


$conn->close();
