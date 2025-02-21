<?php
require_once '../includes/app/db.php';;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $documento = $_POST["documento"];
    $celular = $_POST["celular"];
    $comprobante = $_POST["comprobante"];


    $sql = "INSERT INTO Users_stls (nombre, correo, documento, celular, comprobante) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiis", $nombre, $correo, $documento, $celular, $comprobante);

    if ($stmt->execute()) {
        echo json_encode(["exito" => true, "mensaje" => "Registro exitoso"]);
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Error al registrar"]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
}
