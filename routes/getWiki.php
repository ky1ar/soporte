<?php
require_once '../includes/app/db.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    try {
        $title = $_POST['title'];

        $sql = "SELECT * FROM Wiki WHERE title = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta SQL: ' . $conn->error);
        }

        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            // Reemplazar \r\n y \n por <br> en los valores de los textos
            $textKeys = [
                'p1', 'coment1', 'pex1', 'p2', 'coment2', 'p3', 'pex3', 'p4', 'p5',
                'p51', 'p52', 'p6', 'p61', 'p7', 'p71', 'p72', 'p73', 'p74', 'p8',
                'p81', 'p82', 'p811', 'p821', 'p831', 'p841', 'p851', 'p861', 'p822',
                'p823', 'p824', 'p825', 'p826', 'p8261', 'p9', 'p721', 'p731', 'p711'
            ];

            foreach ($textKeys as $key) {
                if (isset($data[$key])) {
                    // Aplicar estilos a las líneas con viñetas numeradas o de puntos
                    $data[$key] = agregarEstilosViñetas($data[$key]);
                    // Reemplazar saltos de línea con <br> en el texto
                    $data[$key] = str_replace(array("\r\n", "\n"), "<br>", $data[$key]);
                }
            }

            $response['success'] = true;
            $response['data'] = $data;
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontraron datos para el título proporcionado.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = 'Error: ' . $e->getMessage();
    } finally {
        $conn->close();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Solicitud inválida.';
}

function agregarEstilosViñetas($texto)
{
    $lineas = explode("\n", $texto);
    $texto_formateado = "";

    foreach ($lineas as $linea) {
        $linea_trim = trim($linea);

        // Verificar si la línea contiene un enlace
        if (strpos($linea_trim, 'href=') !== false) {
            // Si es un enlace, se añade al texto formateado sin ningún cambio
            $texto_formateado .= $linea_trim . "<br>";
        } else {
            // Si no es un enlace, se aplica el formato según corresponda
            if (preg_match('/^\d+\.$/', $linea_trim)) {
                // Si encuentra un número seguido de un punto al final de la línea
                $linea_formateada = '<span style="font-weight: bold;">' . $linea_trim . '</span>';
            } elseif (preg_match('/^\d+\./', $linea_trim) || strpos($linea_trim, '•') === 0) {
                // Si encuentra un número seguido de un punto o una viñeta al inicio de la línea
                $pos_dos_puntos = strpos($linea_trim, ':');
                if ($pos_dos_puntos !== false) {
                    $parte_inicial = '<span style="font-weight: bold;">' . substr($linea_trim, 0, $pos_dos_puntos) . '</span>';
                    $parte_restante = substr($linea_trim, $pos_dos_puntos);
                    $linea_formateada = $parte_inicial . $parte_restante;
                } else {
                    $parte_inicial = preg_replace('/^(\d+\.) /', '<span style="font-weight: bold; padding-right: 0.5rem;">$1</span>', $linea_trim);
                    $linea_formateada = $parte_inicial . " "; // Añadir un espacio después del punto
                }
                $texto_formateado .= $linea_formateada . "<br>";
            } else {
                // Si no cumple ninguna de las condiciones anteriores, se deja sin formato
                $linea_formateada = $linea_trim;
            }
        }
    }

    return $texto_formateado;
}


echo json_encode($response);
