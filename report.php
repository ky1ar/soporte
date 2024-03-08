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
    <?php require_once 'header.php'; ?>
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

    <section id="rpt-dat">
        <div class="ky1-wrp">
            <div class="dat-pad">
                <ul class="dat-one">
                    <li class="lft-a">
                        <div class="cnt-lft">
                            <?php $sql = "SELECT id FROM Orders WHERE state != 9";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $c = $result->num_rows;?>
                            <h2><?php echo $c ?><span>Servicios</span></h2>
                            <p>En Actividad</p>
                        </div>
                        <img class="tml-img" src="assets/img/wip.svg" alt="">
                        
                    </li>
                    <li class=" lft-b">
                        <div class="cnt-lft">
                            <?php $sql = "SELECT id FROM Orders WHERE MONTH(dates) = MONTH(CURDATE()) AND YEAR(dates) = YEAR(CURDATE())";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $c = $result->num_rows;?>
                            <h2><?php echo $c ?><span>Servicios</span></h2>
                            <p>En este Mes</p>
                        </div>
                        <img class="tml-img" src="assets/img/cal2.svg" alt="">
                    </li>
                    <li class="lft-c">
                        <div class="cnt-lft">
                        <?php $sql = "SELECT id FROM Orders WHERE state = 9";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $c = $result->num_rows;?>
                            <h2><?php echo $c ?><span>Servicios</span></h2>
                            <p>Completados</p>
                        </div>
                        <img class="tml-img" src="assets/img/fns.svg" alt="">
                    </li>
                </ul>
            </div>
            <div class="dat-pad">
                <div class="dat-two">
                    <div class="two-hdr">
                        <h3>Historial de Ordenes<p>Listado de equipos ingresados al área de Soporte Técnico</p></h3>
                        <a href="#"><img src="assets/img/pls.svg" alt="" style="width: 1rem;">Añadir Orden</a>
                    </div>
                    <table class="rpt-tbl" border="0" cellspacing="0" cellpadding="0">
                        <tr class="row-hdr">
                            <th>No.</th>
                            <th>Orden</th>
                            <th>Técnico</th>
                            <th>Ingreso</th>
                            <th>Tipo</th>
                            <th>Origen</th>
                            <th>Estado</th>
                            <th>Ver</th>
                        </tr>
                        <?php 
                        $sql = "SELECT o.id, o.number as orders, o.dates, DATEDIFF(NOW(), o.dates) as pday, c.name, o.comments, c.email, c.document, c.phone, w.id as wid, w.name as wnm, m.model, b.name as bnm, m.slug, t.name as tnm, r.name as onm, o.state FROM Orders o INNER JOIN Machine m ON o.machine = m.id INNER JOIN Users c ON o.client = c.id INNER JOIN Brand b ON m.brand = b.id INNER JOIN Users w ON o.worker = w.id INNER JOIN Type t ON o.type = t.id INNER JOIN Origin r ON o.origin = r.id ORDER BY pday ASC;";
                        $result = $conn->query($sql);
                        $n = 1;
                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                                setlocale(LC_TIME, 'es_ES');
                                $date = strtotime($row['dates']);
                                $date = strftime("%e de %B de %Y", $date);
                                ?>
                            <tr>
                                <td><?php echo $n ?></td>
                                <td class="row-odr">
                                    <img width="48" class="tbl-img" src="assets/mac/<?php echo $row['slug'] ?>.webp" alt="">
                                    <div class="tbl-odr"><?php echo $row['orders'] ?><span><?php echo $row['model']?></span></div></td>
                                <td><?php echo $row['wnm'] ?></td>
                                <td><?php echo $date ?></td>
                                <td class="row-spn"><span><?php echo $row['tnm'] ?></span></td>
                                <td class="row-spn"><span><?php echo $row['onm'] ?></span></td>
                                <td class="row-stt"><?php echo $row['state'] == 9 ? '<span class="stt-fns">Finalizado</span>':'<span>Activo</span>' ?></td>
                                <td class="row-act"><img class="tbl-tec" src="assets/img/dot.svg" alt=""></td>
                            </tr>
                            <?php $n++;
                            endwhile;
                        endif; ?>
                    </table>
                </div>
                
            </div>
            <div id="itm-tml">

                <ul class="itm-ul" >
                    <?php 
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
                    $sql = "SELECT o.id, o.number as orders, o.dates, o.paid, o.dates as pday, c.name, o.comments, c.email, c.document, c.phone, w.id as wid, w.name as wnm, m.model, b.name as bnm, m.slug, t.id as tid, r.id as rid, t.name as tnm, r.name as onm, s.name as snm FROM Orders o INNER JOIN Machine m ON o.machine = m.id INNER JOIN Users c ON o.client = c.id INNER JOIN Brand b ON m.brand = b.id INNER JOIN Users w ON o.worker = w.id INNER JOIN Type t ON o.type = t.id INNER JOIN Status s ON o.state = s.id INNER JOIN Origin r ON o.origin = r.id ORDER BY pday ASC;";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): 
                            $pday = getPday($row['pday'], $tday, $fday);
                            ?>
                            <li class="itm-li" data-id="<?php echo $row['orders'] ?>">
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
            </div>
        </div>
    </section>

   
</body>
</html>