<?php
header('Content-Type: application/json');

if (!isset($_POST['numero']) || !isset($_POST['codigo'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

$numero = $_POST['numero'];  // Tracking number
$codigo = $_POST['codigo'];  // Emission number

$apikey = 'a82e5d192fae9bbfee43a964024498e87dfecb884b67c7e95865a3bb07b607dd';
$trackUrl = 'https://reports.olvaexpress.pe/webservice/rest/getTrackingInformation?tracking=' . urlencode($numero) .
            '&emision=' . urlencode($codigo) .
            '&apikey=' . $apikey .
            '&details=1';

function makeCurlRequest($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['response' => $response, 'httpCode' => $httpCode];
}

$trackResponse = makeCurlRequest($trackUrl);

if ($trackResponse['httpCode'] == 200) {
    $trackData = json_decode($trackResponse['response'], true);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API de rastreo']);
    exit;
}

// Comprobamos si se obtuvo la información correctamente
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

