<?php
$servername = "localhost";
$username = "u809802095_ky1ar";
$password = "Knoeht6306*";
$database = "u809802095_support";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>