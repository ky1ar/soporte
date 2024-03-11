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
    
    <div id="ky1-bar">
        <div class="ky1-wrp bar-enl">	 
            <a href="https://api.whatsapp.com/send?phone=51934760404" target="_blank" rel="nofollow"><img src="https://ky1arsupport.tiendakrear3d.com/wp-content/uploads/2023/08/wsp.svg" width="16" height="16" alt="ico">934 760 404</a>
            <a href="https://api.whatsapp.com/send?phone=51982001288" target="_blank" rel="nofollow"><img src="https://ky1arsupport.tiendakrear3d.com/wp-content/uploads/2023/08/wsp.svg" width="16" height="16" alt="ico">982 001 288</a>
            <a href="mailto:ventas2@krear3d.com" target="_blank" rel="nofollow"><img src="https://ky1arsupport.tiendakrear3d.com/wp-content/uploads/2023/08/eml.svg" width="16" height="16" alt="ico">ventas2@krear3d.com</a>
            <a href="https://bit.ly/2ZzWUeK" target="_blank" rel="nofollow"><img src="https://ky1arsupport.tiendakrear3d.com/wp-content/uploads/2023/08/map.svg" width="16" height="16" alt="ico">Calle Javier Fernández 262 Miraflores - Lima</a>
        </div>
    </div>
    <header id="ky1-hdr-adm" class="ky1-blr">
        <div class="ky1-wrp">
            <div class="ky1-lft">
                <div id="hdr-usr">
                    <img class="hdr-prf" src="assets/img/profile.webp" width="40" height="40" alt="Perfil">
                    <div class="ky1-txt">
                        <span><?php echo $s_name ?></span>
                        <p><?php echo $s_role ?></p>
                    </div>
                    <div id="hdr-ext">
                        <div class="ext-cnt">
                            <img class="hdr-prf" src="assets/img/profile.webp" width="80" height="80" alt="Perfil">
                            <span><?php echo $s_name ?></span>
                            <a href="proLogout">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
                
                <div class="flt-usr flt-act" data-usrf="1"><img src="assets/img/edt.svg" alt="">Dashboard</div>
                <div class="flt-usr flt-act" data-usrf="2"><img src="assets/img/usr.svg" alt="">Técnicos</span></div>
            </div>
            <div class="ky1-rgt">
                <a href="https://api.whatsapp.com/send?phone=51946887982" target="_blank" rel="nofollow"><img src="assets/img/cnt.svg" alt=""></a>
                <?php if( $s_levels == 3 || $s_levels == 4 ):?>
                    <a href="grid"><img src="assets/img/adm.svg" alt=""></a>
                <?php endif;?>
            </div>
        </div>
    </header>

    <section id="adminSection">
        <div class="ky1-wrp">
        <?php 
        $days = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
        $months = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre",  "noviembre", "diciembre");
        $startMonth = 0;
        $startTable = false;
        $sql = "SELECT t.id, training_date, t.phone, t.email, CASE WHEN c.custom = 0 THEN ds.h_start WHEN c.custom = 1 THEN cs.h_start END AS h_start, document, t.name as t_name, invoice, m.model as m_model, m.slug as m_slug FROM Training t INNER JOIN Calendar c ON t.training_date = c.calendar_date INNER JOIN Machine m ON t.machine = m.id INNER JOIN Brand b ON m.brand = b.id LEFT JOIN Default_Schedule ds ON t.training_start = ds.h_start AND c.custom = 0 LEFT JOIN Custom_Schedule cs ON t.training_start = cs.h_start AND c.custom = 1 WHERE t.training_state = 0 ORDER BY training_date, h_start;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):?>
            <div class="sectionBox">
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
                                   "SELECT t.id, w.name as w_name, m.model as m_model, m.slug as m_slug, 
                                    t.document as c_document, t.name as c_name, t.phone as c_phone, t.email as c_email, invoice, t.training_start
                                    FROM Training t 
                                    INNER JOIN Calendar c ON t.training_date = c.calendar_date 
                                    INNER JOIN Machine m ON t.machine = m.id 
                                    INNER JOIN Brand b ON m.brand = b.id 
                                    INNER JOIN Users w ON t.worker = w.id 
                                    WHERE t.training_state = 1 AND training_date = '$date' 
                                    ORDER BY training_start;";
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows > 0){
                                        while ($row2 = $result2->fetch_assoc()){
                                            echo '<div class="calendarViewRow">';
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