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
    <section id="adminSection">
        <div class="wrapper">
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
                        <div id="calendarNavigation" class="buttons">
                            <div class="button <?php echo $s_levels == 4 ? 'admin':'disabled' ?>" id="calendarPrev">
                                <img width="12" height="12" src="assets/img/arrow.svg" alt="">
                            </div>
                            <div class="button <?php echo $s_levels == 4 ? 'admin':'' ?>" id="calendarNext">
                                <img width="12" height="12" src="assets/img/arrow.svg" alt="">
                            </div>
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
                                for ($i = 0; $i < $firstDayNum; $i++) {
                                    echo '<li></li>';
                                }
                                $todayDate = new DateTime();
                                $todayDate->setTime(0, 0, 0);
                                $sql =
                                "SELECT *
                                FROM Calendar
                                WHERE YEAR(calendar_date) = YEAR('$firstDay')
                                AND MONTH(calendar_date) = MONTH('$firstDay')";

                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $date = $row['calendar_date'];
                                    $detail = $row['detail'];
                                    $checkDate = new DateTime($date);

                                    $dayNum = date('d', strtotime($date));
                                    echo '<li'.(($today == $dayNum) ? ' class="today"' : '').'>';
                                    echo '<span'.($checkDate >= $todayDate ? ' class="calendarAdd"':'').' data-day="'.$dayNum.'">'.$dayNum.'</span>';
                                    echo '<div class="calendarView">';

                                    $sql2 =
                                    "SELECT t.id,
                                        t.training_state,
                                        t.training_start,
                                        w.image as w_image,
                                        w.nick as w_nick,
                                        m.model as m_model,
                                        m.slug as m_slug
                                    FROM Training t
                                    INNER JOIN Machine m ON t.machine = m.id
                                    INNER JOIN Brand b ON m.brand = b.id
                                    LEFT JOIN Users w ON t.worker = w.id
                                    WHERE (t.training_state = 0 OR t.training_state = 1 OR t.training_state = 2) AND training_date = '$date'
                                    ORDER BY training_start;";
                                    $result2 = $conn->query($sql2);
                                    if ($result2->num_rows > 0) {
                                        while ($row2 = $result2->fetch_assoc()):?>
                                            <div class="calendarViewRow
                                                <?= $row2['training_state'] == 0 ? 'pending' : ''?>
                                                <?= $row2['training_state'] == 2 ? 'finish' : ''?>"
                                                data-id="<?= $row2['id']?>"
                                                data-date="<?= $date?>"
                                                data-start="<?= $row2['training_start']?>"
                                                style="background-color: #<?= $row2['w_image']?>47;">
                                                <h3><?= $row2['m_model']?></h3>
                                                <div class="flex">
                                                    <h2 style="background-color: #<?= $row2['w_image']?>;">
                                                        <?= substr($row2['training_start'], 0, 5)?>
                                                    </h2>
                                                    <p><?php// $row2['w_nick']?></p>
                                                </div>
                                            </div>
                                        <?php endwhile;
                                    } else {
                                        echo $detail ? '<div class="dayDetail">'.$detail.'</div>':'';
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
    <section id="viewOverlay">
        <div class="modalBox">
            <div id="viewTraining">
                <div class="header">
                    <div class="block">
                        <h2 class="date"></h2>
                        <span class="schedule"></span>
                    </div>
                    <div class="content">
                        <div class="staticWorker">
                            <img src="assets/img/worker.svg" alt="">
                            <span class="worker"></span>
                        </div>
                        <div class="editableWorker">
                            <img src="assets/img/worker.svg" alt="">
                            <select class="id_worker" id="trainingWorker">
                                <?php $sql = "SELECT id, name FROM Users WHERE levels = 2 OR levels = 3 ORDER BY name";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()):?>
                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                <?php endwhile;
                                $conn->close();
                                ?>
                            </select>
                            <input type="hidden" id="pre" class="pre">
                            <button type="submit" id="upd_worker" data-level="<?php echo $s_levels ?>">
                                <img src="assets/img/sav.svg" alt="">
                            </button>
                        </div>
                        <div class="staticMeet">
                            <img src="assets/img/meet.svg" alt="">
                            <a class="meet" href="" target="_blank"></a>
                        </div>
                        <div class="editableMeet">
                            <img src="assets/img/meet.svg" alt="">
                            <input class="upd_meet" id="meetLink" type="text" placeholder="Ingrese el link de Google Meet">
                            <div id="actionMessage"></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="left">
                        <div class="title">
                            <h2 class="model"></h2>
                            <h2 class="count"></h2>
                        </div>
                        <h3 class="name"></h3>
                        <h3 class="document"></h3>
                        <div class="invoice" id="viewInvoice" data-src="">
                            <img width="12" height="12" src="assets/img/invoice.svg" alt="">Comprobante
                        </div>
                        <div class="link">
                            <p class="email"></p>
                            <div class="flex">
                                <img src="assets/img/wsp2.svg" alt="">
                                <a class="phone" href="" target="_blank" rel="nofollow"></a>
                            </div>
                            
                        </div>
                    </div>
                    <img class="image" src="" alt="">
                </div>
                <div class="actionButtons" data-id="" data-date="">
                    <div id="rejectTraining">Rechazar Solicitud</div>
                    <div id="aproveTraining">Aprobar Solicitud</div>
                </div>
                <div class="viewButtons" data-id="">
                    <div id="cancelTraining">Cancelar Capacitación</div>
                    <div id="finishTraining">Finalizar Capacitación</div>
                </div>
                <img class="modalClose" src="assets/img/x.svg" alt="">
            </div>
        </div>
    </section>

    <section id="previewInvoice">
        <div class="previewBox">
            <img class="close" src="assets/img/x.svg" alt="">
            <div id="invoiceFile"></div>
        </div>
    </section>
    <section id="AddOverlay">
        <div class="modalBox">
            <div id="addTraining">
                <div id="addLoadingResponse">
                    <div class="flex">
                        <img src="assets/img/fav.png" alt="Cargando..." />
                    </div>
                </div>
                <div id="scheduleSelector"></div>
                <div id="scheduleForm">
                    <form method="post" id="scheduleSubmit" enctype="multipart/form-data">
                        <div id="selectedSchedule"></div>
                        <p class="selectedMessage">Completa los campos de la reserva.</p>
                        <ul>
                            <li class="percent30">
                                <div class="formRow">
                                    <label for="">Documento</label>
                                    <input id="dniRUC" type="text" placeholder="DNI / RUC">
                                    <input id="clientId" type="hidden">
                                </div>
                            </li>
                            <li class="percent70">
                                <div class="formRow">
                                    <label for="">Cliente</label>
                                    <input id="client" type="text" placeholder="Nombre del cliente">
                                </div>
                            </li>
                            <li class="percent60">
                                <div class="formRow">
                                    <label for="">Email</label>
                                    <input id="email" type="email" placeholder="Ingresar correo">
                                </div>
                            </li>
                            <li class="percent40">
                                <div class="formRow">
                                    <label for="">Celular</label>
                                    <input id="phone" type="tel" placeholder="Ingresar celular">
                                </div>
                            </li>
                            <div class="percent75">
                                <li class="percent100">
                                    <div class="formRow">
                                        <label for="">Producto</label>
                                        <div class="formMachine">
                                            <input id="machine" type="text" placeholder="Nombre del equipo">
                                            <input id="machineId" type="hidden" >
                                            <div id="suggestions"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="percent100">
                                    <div class="formRow">
                                        <label for="">Comprobante de Pago</label>
                                        <input type="file" id="invoice" accept=".pdf,.jpg,.jpeg">
                                    </div>
                                </li>
                            </div>
                            <li class="percent25">
                                <div class="formRow">
                                    <label for="">Imagen</label>
                                    <img id="machineImage" src="assets/img/def.webp" alt="">
                                </div>
                            </li>
                        </ul> 
                        <div class="formButton">
                            <input type="hidden" id="picked">
                            <div id="scheduleFormMessage"></div>
                            <button type="submit" class="formSubmit">Reservar Capacitación</button>
                        </div>     
                    </form>
                </div>
                <img class="modalClose" src="assets/img/x.svg" alt="">
            </div>
        </div>
    </section>
</body>

</html>
