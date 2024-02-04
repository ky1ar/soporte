<?php
require_once 'db.php';
date_default_timezone_set('America/Bogota');
setlocale(LC_TIME, 'es_ES');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {

    $date = $_POST['date'];

    $sql = "SELECT * FROM Calendar WHERE calendar_date = '$date'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $state = $row['state'];

        $formatedDate = date('l, j \de F \del Y', strtotime($date));
        echo '<div class="hou-hdr">'.$formatedDate.'</div>';

        echo '<ul>';
        if ($state == 2) {
            $sql2 = "SELECT cs.id, cs.h_start, cs.h_end FROM Custom_Schedule cs LEFT JOIN Training t ON t.training_date = cs.t_date WHERE t.training_date = '$date' AND t.schedule_id != cs.id";
            $result2 = $conn->query($sql2);
            while ($row2 = $result2->fetch_assoc()) {
                echo '<li><a href="">'.$row2['h_start'].'</a></li>';
            }
        } else {
            $sql2 = "SELECT ds.id, ds.h_start, ds.h_end FROM Default_Schedule ds LEFT JOIN Training t ON ds.id = t.schedule_id AND t.training_date = '$date' WHERE t.id IS NULL";
            $result2 = $conn->query($sql2);
            while ($row2 = $result2->fetch_assoc()) {
                echo '<li><a href="">'.$row2['h_start'].'</a></li>';
            }
        }
        echo '</ul>';
    }
}
$conn->close();
?>

