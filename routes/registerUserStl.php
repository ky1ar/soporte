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
    // Verificar si el correo o DNI ya existen en la base de datos
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

    // Insertar el registro si las validaciones pasaron
    $sql = "INSERT INTO Users_stls (nombre, correo, documento, celular, comprobante) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $nombre, $correo, $documento, $celular, $comprobante);

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
