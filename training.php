<?php
require_once 'db.php';

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/fav.png">
    <title>Krear 3D - Soporte Técnico</title>
    <?php require_once 'header_back.php'; ?>
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
            <div class="sectionBox">
                <h3 class="boxTitle">Solicitudes de Capacitaciones
                    <p>Listado de las capacitaciones pendientes de aprobación y asignación del responsable.</p>
                </h3>
                
                <?php 
                $days = array("domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado");
                $months = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre",  "noviembre", "diciembre");
                $startMonth = 0;
                $startTable = false;
                $sql = "SELECT t.id, training_date, CASE WHEN c.custom = 0 THEN ds.h_start WHEN c.custom = 1 THEN cs.h_start END AS h_start, document, t.name as t_name, invoice, m.model as m_model, m.slug as m_slug FROM Training t INNER JOIN Calendar c ON t.training_date = c.calendar_date INNER JOIN Machine m ON t.machine = m.id INNER JOIN Brand b ON m.brand = b.id LEFT JOIN Default_Schedule ds ON t.schedule_id = ds.id AND c.custom = 0 LEFT JOIN Custom_Schedule cs ON t.schedule_id = cs.id AND c.custom = 1 WHERE t.state = 0 ORDER BY training_date, h_start;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):?>
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
                                <select class="selectWorker">
                                    <option value="">Seleccionar</option>
                                    <option value="193">Bryan García</option>
                                    <option value="122">Gabriel Díaz</option>
                                    <option value="2">Juan Huamán</option>
                                    <option value="108">Richard Tong</option>
                                    <option value="203">Ronny Calderón</option>
                                </select>
                            </td>
                            <td>
                                <div class="actionButtons">
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
                <?php endif; ?>
            </div>
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
                                    echo '<li' . (($today == $dayNum) ? ' class="today"' : '') . '>';
                                    echo '<span data-day="'.$dayNum.'">'.$dayNum.'</span><div>';

                                    $sql2 = "SELECT t.id, training_date, CASE WHEN c.custom = 0 THEN ds.h_start WHEN c.custom = 1 THEN cs.h_start END AS h_start, document, t.name as t_name, invoice, m.model as m_model, m.slug as m_slug FROM Training t INNER JOIN Calendar c ON t.training_date = c.calendar_date INNER JOIN Machine m ON t.machine = m.id INNER JOIN Brand b ON m.brand = b.id LEFT JOIN Default_Schedule ds ON t.schedule_id = ds.id AND c.custom = 0 LEFT JOIN Custom_Schedule cs ON t.schedule_id = cs.id AND c.custom = 1 WHERE t.state = 1 AND training_date =  '$date' ORDER BY training_date, h_start;";
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows > 0){
                                        while ($row2 = $result2->fetch_assoc()){
                                            echo '<p>'.substr($row2['h_start'], 0, 5).'</p>';
                                        }
                                    }
                                    echo '</div></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </section>

   
</body>
</html>