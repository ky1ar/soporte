<?php
header('Content-Type: application/json');
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
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/buscar');
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

// Ejecutar
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Verificar respuesta
if ($httpCode == 200) {
    echo $response;
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar API']);
}
