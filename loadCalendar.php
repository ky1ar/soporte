<?php
require_once 'db.php';
date_default_timezone_set('America/Bogota');
setlocale(LC_TIME, 'es_ES');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {

    $date = $_POST['date'];
    $day = $_POST['day'];

    for ($i = 0; $i < $day; $i++) { echo '<li></li>'; }
    
    $sql = "SELECT * FROM Calendar WHERE YEAR(calendar_date) = YEAR('$date') AND MONTH(calendar_date) = MONTH('$date')";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $dayNum = date('d', strtotime($row['calendar_date']));
        $state = $row['state'];
        
        echo '<li>';
        if ( $state == 0 ) { echo '<span>'.$dayNum.'</span>'; } 
        else { echo '<div class="boxDay" data-day="'.$dayNum.'">'.$dayNum.'</div>'; }
        echo '</li>';
    }
}
$conn->close();
?>



