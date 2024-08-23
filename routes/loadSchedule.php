<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {

    $date = $_POST['date'];
    $response = array();

    // Consulta para obtener la información del calendario
    $sql = "SELECT * FROM Calendar WHERE calendar_date = '$date'";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        $custom = $row['custom'];

        $days = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
        $selectedDate = new DateTime($date);

        $dayName = $days[$selectedDate->format('w')];
        $dayMonth = $selectedDate->format('j');

        $selectedDateText = $dayName . ' ' . $dayMonth;

        // Inicializamos las variables de disponibilidad
        $customScheduleAvailable = false;
        $defaultScheduleAvailable = false;

        // Inicializamos el HTML con el día seleccionado
        $response['html'] = '<div id="selectedData" data-day="'.$selectedDateText.'" data-date="'.$date.'" class="dateUnavailable">'.$selectedDateText.'</div>';

        // Verificamos la disponibilidad de horarios personalizados
        if ($custom == 1) {
            $sql2 = "SELECT cs.id, cs.h_start, cs.h_end 
                     FROM Custom_Schedule cs
                     LEFT JOIN Training t ON cs.h_start = t.training_start AND cs.t_date = t.training_date
                     WHERE cs.t_date = '$date' 
                     AND (t.id IS NULL)
                     ORDER BY cs.h_start;";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                $customScheduleAvailable = true;
                $response['html'] = '<div id="selectedData" data-day="'.$selectedDateText.'" data-date="'.$date.'">'.$selectedDateText.'</div>';
                $response['html'] .= '<p class="selectedMessage">A continuación elige un horario disponible</p>';
                $response['html'] .= '<ul>';
                while ($row2 = $result2->fetch_assoc()) {
                    $response['html'] .= '<li><div class="boxSchedule" data-schedule="'.substr($row2['h_start'], 0, 5).'">'.substr($row2['h_start'], 0, 5).'</div></li>';
                }
                $response['html'] .= '</ul>';
            }
        } else {
            // Verificamos la disponibilidad de horarios predeterminados
            $sql2 = "SELECT 
                        ds.id,
                        ds.h_start,
                        ds.h_end,
                        MIN(t.training_state) AS training_state
                     FROM Default_Schedule ds
                     LEFT JOIN Training t ON ds.h_start = t.training_start
                     AND t.training_date = '$date'
                     GROUP BY ds.id, ds.h_start, ds.h_end
                     HAVING MIN(t.training_state) != 0 
                        AND MIN(t.training_state) != 1
                        AND MIN(t.training_state) != 2
                        OR MIN(t.training_state) IS NULL 
                     ORDER BY ds.h_start;";
                     
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                $defaultScheduleAvailable = true;
                $response['html'] = '<div id="selectedData" data-day="'.$selectedDateText.'" data-date="'.$date.'">'.$selectedDateText.'</div>';
                $response['html'] .= '<p class="selectedMessage">A continuación elige un horario disponible</p>';
                $response['html'] .= '<ul>';
                while ($row2 = $result2->fetch_assoc()) {
                    $response['html'] .= '<li><div class="boxSchedule" data-schedule="'.substr($row2['h_start'], 0, 5).'">'.substr($row2['h_start'], 0, 5).'</div></li>';
                }
                $response['html'] .= '</ul>';
            }
        }

        // Si no hay horarios disponibles en ninguno de los casos, marcamos el día como no seleccionable
        if (!$customScheduleAvailable && !$defaultScheduleAvailable) {
            $response['html'] = '<div id="selectedData" data-day="'.$selectedDateText.'" data-date="'.$date.'" class="dateUnavailable">'.$selectedDateText.'</div>';
        }

        // Añadimos la cantidad de horarios disponibles en el valor oculto
        $response['html'] .= '<input type="hidden" id="dateAvailable" value="'.($result2->num_rows).'">';
    }
    
    echo json_encode($response);
}
$conn->close();
?>
