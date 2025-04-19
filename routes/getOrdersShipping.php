<?php
require_once '../includes/app/db.php';
require_once '../routes/scrapShalom.php';
require_once '../routes/scrapOlva.php';
require_once '../routes/scrapMarvisur.php';

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
        $scrapResults = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;

            $id_agencia = (int)$row['id_agencia'];
            $code1 = $row['code1'];
            $code2 = $row['code2'];
            $agencia = $row['nombre_agencia'];

            // Inicializar variables
            $fecha_ultimate = null;
            $estado = "Sin información";

            // Ejecutar scrap según agencia
            switch ($id_agencia) {
                case 1: // Shalom
                    $response = scrapShalom($code1, $code2);
                    if ($response['success']) {
                        $estados = $response['data']['estados'];
                        $mapa = [
                            'Entregado' => $estados['entregado']['fecha'] ?? null,
                            'En Ruta'   => $estados['transito']['fecha'] ?? null,
                            'Agencia'   => $estados['origen']['fecha'] ?? null
                        ];
                        $fecha_ultimate = obtenerUltimaFecha($mapa, $estado);
                    }
                    break;

                case 2: // Olva
                    $response = scrapOlva($code1, $code2);
                    if ($response['success']) {
                        $detalles = $response['data']['details'];
                        $mapa = [
                            'Entregado' => null,
                            'En Ruta'   => null,
                            'Agencia'   => null
                        ];
                        foreach ($detalles as $item) {
                            if ($item['estado_tracking'] === 'ENTREGADO') {
                                $mapa['Entregado'] = $item['fecha_creacion'];
                            } elseif ($item['estado_tracking'] === 'ASIGNADO') {
                                $mapa['En Ruta'] = $item['fecha_creacion'];
                            } elseif ($item['estado_tracking'] === 'RECEPCION TIENDA') {
                                $mapa['Agencia'] = $item['fecha_creacion'];
                            }
                        }
                        $fecha_ultimate = obtenerUltimaFecha($mapa, $estado);
                    }
                    break;

                case 3: // Marvisur
                    $response = scrapMarvisur($code1, $code2);
                    if ($response['success']) {
                        $detalles = $response['data']['Table'];
                        $mapa = [
                            'Entregado' => null,
                            'En Ruta'   => null,
                            'Agencia'   => null
                        ];
                        foreach ($detalles as $item) {
                            if ($item['COMENTARIO'] === 'ENTREGADO') {
                                $mapa['Entregado'] = $item['FECEVENTO'];
                            } elseif ($item['COMENTARIO'] === 'EN RUTA') {
                                $mapa['En Ruta'] = $item['FECEVENTO'];
                            } elseif ($item['COMENTARIO'] === 'RECEPCION') {
                                $mapa['Agencia'] = $item['FECEVENTO'];
                            }
                        }
                        $fecha_ultimate = obtenerUltimaFecha($mapa, $estado);
                    }
                    break;

                default:
                    // Agencia no soportada
                    break;
            }

            $scrapResults[] = [
                "agencia"        => $agencia,
                "code1"          => $code1,
                "code2"          => $code2,
                "fecha_ultimate" => $fecha_ultimate,
                "estado"         => $estado
            ];
        }

        if (!empty($orders)) {
            echo json_encode([
                'status' => 'success',
                'orders' => $orders,
                'scrap'  => $scrapResults
            ]);
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

// Función auxiliar para determinar la última fecha válida y su estado
function obtenerUltimaFecha($mapa, &$estado) {
    $fechas = array_filter($mapa);
    if (empty($fechas)) return null;

    arsort($fechas); // Orden descendente por fecha
    $estado = array_key_first($fechas); // Primer clave tras ordenar
    return $fechas[$estado];
}
?>
