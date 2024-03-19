<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: krear3dperu");
    exit();
}

$s_id = $_SESSION['user_id'];
$s_levels = $_SESSION['user_levels'];
$s_name = $_SESSION['user_name'];
$s_nick = $_SESSION['user_nick'];
$s_role = $_SESSION['user_role'];

$currentPage = "Capacitaciones"; 
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php'; 
require_once 'includes/common/header_admin.php';
$stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin']; 
?>
</head>
<body class="ky1-adm">
    <?php 
    require_once 'includes/bar/topBar_admin.php';
    require_once 'includes/bar/navigationBar_admin.php'; 
    ?>
    <section id="adminSection" style="display: flex; justify-content: center; align-items: center">
        <div class="ky1-wrp">
        <?php 
        $days = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
        $months = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre",  "noviembre", "diciembre");
        $startMonth = 0;
        $startTable = false;
        $sql = "SELECT t.id, training_date, t.phone, t.email, CASE WHEN c.custom = 0 THEN ds.h_start WHEN c.custom = 1 THEN cs.h_start END AS h_start, document, t.name as t_name, invoice, m.model as m_model, m.slug as m_slug FROM Training t INNER JOIN Calendar c ON t.training_date = c.calendar_date INNER JOIN Machine m ON t.machine = m.id INNER JOIN Brand b ON m.brand = b.id LEFT JOIN Default_Schedule ds ON t.training_start = ds.h_start AND c.custom = 0 LEFT JOIN Custom_Schedule cs ON t.training_start = cs.h_start AND c.custom = 1 WHERE t.training_state = 0 ORDER BY training_date, h_start;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):?>
            <div class="sectionBox" >
                <h3 class="boxTitle">Solicitudes de Capacitaciones
                    <p>Listado de las capacitaciones pendientes de aprobación y asignación del responsable.</p>
                </h3>
                <?php while ($row = $result->fetch_assoc()):?>
                    <?php 
                    $selectedDate = new DateTime($row['training_date']);
                    $year = $selectedDate->format('Y');
                    $month = $selectedDate->format('n');
                    $dayName = $days[$selectedDate->format('w')];
                    $dayNumber = $selectedDate->format('j');
                    
                    if($startMonth != $month && $startTable):?>
                        </table>
                    <?php endif;

                    if($startMonth != $month):
                        $startTable = true;
                        $startMonth = $month;
                    ?>
                    <table class="pendingTable" border="0" cellspacing="0" cellpadding="0">
                        <tr class="tableHeader">
                            <th><?php echo $months[$month-1] ?> <?php echo $year ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>
                            <div class="rowSchedule"><?php echo $dayName.' '.$dayNumber ?>
                                <span><img width="14" height="14" src="assets/img/clock.svg" alt=""><?php echo substr($row['h_start'], 0, 5) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="rowMachine">
                                <img width="48" src="assets/mac/<?php echo $row['m_slug'] ?>.webp" alt="">
                                <span><?php echo $row['m_model'] ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="rowClient">
                                <?php echo $row['t_name'] ?>
                                <span><?php echo $row['document'] ?>
                                    <div class="preview" data-src="<?php echo $row['invoice'] ?>"><img width="12" height="12" src="assets/img/invoice.svg" alt=""> Recibo</div>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="rowClient">
                                <?php echo $row['email'] ?>
                                <span><?php echo $row['phone'] ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="actionButtons" data-id="<?php echo $row['id'] ?>" data-date="<?php echo $row['training_date'] ?>">
                                <div class="button aprove">Aprobar</div>
                                <div class="button reject">Rechazar</div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </table>
                <div id="previewInvoice">
                    <div class="previewBox">
                        <span class="close">&times;</span>
                        <div id="invoiceFile"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="sectionBox">
                <h3 class="boxTitle">Calendario de Capacitaciones</h3>
                <div id="adminCalendar">
                    <?php
                    $today = date('d');
                    $firstDay = date('Y-m-01');
                    $firstDayNum = date('N', strtotime($firstDay));
                    ?>
                    <div class="calendarHeader">
                        <span id="monthName"><?php echo strftime('%B %Y', strtotime($firstDay)) ?></span>
                        <div id="calendarNavigation">
                            <div class="button disabled" id="calendarPrev"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                            <div class="button" id="calendarNext"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                        </div>
                    </div>
                    <div id="calendarContainer">
                        <div id="loadingResponse">
                            <div class="flex">
                                <img src="assets/img/fav.png" alt="Cargando..." />
                            </div>
                        </div>
                        <div id="calendarSelector">
                            <ul class="calendarBox boxHeader">
                                <li>dom</li>
                                <li>lun</li>
                                <li>mar</li>
                                <li>mié</li>
                                <li>jue</li>
                                <li>vie</li>
                                <li>sáb</li>
                            </ul>
                            <ul class="calendarBox" id="calendarTable">
                                <?php
                                for ($i = 0; $i < $firstDayNum; $i++) { echo '<li></li>'; }
                                
                                $sql = "SELECT * FROM Calendar WHERE YEAR(calendar_date) = YEAR('$firstDay') AND MONTH(calendar_date) = MONTH('$firstDay')";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $date = $row['calendar_date'];
                                    $dayNum = date('d', strtotime($date));
                                    echo '<li class="admin ' . (($today == $dayNum) ? 'today' : '') . '">';
                                    echo '<span data-day="'.$dayNum.'">'.$dayNum.'</span>';
                                    echo '<div class="calendarView">';
                                    $sql2 = 
                                   "SELECT t.id, t.training_state, w.name as w_name, m.model as m_model, m.slug as m_slug, 
                                    t.document as c_document, t.name as c_name, t.phone as c_phone, t.email as c_email, invoice, t.training_start
                                    FROM Training t 
                                    INNER JOIN Calendar c ON t.training_date = c.calendar_date 
                                    INNER JOIN Machine m ON t.machine = m.id 
                                    INNER JOIN Brand b ON m.brand = b.id 
                                    INNER JOIN Users w ON t.worker = w.id 
                                    WHERE (t.training_state = 1 OR t.training_state = 2) AND training_date = '$date' 
                                    ORDER BY training_start;";
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows > 0){
                                        while ($row2 = $result2->fetch_assoc()){
                                            echo '<div class="calendarViewRow '.(($row2['training_state'] == 2) ? 'finish' : '').'" data-id="'.$row2['id'].'" data-date="'.$date.'" data-start="'.$row2['training_start'].'">';
                                            echo '<h2>'.substr($row2['training_start'], 0, 5).'</h2>';
                                            echo '<div><h3>'.$row2['w_name'].'</h3>';
                                            echo '<p>'.$row2['m_model'].'</p></div>';
                                            echo '<img width="48" src="assets/mac/'.$row2['m_slug'].'.webp" alt="">';
                                            echo '</div>';
                                        }
                                    }
                                    echo '</div>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </section>
    <section id="aproveOverlay">
        <div class="modalBox">
            <form method="post" id="aproveSubmit">
                <h2>¿Aprobar capacitación?</h2>
                <p>Agrega el link de Google Meet y el responsable.<br>Recuerda haber verificado el comprobante.</p>
                <div class="aproveBox">
                    <label for="">Responsable de la capacitación</label>
                    <div class="flex">
                        <img src="assets/img/worker.svg" alt="">
                        <select id="trainingWorker">
                            <option value="">Seleccionar</option>
                            <?php $sql = "SELECT id, name FROM Users WHERE levels = 2 OR levels = 3 ORDER BY name";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                            <?php endwhile; 
                            $conn->close();?>
                        </select>
                    </div>
                </div>
                <div class="aproveBox">
                    <label for="">Enlace Google Meet</label>
                    <div class="flex">
                        <img src="assets/img/meet.svg" alt="">
                        <input type="text" id="meet" placeholder="Ingresa la Url">
                    </div>
                </div>
                <div id="aproveMessage"></div>
                <div class="aproveButtons" data-id="">
                    <div class="modalCancel">Cancelar</div>
                    <button type="submit">Aprobar</button>
                </div>
                <img class="modalClose" src="assets/img/x.svg" alt="">
            </form>
        </div>
    </section>
    <section id="rejectOverlay">
        <div class="modalBox">
            <form method="post" id="rejectSubmit">
                <h2>¿Rechazar capacitación?</h2>
                <p>Recuerda haber verificado correctamente el comprobante.</p>
                <div class="rejectBox">
                    <label for="">Mensaje en el correo</label>
                    <div class="flex">
                        <img src="assets/img/cnt.svg" alt="">
                        <textarea id="rejectText" placeholder="Ingresa el mensaje"></textarea>
                    </div>
                </div>
                <div id="rejectMessage"></div>
                <div class="rejectButtons">
                    <div class="modalCancel">Cancelar</div>
                    <button type="submit">Rechazar</button>
                </div>
                <img class="modalClose" src="assets/img/x.svg" alt="">
            </form>
        </div>
    </section>
    <section id="viewOverlay">
        <div class="modalBox">
            <div id="viewTraining">
                <h2 class="title"></h2>
                <p>Detalles de la capacitación:</p>
                <div class="rowMachine">
                    <div class="viewBox">
                        <label for="">Cliente</label>
                        <span class="name"></span>
                        <span class="phone"></span>
                    </div>
                    <div class="viewBox">
                        <label for="">Equipo</label>
                        <div class="rowMachine">
                            <img class="image" width="32" src="" alt="">
                            <span class="model"></span>
                        </div>
                    </div>
                </div>
                <div class="viewBox">
                    <label for="">Responsable de la capacitación</label>
                    <div class="flex">
                        <img src="assets/img/worker.svg" alt="">
                        <span class="worker"></span>
                    </div>
                </div>
                <div class="viewBox">
                    <label for="">Enlace Google Meet</label>
                    <div class="flex">
                        <img src="assets/img/meet.svg" alt="">
                        <a class="meet" target="_blank" href=""></a>
                    </div>
                </div>
                <div id="viewMessage"></div>
                <div class="viewButtons" data-id="">
                    <div id="cancelTraining">Eliminar</div>
                    <div id="finishTraining">Completada</div>
                </div>
                <img class="modalClose" src="assets/img/x.svg" alt="">
            </div>
        </div>
    </section>                     
</body>
</html>

<?php 
/*/$sql2 = 
"SELECT t.id, document, t.name as t_name, invoice, m.model as m_model, m.slug as m_slug,
 CASE 
     WHEN c.custom = 0 THEN ds.h_start 
     WHEN c.custom = 1 THEN cs.h_start 
     END AS h_start 
 FROM Training t 
 INNER JOIN Calendar c ON t.training_date = c.calendar_date 
 INNER JOIN Machine m ON t.machine = m.id 
 INNER JOIN Brand b ON m.brand = b.id 
 INNER JOIN Users u ON t.worker = u.id 
 LEFT JOIN Default_Schedule ds ON t.training_start = ds.h_start AND c.custom = 0 
 LEFT JOIN Custom_Schedule cs ON t.training_start = cs.h_start AND c.custom = 1 
 WHERE t.training_state = 1 AND training_date =  '$date' 
 ORDER BY training_date, h_start;"*/
 ?>