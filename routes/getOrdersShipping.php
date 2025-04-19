<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'])) {
    $documento = trim($_POST['documento']);

    if (strlen($documento) !== 8 && strlen($documento) !== 11) {
        echo json_encode(['status' => 'error', 'message' => 'No tiene ningún registro']);
        exit;
    }

    $query = "
        SELECT 
            o.id_order,
            u.nombre AS nombre_usuario,
            u.documento,
            o.orden,
            o.agencia AS id_agencia,
            a.agencia_name AS nombre_agencia,
            o.code1,
            o.code2,
            s.status AS nombre_status
        FROM Orders_Shipping o
        INNER JOIN Users_Shipping u ON o.id_user = u.id_user
        LEFT JOIN Agency_Shipping a ON o.agencia = a.id_agencia
        LEFT JOIN Status_Shipping s ON o.status = s.id_status
        WHERE u.documento = ?
        ORDER BY o.fecha_creacion DESC
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $documento);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            // Consultar estado en vivo según agencia
            $estado_actual = obtenerEstadoActual($row['id_agencia'], $row['code1'], $row['code2']);
            $row['estado_actual'] = $estado_actual;
            $orders[] = $row;
        }

        if (!empty($orders)) {
            echo json_encode(['status' => 'success', 'orders' => $orders]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No tiene ningún registro']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud no válida']);
}

// ====================== FUNCIONES DE CONSULTA ==========================

function makePostRequest($url, $data) {
    $ch = curl_init($url);
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['response' => $response, 'httpCode' => $httpCode];
}

function makeCurlRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['response' => $response, 'httpCode' => $httpCode];
}

function makeCurlRequestPost($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
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

function obtenerEstadoActual($id_agencia, $code1, $code2) {
    switch ($id_agencia) {
        case 1: // Shalom
            $trackUrl = 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/buscar';
            $data = ['numero' => $code1, 'codigo' => $code2];
            $trackResponse = makeCurlRequestPost($trackUrl, $data);
            if ($trackResponse['httpCode'] == 200) {
                $trackData = json_decode($trackResponse['response'], true);
                $ose_id = $trackData['data']['ose_id'] ?? null;
                if ($ose_id) {
                    $stateUrl = 'https://servicesweb.shalomcontrol.com/api/v1/web/rastrea/estados';
                    $stateData = ['ose_id' => $ose_id];
                    $stateResponse = makeCurlRequestPost($stateUrl, $stateData);
                    if ($stateResponse['httpCode'] == 200) {
                        $stateData = json_decode($stateResponse['response'], true);
                        return $stateData['data'][0]['descripcion'] ?? 'Sin estado';
                    }
                }
            }
            return 'Sin estado';
        case 2: // Olva
            $apikey = 'a82e5d192fae9bbfee43a964024498e87dfecb884b67c7e95865a3bb07b607dd';
            $url = 'https://reports.olvaexpress.pe/webservice/rest/getTrackingInformation?tracking=' . urlencode($code1) . '&emision=' . urlencode($code2) . '&apikey=' . $apikey . '&details=1';
            $trackResponse = makeCurlRequest($url);
            if ($trackResponse['httpCode'] == 200) {
                $trackData = json_decode($trackResponse['response'], true);
                return $trackData['data']['events'][0]['status'] ?? 'Sin estado';
            }
            return 'Sin estado';
        case 3: // Marvisur
            $apiUrl = 'https://marvicom.expresomarvisur.com/backend/api/WebApi';
            $postData = ["modo" => 1, "serie" => $code2, "numero" => $code1];
            $trackResponse = makePostRequest($apiUrl, $postData);
            if ($trackResponse['httpCode'] == 200) {
                $trackData = json_decode($trackResponse['response'], true);
                return $trackData['data']['estado'] ?? 'Sin estado';
            }
            return 'Sin estado';
        default:
            return 'Agencia no válida';
    }
}
?>
