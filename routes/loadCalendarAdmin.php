<?php
require_once '../includes/app/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {

    $date = $_POST['date'];
    $day = $_POST['day'];
    
    $today = new DateTime();
    $todayDay = $today->format('j');
    $todayMonth = $today->format('n');

    $selectedDate = new DateTime($date);
    $dayMonth = $selectedDate->format('j');
    $monthNumber = $selectedDate->format('n');

    for ($i = 0; $i < $day; $i++) { echo '<li></li>'; }
                                
    $sql = "SELECT * FROM Calendar WHERE YEAR(calendar_date) = YEAR('$date') AND MONTH(calendar_date) = MONTH('$date')";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $date = $row['calendar_date'];
        $dayNum = date('d', strtotime($date));
        echo '<li class="admin ' . (($todayMonth == $monthNumber && $todayDay == $dayNum) ? 'today' : '') . '">';
        echo '<span data-day="'.$dayNum.'">'.$dayNum.'</span>';
        echo '<div class="calendarView">';
        
        $sql2 =
        "SELECT t.id,
            t.training_state,
            w.image as w_image,
            w.nick as w_nick,
            m.model as m_model,
            m.slug as m_slug,
            t.training_start
        FROM Training t
        INNER JOIN Machine m ON t.machine = m.id
        INNER JOIN Brand b ON m.brand = b.id
        INNER JOIN Users w ON t.worker = w.id
        WHERE (t.training_state = 1 OR t.training_state = 2) AND training_date = '$date'
        ORDER BY training_start;";

        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0){
            while ($row2 = $result2->fetch_assoc()):?>
                <div class="calendarViewRow
                    <?= $row2['training_state'] == 2 ? 'finish' : ''?>"
                    data-id="<?= $row2['id']?>"
                    data-date="<?= $date?>"
                    data-start="<?= $row2['training_start']?>"
                    style=
                        "border-left: 3px solid #<?= $row2['w_image']?>;
                        background-color: #<?= $row2['w_image']?>47;"
                >
                    <h3><?= $row2['m_model']?></h3>
                    <div class="flex">
                        <h2 style="background-color: #<?= $row2['w_image']?>;">
                            <?= substr($row2['training_start'], 0, 5)?>
                        </h2>
                        <p><?= $row2['w_nick']?></p>
                    </div>
                </div>
            <?php endwhile;
        }
        echo '</div>';
        echo '</li>';
    }
}
$conn->close();
?>



