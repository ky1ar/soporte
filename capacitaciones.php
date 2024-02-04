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
                    <div class="cap-cld">
                        <?php
                        $today = date('d');
                        $firstDay = date('Y-m-01');
                        $firstDayNum = date('N', strtotime($firstDay));
                        ?>
                        <div class="cap-hdr">
                            <button id="calendarToday">Hoy</button>
                            <span id="monthName"><?php echo strftime('%B %Y', strtotime($firstDay)) ?></span>
                            <div class="cap-btn">
                                <div class="btn disabled" id="calendarPrev"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                                <div class="btn" id="calendarNext"><img width="12" height="12" src="assets/img/arrow.svg" alt=""></div>
                            </div>
                        </div>
                        <ul class="cld-box">
                            <li>dom</li>
                            <li>lun</li>
                            <li>mar</li>
                            <li>mié</li>
                            <li>jue</li>
                            <li>vie</li>
                            <li>sáb</li>
                        </ul>
                        <ul class="cld-box">
                            <?php

                            for ($i = 0; $i < $firstDayNum; $i++) { echo '<li></li>'; }
                            
                            $sql = "SELECT * FROM Calendar WHERE YEAR(calendar_date) = YEAR('$firstDay') AND MONTH(calendar_date) = MONTH('$firstDay')";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $dayNum = date('d', strtotime($row['calendar_date']));
                                $state = $row['state'];
                                
                                echo '<li>';
                                if ( $dayNum < $today || $state == 0 ) {
                                    echo '<span>' . $dayNum . '</span>';
                                } else {
                                    echo '<a href="" class="ky1-slc-day">' . $dayNum . '</a>';
                                }
                                echo '</li>';
                            }
                        ?>
                        </ul>
                    </div>
                    <div class="ky1-slc-hou">
                        <div class="hou-hdr">
                            Lunes 15 de enero
                        </div>
                        <ul>
                            <li><a href="">09:30</a></li>
                            <li><a href="">16:00</a></li>
                        </ul>
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

<?php/*
SELECT
    calendar.t_date,
    COUNT(DISTINCT COALESCE(cs.id, ds.id)) AS avl,
    MAX(cs.enabled) AS estado_enabled
FROM (
    SELECT DATE('2024-02-01') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS t_date
    FROM
        (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
        CROSS JOIN 
        (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
        CROSS JOIN 
        (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
    WHERE DATE('2024-02-01') + INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY BETWEEN '2024-02-01' AND '2024-02-29'
) AS calendar
LEFT JOIN Custom_Schedule AS cs ON calendar.t_date = cs.t_date
LEFT JOIN Default_Schedule AS ds ON DAYOFWEEK(calendar.t_date) = ds.t_day
GROUP BY calendar.t_date
ORDER BY calendar.t_date;


SELECT 
    DAYOFWEEK('2024-02-01') AS day_of_week, 
    COALESCE(MAX(cs.h_start), ds.h_start) AS h_start, 
    COALESCE(MAX(cs.h_end), ds.h_end) AS h_end 
FROM 
    Default_Schedule AS ds 
LEFT JOIN 
    Custom_Schedule AS cs ON cs.t_date = '2024-02-01' AND ds.t_day = DAYOFWEEK('2024-02-01')
GROUP BY 
    ds.h_start, ds.h_end;



SELECT DAYOFWEEK('2024-02-01') AS day_of_week, ds.h_start, ds.h_end FROM Default_Schedule AS ds WHERE ds.t_day = DAYOFWEEK('2024-02-01');

SELECT DAYOFWEEK('2024-02-01') AS day_of_week, COALESCE(MAX(cs.h_start), ds.h_start) AS h_start, COALESCE(MAX(cs.h_end), ds.h_end) AS h_end FROM Default_Schedule AS ds LEFT JOIN Custom_Schedule AS cs ON cs.t_date = '2024-02-01' AND ds.t_day = DAYOFWEEK('2024-02-01') GROUP BY ds.h_start, ds.h_end;
