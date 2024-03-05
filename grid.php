<?php 
$currentPage = "Taller"; 
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php'; 
require_once 'includes/common/header.php';

//require_once 'db.php';

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

$stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin']; 

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
    <header id="ky1-hdr-adm">
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
                
                <?php
                //if( $s_levels == 3 || $s_levels == 4 ):
                    /*$sql = "SELECT w.id, w.name, w.nick, SUM(CASE WHEN o.state = 9 THEN 1 ELSE 0 END) AS total FROM Users w LEFT JOIN Orders o ON w.id = o.worker WHERE w.levels = 2 OR w.levels = 3 GROUP BY w.id ORDER BY total DESC, w.name";*/

                    $sql = "SELECT w.id, w.name, w.nick, SUM(CASE WHEN o.state = 9 AND MONTH(o.dates) = MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END) AS total FROM Users w LEFT JOIN Orders o ON w.id = o.worker WHERE (w.levels = 2 OR w.levels = 3) GROUP BY w.id ORDER BY total DESC, w.name";

                    $result = $conn->query($sql);
                    $p = 1;
                    while ($row = $result->fetch_assoc()):
                        switch ($p) {
                            case 1:
                                $ico = 'frs';
                                break;
                            case 2:
                                $ico = 'sec';
                                break;
                            default:
                                $ico = 'usr';
                        } ?>
                        <div class="flt-usr flt-act" data-usrf="<?php echo $row['id'] ?>"><img src="assets/img/<?php echo $row['total'] != 0 ? $ico : 'usr' ?>.svg" alt=""><?php echo $row['nick'] ?><span><?php echo $row['total'] ?></span></div>
                    <?php $p++;
                    endwhile; 
               // endif;?>
            </div>
            <div class="ky1-rgt">
            <input id="ky1-src" type="text" placeholder="Localizar Orden">
                <?php if( $s_levels == 3 || $s_levels == 4 ):?>
                    <div id="ky1-add" href="#"><img src="assets/img/pls.svg" alt="">Añadir Orden</div>
                <?php endif;?>
                <a href="https://api.whatsapp.com/send?phone=51946887982" target="_blank" rel="nofollow"><img src="assets/img/cnt.svg" alt=""></a>
                <?php if( $s_levels == 3 || $s_levels == 4 ):?>
                    <a href="report"><img src="assets/img/mnu.svg" alt=""></a>
                <?php endif;?>
            </div>
        </div>
    </header>

    <section id="ky1-rpt">
        <div class="ky1-wrp">
            <div id="rpt-ovr"></div>
            <div id="rpt-msg">
                <form class="msg-cnt" method="post">
                    <h2>¿Actualizar estado?</h2>
                    <p>Deseas actualizar el estado de la orden <b id="msg-ord"></b></p>
                    <div id="msg-img">
                        <img src="assets/img/thr.svg" alt="">
                        <img src="assets/img/r.svg" alt="">
                        <img src="assets/img/thr.svg" alt="">
                    </div>
                    <div class="msg-eml">
                        Enviar correo de actualización
                        <label class="eml-swt">
                            <input id="msg-chk" type="checkbox" name="check">
                            <span class="eml-sld"></span>
                        </label>
                    </div>
                    <div class="msg-cmm">
                        <label for="">Añadir notas</label>
                        <input id="msg-cmm" type="text" name="notes" placeholder="Opcional">
                    </div>
                    <div class="msg-btn">
                        <div id="msg-nop">Cancelar</div>
                        <input id="msg-chn" type="hidden" name="changer" value="<?php echo $s_id ?>">
                        <button type="submit" id="msg-yes" name="submit">Actualizar</button>
                    </div>
                    <img id="msg-cls" src="assets/img/x.svg" alt="">
                </form>
            </div>

            <div id="add-frm">
                <div class="frm-cnt">
                    <h2>Añadir Orden de Servicio</h2>
                    <form id="ky1-frm-ord" action="addOrder.php" method="post">
                        <div id="ky1-frm-msj"></div>
                        <ul>
                            <li class="frm-25">
                                <div class="frm-row">
                                    <label for="">Orden</label>
                                    <div class="frm-flx">
                                        <b>00</b>
                                        <input id="ky1-ords" type="text" name="order" placeholder="Número">
                                    </div>
                                </div>
                            </li>
                            <li class="frm-25">
                                <div class="frm-row">
                                    <label for="">Documento</label>
                                    <input id="ky1-doc" type="text" name="document" placeholder="DNI / RUC">
                                    <input id="ky1-cid" type="hidden" name="clientID">
                                </div>
                            </li>
                            <li class="frm-50">
                                <div class="frm-row">
                                    <label for="">Cliente</label>
                                    <input id="ky1-nme" type="text" name="client" placeholder="Nombre del cliente">
                                </div>
                            </li>
                            <li class="frm-100">
                                <div class="frm-row">
                                    <label for="">Comentarios</label>
                                    <input id="ky1-cmm" type="text" name="comments" placeholder="Opcional">
                                </div>
                            </li>
                            <li class="frm-60">
                                <div class="frm-row">
                                    <label for="">Correo</label>
                                    <input id="ky1-eml" type="email" name="email" placeholder="Ingresar correo">
                                </div>
                            </li>
                            <li class="frm-40">
                                <div class="frm-row">
                                    <label for="">Celular</label>
                                    <input id="ky1-phn" type="tel" name="phone" placeholder="Ingresar celular">
                                </div>
                            </li>

                            <div class="frm-75">
                                <li class="frm-100">
                                    <div class="frm-row">
                                        <label for="">Producto</label>
                                        <div class="frm-mch">
                                            <input id="ky1-mch" type="text" name="machine" placeholder="Nombre del equipo">
                                            <input id="ky1-mid" type="hidden" name="machineID">
                                            <div id="ky1-sgs"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="frm-100">
                                    <div class="frm-row">
                                        <label for="">Fecha de Ingreso</label>
                                        <input id="ky1-dte" type="date" name="date" value="" min="2023-01-01" max="">
                                    </div>
                                </li>
                            </div>

                            <li class="frm-25">
                                <div class="frm-row">
                                    <label for="">Imagen</label>
                                    <img id="ky1-mim" src="assets/img/def.webp" alt="">
                                </div>
                            </li>
                            <li class="frm-30">
                                <div class="frm-row">
                                    <label for="">Técnico</label>
                                    <select name="worker">
                                        <?php $sql = "SELECT id, name FROM Users WHERE levels = 2 OR levels = 3 ORDER BY name";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </li>
                            <li class="frm-30">
                                <div class="frm-row">
                                    <label for="">Tipo</label>
                                    <select name="type">
                                        <?php $sql = "SELECT id, name FROM Type ORDER BY name";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </li>
                            <li class="frm-30">
                                <div class="frm-row">
                                    <label for="">Origen</label>
                                    <select name="origin">
                                    <?php $sql = "SELECT id, name FROM Origin ORDER BY name";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </li>
                        </ul>
                        <div class="frm-btn">
                            <div id="frm-nop">Cancelar</div>
                            <input type="hidden" name="changer" value="<?php echo $s_id ?>">
                            <button type="submit" id="frm-yes" name="submit">Añadir</button>
                        </div>
                    </form>
                    <img id="frm-cls" src="assets/img/x.svg" alt="">
                </div>
            </div>

            <ul id="add-tml">
                <?php

                $sql = "SELECT * FROM Status";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                    if ($row['id'] != 9):?>
                        <li>
                            <img src="assets/img/<?php echo $stt_img[$row['id']-1] ?>.svg" alt="">
                            <div class="tml-lne"></div>
                            <div class="tml-dot"><?php echo $row['id'] ?></div>
                            <div class="tml-ttl"><?php echo $row['name'] ?></div>
                        </li>
                    <?php endif;
                endwhile; ?>
            </ul>
            
            <div id="lst-tml">
            <?php
                $con = '';
                //if( $s_levels == 2 ) $con = "AND w.id = $s_id";
                $tday = date("Y-m-d");
                $fday = [
                    "2023-12-25",
                    "2024-01-01",
                    "2024-03-28",
                    "2024-03-29",
                    "2024-05-01",
                    "2024-06-07",
                    "2024-06-29",
                    "2024-07-23",
                    "2024-07-28",
                    "2024-07-29",
                    "2024-08-06",
                    "2024-08-30",
                    "2024-10-08",
                    "2024-11-01",
                    "2024-12-08",
                    "2024-12-09",
                    "2024-12-25",
                ];

                function getPday($date, $tday, $fday) {
                    $ini = new DateTime($date);
                    $fin = new DateTime($tday);
                    
                    $int = new DateInterval('P1D');
                    $per = new DatePeriod($ini, $int, $fin);
                    
                    $pday = 0;
                    
                    foreach ($per as $day) {
                        $sday = $day->format('N');
                        $sfday = in_array($day->format('Y-m-d'), $fday);
                        
                        if ($sday <= 5 && !$sfday) {
                            $pday++;
                        }
                    }
                    return $pday;
                }

                for ( $s = 1; $s < 10; $s++):
                    if ($s != 9): ?>
                        <ul data-id="<?php echo $s; ?>">
                        <?php $sql = "SELECT o.id, o.number AS orders, o.paid, o.dates AS pday, w.id AS wid, w.name AS wnm, m.model, m.slug, t.name AS tnm, os.dates AS sday FROM Orders o INNER JOIN Machine m ON o.machine = m.id INNER JOIN Users w ON o.worker = w.id INNER JOIN Type t ON o.type = t.id LEFT JOIN Orders_Status os ON o.id = os.orders WHERE o.state = $s AND os.stat = $s " . $con ." ORDER BY pday ASC;";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()): 
                                    
                                    if( $s != 9) {
                                        $pday = getPday($row['pday'], $tday, $fday);
                                        $sday = getPday($row['sday'], $tday, $fday);
                                    } else {
                                        $pday = getPday($row['pday'], $row['sday'], $fday);
                                        $sday = getPday($row['sday'], $row['sday'], $fday);
                                    }
                                    
                                    $color = ($s < 5) ? (($pday < 4) ? 'one' : (($pday < 8) ? 'two' : 'thr')) : (($pday < 14) ? 'one' : (($pday < 18) ? 'two' : 'thr'));
                                    ?>
                                    <li class="stt-<?php echo $color ?>" data-ord="<?php echo $row['id'] ?>" data-id="<?php echo $row['orders'] ?>" data-usr="<?php echo $row['wid'] ?>" data-day="<?php echo $pday ?>">
                                        <?php if( ($s_levels == 3 || $s_levels == 4) && $s != 9 ):?>
                                            <img class="itm-upd" src="assets/img/r.svg" alt="">
                                        <?php endif; ?>
                                        <div class="itm-cnt">
                                            <div class="itm-flx">
                                                <h2 class="ky1-sor"><b>00</b><?php echo $row['orders'] ?></h2>
                                                <div class="itm-dcn">
                                                    <h2 class="itm-sdy"><?php echo $sday ?></h2>
                                                    <h2 class="itm-pdy"><?php echo $pday ?></h2>
                                                </div>
                                            </div>
                                            <div class="itm-flx">
                                                <div>
                                                    <h3><?php echo $row['wnm'] ?></h3>
                                                    <h4><?php echo $row['model']?></h4>
                                                    <div class="itm-aux">
                                                        <h5><?php echo $row['tnm'] ?></h5>
                                                        <?php echo $row['paid'] == 1 ? '<h5>P</h5>':'' ?>
                                                    </div>
                                                </div>
                                                <img class="itm-img" src="assets/mac/<?php echo $row['slug'] ?>.webp" alt="">
                                            </div>
                                        </div>
                                    </li>
                                <?php endwhile;
                            endif; ?>
                        </ul>
                    <?php endif;
                endfor; ?>
            </div>


            <div id="itm-tml">
            <?php for( $s = 1; $s < 10; $s++): 
                if ($s != 9):?>
                    <ul class="itm-ul" data-id="<?php echo $s; ?>">
                    <?php $sql = "SELECT o.id, o.number as orders, o.dates, o.paid, o.dates as pday, c.name, o.comments, c.email, c.document, c.phone, w.id as wid, w.name as wnm, m.model, b.name as bnm, m.slug, t.id as tid, r.id as rid, t.name as tnm, r.name as onm, s.name as snm FROM Orders o INNER JOIN Machine m ON o.machine = m.id INNER JOIN Users c ON o.client = c.id INNER JOIN Brand b ON m.brand = b.id INNER JOIN Users w ON o.worker = w.id INNER JOIN Type t ON o.type = t.id INNER JOIN Status s ON o.state = s.id INNER JOIN Origin r ON o.origin = r.id WHERE o.state = $s ORDER BY pday ASC;";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): 
                            $pday = getPday($row['pday'], $tday, $fday);

                            $color = ($s < 5) ? (($pday < 4) ? 'one' : (($pday < 8) ? 'two' : 'thr')) : (($pday < 14) ? 'one' : (($pday < 18) ? 'two' : 'thr'));
                            ?>
                            <li class="itm-li stt-<?php echo $color ?>" data-id="<?php echo $row['orders'] ?>">
                                <img class="itm-cls" src="assets/img/x.svg" alt="">
                                <div class="itm-smy">
                                    <form class="smy-lft" method="post">
                                        <div class="itm-hdr">
                                            <div class="itm-lft">
                                                <div class="itm-wfl">
                                                    <h2>Orden <b>00</b><?php echo $row['orders'] ?></h2>
                                                    <div class="itm-wrk">
                                                        <img src="assets/img/tec.svg" alt="">
                                                        <?php if( $s_levels == 3 || $s_levels == 4 ):?>
                                                        <select name="worker" class="ky1-wrk">
                                                            <?php
                                                            $sqlU = "SELECT w.id, w.name FROM Users w WHERE w.levels = 2 OR w.levels = 3 ORDER BY w.name ";
                                                            $resultU = $conn->query($sqlU);
                                                            $p = 1;
                                                            while ($rowU = $resultU->fetch_assoc()): ?>
                                                                <option <?php echo $row['wid'] == $rowU['id'] ? 'selected':'' ?> value="<?php echo $rowU['id'] ?>"><?php echo $rowU['name'] ?></option>
                                                            <?php $p++;
                                                            endwhile;?>
                                                        </select> 
                                                        <?php else: ?>
                                                            <h3><?php echo $row['wnm'] ?></h3>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php if( $s_levels == 3 || $s_levels == 4 ): ?>
                                                    <div class="itm-box">
                                                        <input type="hidden" class="ky1-oid" name="orders" value="<?php echo $row['id'] ?>">
                                                        <button type="submit" class="edt-yes" name="submit"><img src="assets/img/sav.svg" alt=""></button>
                                                        <button class="itm-btn"><img src="assets/img/edt.svg" alt=""></button>
                                                        <button class="itm-btn"><img src="assets/img/edt.svg" alt=""></button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <span><?php echo $pday ?> días</span>
                                        </div>
                                        <div class="itm-crd">
                                            <div class="imt-dat">
                                                <h3><?php echo $row['bnm'] . ' ' . $row['model']?></h3>
                                                <h2><?php echo $row['name'] ?></h2>
                                                <h3><?php echo $row['document'] ?></h3>
                                                <div class="itm-lnk">
                                                    <p><?php echo $row['email'] ?></p>
                                                    <a class="itm-wsp" href="https://api.whatsapp.com/send?phone=51<?php echo $row['phone'] ?>" target="_blank" rel="nofollow"><?php echo implode(' ', str_split($row['phone'], 3)) ?><img src="assets/img/wsp2.svg" alt=""></a>
                                                    <p><?php echo $row['comments'] ?></p>
                                                </div>
                                                <div class="itm-otr">
                                                <?php if( $s_levels == 3 || $s_levels == 4 ):?>
                                                    <select name="type" class="ky1-typ">
                                                        <?php
                                                        $sqlU = "SELECT t.id, t.name FROM Type t ORDER BY t.name ";
                                                        $resultU = $conn->query($sqlU);
                                                        $p = 1;
                                                        while ($rowU = $resultU->fetch_assoc()): ?>
                                                            <option <?php echo $row['tid'] == $rowU['id'] ? 'selected':'' ?> value="<?php echo $rowU['id'] ?>"><?php echo $rowU['name'] ?></option>
                                                        <?php $p++;
                                                        endwhile;?>
                                                    </select> 
                                                    <select name="origin" class="ky1-ori">
                                                        <?php
                                                        $sqlW = "SELECT o.id, o.name FROM Origin o ORDER BY o.name ";
                                                        $resultW = $conn->query($sqlW);
                                                        $p = 1;
                                                        while ($rowU = $resultW->fetch_assoc()): ?>
                                                            <option <?php echo $row['rid'] == $rowU['id'] ? 'selected':'' ?> value="<?php echo $rowU['id'] ?>"><?php echo $rowU['name'] ?></option>
                                                        <?php $p++;
                                                        endwhile;?>
                                                    </select> 
                                                    <?php else: ?>
                                                        <h5><?php echo $row['tnm'] ?></h5>
                                                        <h5><?php echo $row['onm'] ?></h5>
                                                    <?php endif; ?>
                                                    
                                                    <select name="type" class="ky1-pid">
                                                        <option <?php echo $row['paid'] == 0 ? 'selected':'' ?> value="0">No pagado</option>
                                                        <option <?php echo $row['paid'] == 1 ? 'selected':'' ?> value="1">Pagado</option>
                                                    </select> 
                                                </div>
                                            </div>
                                            <img class="itm-img" src="assets/mac/<?php echo $row['slug'] ?>.webp" alt="">
                                        </div>
                                        <span class="smy-stt">Estado actual de la orden:<b>En <?php echo $row['snm'] ?></b></span>
                                        <div class="smy-tme">
                                            <h2>Total de días desde el ingreso</h2>
                                            <div class="tme-cnt">
                                                <div id="tme-pdy" class="tme-top" data-stt="<?php echo $pday ?>"><b><?php echo $pday ?> días</b></div>
                                                <div class="tme-bot"></div>
                                                <b>0</b>
                                                <b>20</b>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="smy-rgt">
                                        <ul>
                                            <?php 
                                            $sql2 = "SELECT s.name as snm, os.notes, c.name as cnm, os.dates FROM Orders_Status os INNER JOIN Status s ON os.stat = s.id INNER JOIN Users c ON os.changer = c.id  WHERE os.orders = $row[id];";
                                            $result2 = $conn->query($sql2);
                                            $n = 1;
                                            $t = $result2->num_rows;
                                            while ($row2 = $result2->fetch_assoc()):
                                                setlocale(LC_TIME, 'es_ES');
                                                $date = strtotime($row2['dates']);
                                                $date = strftime("%e de %B de %Y", $date);
                                                ?>
                                                <li class="<?php echo $n == $t ? 'smy-crr':'smy-act' ?>">
                                                    <i><?php echo $n ?></i>
                                                    <div class="hst-cnt">
                                                        <img src="assets/img/<?php echo $stt_img[$n-1] ?>.svg" alt="">
                                                        <div class="hst-ttl">
                                                            <h3><?php echo $row2['snm'] ?><em>- <?php echo $row2['cnm'] ?></em></h3>
                                                            <p><?php echo $row2['notes'] ? ($row2['notes']):'' ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="hst-dte"><?php echo $date ?></div>
                                                </li>
                                            <?php $n++;
                                            endwhile; 

                                            $sql3 = "SELECT * FROM Status WHERE id > $t";
                                            $result3 = $conn->query($sql3);
                                            while ($row3 = $result3->fetch_assoc()):?>
                                                <li>
                                                    <i><?php echo $n ?></i>
                                                
                                                    <div class="hst-cnt">
                                                    <img src="assets/img/<?php echo $stt_img[$n-1] ?>.svg" alt="">
                                                        <div class="hst-ttl">
                                                            <h3><?php echo $row3['name'] ?></h3>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php $n++;
                                            endwhile;
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php 
                        endwhile;
                    endif;
                    ?>
                    </ul>
                <?php endif;
            endfor; ?>
            </div>
            
        </div>
    </section>

   
</body>
</html>