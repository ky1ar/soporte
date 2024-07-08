<?php
$currentPage = "Capacitaciones";
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php';
require_once 'includes/common/header.php';
?>
</head>

<body>
    <?php
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';
    ?>
    <section id="frontSlider">
        <div class="wrapper">
            <img class="imaCap" width="1920" height="630" src="assets/img/capacitaciones-pc.webp">
            <!-- <p>Estamos encantados de que inicies tu travesía con nosotros. Prepara tu equipo, lleva a cabo las primeras pruebas y comencemos juntos.</p> -->
        </div>
    </section>

    <section id="trainingSection">
        <div class="top">
            <h2>Agenda Aquí</h2>
        </div>
        <div class="wrapper">
            <div class="content">
                <div class="left">
                    <span class="title">Toma en cuenta los siguientes puntos al reservar tu capacitación:</span>
                    <div class="step">
                        <span><img width="22" height="22" src="assets/img/register.svg" alt=""></span>
                        <div>
                            <div class="ex-body">
                                <h3>Agenda tu capacitación</h3><button>Ver Ejemplo</button>
                            </div>
                            <p>Elige la fecha y hora que mejor te convenga.</p>
                        </div>
                    </div>
                    <div class="step">
                        <span><img width="22" height="22" src="assets/img/pc.svg" alt=""></span>
                        <div>
                            <h3>Prepárate</h3>
                            <p>Asegúrate de tener tu cámara y micrófono listos.</p>
                        </div>
                    </div>
                    <div class="step">
                        <span><img width="22" height="22" src="assets/img/time.svg" alt=""></span>
                        <div>
                            <h3>Llega a tiempo</h3>
                            <p>Conéctate a tiempo, tienes un margen de 10 minutos.</p>
                        </div>
                    </div>
                </div>
                <div class="right" id="scheduleCalendar">
                    <?php
                    $today = date('d');
                    $firstDay = date('Y-m-01');
                    $firstDayNum = date('N', strtotime($firstDay));
                    ?>
                    <div class="header">
                        <span id="monthName"><?php echo strftime('%B %Y', strtotime($firstDay)) ?></span>
                        <div id="calendarNavigation" class="buttons">
                            <div class="button disabled" id="calendarPrev"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                            <div class="button" id="calendarNext"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                        </div>
                        <div id="calendarBackDiv">
                            <div class="button text" id="calendarBack">Volver</div>
                        </div>
                    </div>
                    <div id="calendarContainer">
                        <div id="loadingResponse">
                            <div class="flex">
                                <img src="assets/img/fav.png" alt="Cargando..." />
                            </div>
                        </div>

                        <div id="calendarSelector">
                            <ul class="box header">
                                <li>dom</li>
                                <li>lun</li>
                                <li>mar</li>
                                <li>mié</li>
                                <li>jue</li>
                                <li>vie</li>
                                <li>sáb</li>
                            </ul>
                            <ul class="box" id="calendarTable">
                                <?php
                                for ($i = 0; $i < $firstDayNum; $i++) {
                                    echo '<li></li>';
                                }

                                $sql = "SELECT * FROM Calendar WHERE YEAR(calendar_date) = YEAR('$firstDay') AND MONTH(calendar_date) = MONTH('$firstDay')";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $dayNum = date('d', strtotime($row['calendar_date']));
                                    $state = $row['state'];

                                    echo '<li' . (($today == $dayNum) ? ' class="today"' : '') . '>';
                                    if ($dayNum <= $today || $state == 0) {
                                        echo '<span>' . $dayNum . '</span>';
                                    } else {
                                        echo '<div class="boxDay" data-day="' . $dayNum . '">' . $dayNum . '</div>';
                                    }
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div id="scheduleSelector"></div>
                        <div id="scheduleForm">
                            <form method="post" id="scheduleSubmit" enctype="multipart/form-data">
                                <div id="selectedSchedule"></div>
                                <p class="selectedMessage">Completa los campos para tu reserva.</p>

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
                                                    <input id="machineId" type="hidden">
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
                                    <button type="submit" class="formSubmit">Confirmar Reserva</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require_once 'includes/common/footer.php'; ?>
</body>

</html>