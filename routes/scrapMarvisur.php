<?php
header('Content-Type: application/json');
if (!isset($_POST['numero']) || !isset($_POST['serie'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

$numero = $_POST['numero'];  // Número de envío
$serie = $_POST['serie'];    // Serie del documento

$apiUrl = 'https://marvicom.expresomarvisur.com/backend/api/WebApi';

$postData = array(
    "modo" => 1,
    "serie" => $serie,
    "numero" => $numero
);

function makePostRequest($url, $data)
{
    $ch = curl_init($url);
    $jsonData = json_encode($data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['response' => $response, 'httpCode' => $httpCode];
}

$trackResponse = makePostRequest($apiUrl, $postData);
if ($trackResponse['httpCode'] == 200) {
    $trackData = json_decode($trackResponse['response'], true);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API de Marvisur']);
    exit;
}

if (isset($trackData['success']) && $trackData['success'] === true) {
    $result = [
        'success' => true,
        'message' => 'Información obtenida con éxito',
        'data' => $trackData['data'] ?? null,  // Solo la información en data
    ];
} else {
    $result = [
        'success' => false,
        'message' => 'No se pudo obtener la información del rastreo',
    ];
}

echo json_encode($result);
?>
