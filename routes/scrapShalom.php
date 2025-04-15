<?php
header('Content-Type: application/json');

// Verificar si los parámetros necesarios están en la solicitud
if (!isset($_POST['numero']) || !isset($_POST['codigo'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

$numero = $_POST['numero'];
$codigo = $_POST['codigo'];
$data = [
    'numero' => $numero,
    'codigo' => $codigo
];

// Función para hacer la solicitud cURL
function makeCurlRequest($url, $data) {
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: multipart/form-data; boundary=$delimiter"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyFormatted);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar solicitud cURL
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['response' => $response, 'httpCode' => $httpCode];
}

// Solicitud a la API para rastrear el pedido
$trackUrl = 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/buscar';
$trackResponse = makeCurlRequest($trackUrl, $data);

// Verificar si la respuesta es exitosa
if ($trackResponse['httpCode'] == 200) {
    $trackData = json_decode($trackResponse['response'], true);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API de rastreo']);
    exit;
}

// Extraer el ose_id para la solicitud de estados
$ose_id = $trackData['data']['ose_id'] ?? null;

if ($ose_id === null) {
    echo json_encode(['success' => false, 'message' => 'No se pudo obtener el ose_id']);
    exit;
}

// Solicitud a la API para obtener los estados del pedido usando ose_id
$stateUrl = 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/estados';
$stateData = ['ose_id' => $ose_id]; // Incluir el ose_id en los datos
$stateResponse = makeCurlRequest($stateUrl, $stateData);

// Verificar si la respuesta es exitosa
if ($stateResponse['httpCode'] == 200) {
    $stateData = json_decode($stateResponse['response'], true);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API de estados']);
    exit;
}

// Combinar ambos resultados
$result = [
    'success' => true,
    'message' => 'Información obtenida con éxito',
    'data' => [
        'rastreo' => $trackData['data'] ?? null,
        'estados' => $stateData['data'] ?? null
    ]
];

// Responder con los datos combinados
echo json_encode($result);
?>
