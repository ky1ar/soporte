<?php
require_once 'db.php';
date_default_timezone_set('America/Bogota');
setlocale(LC_TIME, 'es_ES');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/fav.png">
    <title>Krear 3D | Capacitaciones</title>
    <?php require_once 'header_front.php'; ?>
</head>
<body>
    <div id="ky1-bar">
        <div class="ky1-wrp bar-enl"> 
            <a href="https://api.whatsapp.com/send?phone=51934760404" target="_blank" rel="nofollow"><img src="assets/img/wsp.svg" width="16" height="16" alt="ico">934 760 404</a>
            <a href="https://api.whatsapp.com/send?phone=51982001288" target="_blank" rel="nofollow"><img src="assets/img/wsp.svg" width="16" height="16" alt="ico">982 001 288</a>
            <a href="mailto:ventas2@krear3d.com" target="_blank" rel="nofollow"><img src="assets/img/eml.svg" width="16" height="16" alt="ico">ventas2@krear3d.com</a>
            <a href="https://bit.ly/2ZzWUeK" target="_blank" rel="nofollow"><img src="assets/img/map.svg" width="16" height="16" alt="ico">Calle Javier Fernández 262 Miraflores - Lima</a>
        </div>
    </div>

    <header id="ky1-hdr">
        <div class="ky1-wrp">
            <a href="/"><img class="hdr-lgo" width="150" height="42" src="assets/img/logod.webp" alt="Logo Krear 3D"></a>
            <ul>
                <li><a href="#">Consultar</a></li>
                <li><a href="capacitaciones">Capacitaciones</a></li>
                <li><a href="#">Conocimiento</a></li>
                <li><a href="#">Cursos</a></li>
            </ul>
            <a class="hdr-lnk" href="https://tiendakrear3d.com/">Tienda<img class="hdr-wsp" width="16" height="16" src="assets/img/tnd.svg" alt="ico"></a>
            <?php
            /*<div class="hdr-lft">
                <a href="admin.php">Administrador</a>
                <a href="login.php">Login</a>
            </div>*/
            ?>
        </div>
    </header>
    <section id="ky1-cap">
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
                <div class="cap-rgt">
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
                        <form action="post">
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
                                            <input type="file" id="archivo" accept=".pdf,.jpg,.jpeg">
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
                                <input type="hidden" name="changer" value="<?php echo $s_id ?>">
                                <button type="submit" id="scheduleSubmit" name="submit">Confirmar Reserva</button>
                            </div>     
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="ftr-top">
            <div class="ky1-wrp">
                <div class="ftr-itm">
                    <img class="ftr-lgo" width="152" height="38" src="assets/img/logo.webp" alt="Krear 3D">
                    <ul>
                        <li><p>Calle Javier Fernandez - Miraflores - Lima</p></li>
                        <li><p>Lu - Sa de 9:00 am a 6:00 pm</p></li>
                    </ul>
                </div>
    
                <div class="ftr-itm">
                    <b>ATENCION AL CLIENTE</b>
                    <ul>
                        <li><a href="mailto:atencionalcliente@krear3d.com" target="_blank" rel="nofollow noopener">atencionalcliente@krear3d.com</a></li>
                        <li><p>Lu - Vi de 9:00 am a 6:00 pm</p></li>
                        <li><a href="https://api.whatsapp.com/send?phone=51981104030" target="_blank" rel="nofollow noopener"><img src="assets/img/wsp.svg" alt="Whatsapp" width="12" height="12">&nbsp;981 104 030</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="ftr-bot">
            <div class="ky1-wrp">
                <ul>
                    <li><a href="/terminos/terminos-condiciones-y-garantia/">POLÍTICAS Y CONDICIONES</a></li>
                    <li><a href="/terminos/politicas-de-garantia-y-soporte-tecnico/">POLÍTICAS DE GARANTÍA</a></li>
                    <li><a href="/terminos/politicas-de-envios-lima-y-provincias/">POLÍTICAS DE ENVÍOS</a></li>
                </ul>
                
                <span class="ftr-cpy">Fabricaciones Digitales del Perú S.A. | RUC 20556316890<br>Krear 3D © 2023. Todos los derechos reservados.</span>
            </div>
        </div>
    </footer>
</body>
</html>

