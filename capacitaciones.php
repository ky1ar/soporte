<?php 
$currentPage = "Citas"; 
require_once 'includes/globals.php'; 
require_once 'includes/header.php'; 
require_once 'includes/admin.php';
require_once 'includes/client.php';
?>
</head>
<body>
    <?php 
    require_once 'includes/topBar.php';
    require_once 'includes/navBar.php';  
    ?>
    <section id="frontSlider">
        <div class="ky1-wrp">
            <img width="1920" height="630" src="assets/slider/b1.webp">
            <p>Estamos encantados de que inicies tu travesía con nosotros. Prepara tu equipo, lleva a cabo las primeras pruebas y comencemos juntos.</p>
        </div>
    </section>

    <section id="ky1-cap">
        <div class="ky1-top">
            <h2>Agenda Aquí</h2>
        </div>
        <div class="ky1-wrp">
            <div class="cap-cnt">
                <div class="cap-lft">
                    <img src="assets/img/cap.svg" alt="ico" width="64" height="64">
                    <h1>Agenda tu Capacitación Personalizada</h1>
                    <p>Estamos encantados de que inicies tu travesía con nosotros. Prepara tu equipo, lleva a cabo las primeras pruebas y comencemos juntos.</p>
                    <p>Por favor, ten en cuenta los siguientes puntos al agendar tu capacitación:</p>
                    <ul>
                        <li>Selecciona la fecha y el horario disponibles.</li>
                        <li>Asegúrate de tener tu cámara y micrófono listos.</li>
                        <li>Conéctate puntualmente, con una tolerancia de 10 minutos.</li>
                    </ul>
                </div>
                <div class="cap-rgt" id="scheduleCalendar">
                    
                    <?php
                    $today = date('d');
                    $firstDay = date('Y-m-01');
                    $firstDayNum = date('N', strtotime($firstDay));
                    ?>
                    <div class="cap-hdr">
                        <!--<button id="calendarToday">Hoy</button>-->
                        <span id="monthName"><?php echo strftime('%B %Y', strtotime($firstDay)) ?></span>
                        <div id="calendarNavigation" class="cap-btn">
                            <div class="btn disabled" id="calendarPrev"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                            <div class="btn" id="calendarNext"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                        </div>
                        <div id="calendarBackDiv">
                            <div class="btn txt" id="calendarBack">Volver</div>
                        </div>
                    </div>
                    <div id="calendarContainer">
                        <div id="loadingResponse">
                            <div class="flex">
                                <img src="assets/img/fav.png" alt="Cargando..." />
                            </div>
                        </div>
                    
                        <div id="calendarSelector">
                            <ul class="cld-box box-hdr">
                                <li>dom</li>
                                <li>lun</li>
                                <li>mar</li>
                                <li>mié</li>
                                <li>jue</li>
                                <li>vie</li>
                                <li>sáb</li>
                            </ul>
                            <ul class="cld-box" id="calendarTable">
                                <?php
                                for ($i = 0; $i < $firstDayNum; $i++) { echo '<li></li>'; }
                                
                                $sql = "SELECT * FROM Calendar WHERE YEAR(calendar_date) = YEAR('$firstDay') AND MONTH(calendar_date) = MONTH('$firstDay')";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $dayNum = date('d', strtotime($row['calendar_date']));
                                    $state = $row['state'];
                                    
                                    echo '<li' . (($today == $dayNum) ? ' class="today"' : '') . '>';
                                    if ( $dayNum <= $today || $state == 0 ) { echo '<span>'.$dayNum.'</span>'; } 
                                    else { echo '<div class="boxDay" data-day="'.$dayNum.'">'.$dayNum.'</div>'; }
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div id="scheduleSelector"></div>
                        <div id="scheduleForm">
                            <form action="post" id="scheduleSubmit" enctype="multipart/form-data">
                                <div id="selectedSchedule"></div>
                                <p class="selectedMessage">Completa los campos para reservar tu cita.</p>
                                
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
                                                <label for="">Boleta</label>
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

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

