<?php
header('Content-Type: application/json');
if (!isset($_POST['numero']) || !isset($_POST['codigo'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}
$numero = $_POST['numero'];
$codigo = $_POST['codigo'];
$data = ['numero' => $numero, 'codigo' => $codigo];

function makeCurlRequest($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    $multipartData = [];
    foreach ($data as $key => $value) {
        $multipartData[] = "$key=" . urlencode($value);
    }
    $body = implode("&", $multipartData);
    $boundary = uniqid();
    $delimiter = '----WebKitFormBoundary' . $boundary;
    $bodyFormatted = '';
    foreach ($data as $key => $value) {
        $bodyFormatted .= "--$delimiter\r\n";
        $bodyFormatted .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
        $bodyFormatted .= "$value\r\n";
    }
    $bodyFormatted .= "--$delimiter--\r\n";
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: multipart/form-data; boundary=$delimiter"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyFormatted);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['response' => $response, 'httpCode' => $httpCode];
}

$trackUrl = 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/buscar';
$trackResponse = makeCurlRequest($trackUrl, $data);
if ($trackResponse['httpCode'] == 200) {
    $trackData = json_decode($trackResponse['response'], true);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API de rastreo']);
    exit;
}

$ose_id = $trackData['data']['ose_id'] ?? null;
if ($ose_id === null) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener el ose_id']);
    exit;
}

$stateUrl = 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/estados';
$stateData = ['ose_id' => $ose_id];
$stateResponse = makeCurlRequest($stateUrl, $stateData);
if ($stateResponse['httpCode'] == 200) {
    $stateData = json_decode($stateResponse['response'], true);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API de estados']);
    exit;
}

$estadoMensaje = $stateData['message'] ?? "Sin información sobre el estado";

$result = [
    'success' => true,
    'message' => 'Información obtenida con éxito',
    'data' => [
        'rastreo' => $trackData['data'] ?? null,
        'mensaje_estado' => $estadoMensaje,
        'estados' => $stateData['data'] ?? null
    ]
];

echo json_encode($result);
