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
                            <th></th>
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
        </div>
    </section>

   
</body>
</html>