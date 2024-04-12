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

$currentPage = "H. de Equipos";
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
    <section id="rpt-dat">
        <div class="wrapper">
            <div class="dat-pad">
                <div class="dat-two">
                    <div class="two-hdr">
                        <h3>Historial de Ordenes<p>Listado de equipos ingresados al área de Soporte Técnico</p>
                        </h3>
                        <a href="#"><img src="assets/img/pls.svg" alt="" style="width: 1rem;">Añadir Orden</a>
                    </div>
                    <table aria-describedby="Training Report" class="rpt-tbl" border="0">
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
                        $sql = "SELECT o.id,
                        o.number as orders,
                        o.dates,
                        DATEDIFF(NOW(), o.dates) as pday,
                        c.name,
                        o.comments,
                        c.email,
                        c.document,
                        c.phone,
                        w.id as wid,
                        w.name as wnm,
                        m.model,
                        b.name as bnm,
                        m.slug,
                        t.name as tnm,
                        r.name as onm,
                        o.state
                        FROM Orders o
                        INNER JOIN Machine m ON o.machine = m.id
                        INNER JOIN Users c ON o.client = c.id
                        INNER JOIN Brand b ON m.brand = b.id
                        INNER JOIN Users w ON o.worker = w.id
                        INNER JOIN Type t ON o.type = t.id
                        INNER JOIN Origin r ON o.origin = r.id
                        ORDER BY pday ASC;";
                        $result = $conn->query($sql);
                        $n = 1;
                        if ($result->num_rows > 0) :
                            while ($row = $result->fetch_assoc()) :
                                setlocale(LC_TIME, 'es_ES');
                                $date = strtotime($row['dates']);
                                $date = strftime("%e de %B de %Y", $date);
                        ?>
                                <tr data-id="<?php echo $row['orders'] ?>">
                                    <td><?php echo $n ?></td>
                                    <td class="row-odr">
                                        <img width="48" class="tbl-img" src="assets/mac/<?php echo $row['slug'] ?>.webp" alt="">
                                        <div class="tbl-odr">
                                            <?php echo $row['orders'] ?><span><?php echo $row['model'] ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $row['wnm'] ?></td>
                                    <td><?php echo $date ?></td>
                                    <td class="row-spn"><span><?php echo $row['tnm'] ?></span></td>
                                    <td class="row-spn"><span><?php echo $row['onm'] ?></span></td>
                                    <td class="row-stt">
                                        <?php
                                        if ($row['state'] == 9) {
                                            echo '<span class="stt-fns">Finalizado</span>';
                                        } else {
                                            echo '<span>Activo</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="row-act"><img class="tbl-tec" src="assets/img/dot.svg" alt=""></td>

                                </tr>
                        <?php $n++;
                            endwhile;
                        endif; ?>
                    </table>
                    <span id="totalRows" data-total="<?php echo $result->num_rows; ?>"></span>
                    <div class="pagination" id="pagination"></div>
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
                            $color = $pday < 8 ? 'one' : ($pday < 14 ? 'two' : 'thr');
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
                                                        <h3><?php echo $row['wnm'] ?></h3>
                                                    </div>
                                                </div>
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
                                                    <h5><?php echo $row['tnm'] ?></h5>
                                                    <h5><?php echo $row['onm'] ?></h5>
                                                    <h5><?php echo $row['paid'] == 0 ? 'No pagado':'Pagado' ?></h5>
                                                </div>
                                            </div>
                                            <img class="itm-img" src="assets/mac/<?php echo $row['slug'] ?>.webp" alt="">
                                        </div>
                                        <span class="smy-stt">Estado de la orden:<b>En <?php echo $row['snm'] ?></b></span>
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
            <div id="rpt-ovr"></div>
        </div>
    </section>
    <script type="text/javascript" src="assets/js/test.js?v=<?php echo $GLOBALS['ver']; ?>"></script>
</body>

</html>