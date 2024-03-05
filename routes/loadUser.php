<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document'])) {
    try {
        $document = $_POST['document'];
        $response = [];

        if (!preg_match('/^\d{8}$|^d{11}$/', $document)) {
            throw new InvalidArgumentException('Documento inválido');
        }

       $sql = "SELECT name, email, phone, id FROM Users WHERE document = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $document);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
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
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn = null;
    }  
} else {
    $response['error'] = 'Error Interno';
    echo json_encode($response, JSON_THROW_ON_ERROR);
}