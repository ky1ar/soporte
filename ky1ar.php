<?php
require_once 'controller.php';
require_once 'db.php';

$controller = new Controller($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'];
    $postData = $_POST;

    $result = $controller->processRequest($action, $postData);

    echo json_encode($result);
}
?>
