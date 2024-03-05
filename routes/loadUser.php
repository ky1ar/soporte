<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document'])) {
    try {
        $document = $_POST['document'];
        $response = [];

        if (!preg_match('/^\d{8}$|^d{11}$/', $document)) {
            throw new InvalidArgumentException('Invalid document format. Only digits are allowed.');
        }

        $sql = "SELECT name, email, phone, id FROM Users WHERE document = :document";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':document', $document, PDO::PARAM_STR);
        $stmt->execute()

        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::ASSOC);
            $response['success'] = [
                'name' => $row['name'], 
                'email' => $row['email'], 
                'phone' => $row['phone'], 
                'id' => $row['id']
            ];
        } else {
            $response['error'] = 'Document not found';
        }
        echo json_encode($response, JSON_THROW_ON_ERROR);
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
        echo json_encode($response, JSON_THROW_ON_ERROR);
    } finally {
        $stmt->close();
        $conn = null;
    }  
} else {
    $response['error'] = 'Error Interno';
    echo json_encode($response, JSON_THROW_ON_ERROR);
}