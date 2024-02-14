<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {

    $date = $_POST['date'];

    $sql = "SELECT * FROM Calendar WHERE calendar_date = '$date'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $state = $row['state'];

        $days = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
        $selectedDate = new DateTime($date);

        $dayName = $days[$selectedDate->format('w')];
        $dayMonth = $selectedDate->format('j');

        $selectedDate = $dayName . ' ' . $dayMonth;
        echo '<div id="selectedDate" data-date="'.$selectedDate.'">'.$selectedDate.'</div>';
        echo '<p class="selectedMessage">A continuación elige un horario disponible</p>';
        echo '<ul>';
        if ($state == 2) {
            $sql2 = "SELECT cs.id, cs.h_start, cs.h_end FROM Custom_Schedule cs LEFT JOIN Training t ON t.training_date = cs.t_date WHERE cs.t_date = '$date' AND COALESCE(t.schedule_id, -1) != cs.id;";
            $result2 = $conn->query($sql2);
            while ($row2 = $result2->fetch_assoc()) {
                echo '<li><div class="boxSchedule" data-schedule="'.substr($row2['h_start'], 0, 5).'">'.substr($row2['h_start'], 0, 5).'</div></li>';
            }
        } else {
            $sql2 = "SELECT ds.id, ds.h_start, ds.h_end FROM Default_Schedule ds LEFT JOIN Training t ON ds.id = t.schedule_id AND t.training_date = '$date' WHERE t.id IS NULL";
            $result2 = $conn->query($sql2);
            while ($row2 = $result2->fetch_assoc()) {
                echo '<li><div class="boxSchedule" data-schedule="'.substr($row2['h_start'], 0, 5).'">'.substr($row2['h_start'], 0, 5).'</div></li>';
            }
        }
        echo '</ul>';
    }
}
$conn->close();
?>

