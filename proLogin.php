<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email  = $_POST['email'];
    $pass   = $_POST['pass'];

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['pass'])) {

            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];

            $ipReadable = inet_ntop(inet_pton($ip));
            $ipv4 = null;
            if (filter_var($ipReadable, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ipv4 = $ipReadable;
            } elseif (filter_var($ipReadable, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $ipv4 = inet_ntop(substr(inet_pton($ipReadable), 12));
            }

            $sql = "INSERT INTO History (users, actions, ip, agent) VALUES (?, 1, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $row['id'], $ipv4, $agent);
            $stmt->execute();


            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_levels'] = $row['levels'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_nick'] = $row['nick'];
            $_SESSION['user_role'] = $row['role'];

            echo json_encode(array("success" => true));
            exit();

        } else {
            echo json_encode(array("success" => false, "message" => "Contraseña incorrecta"));
            exit();
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Usuario no encontrado"));
        exit();
    }
}
$conn->close();
?>
