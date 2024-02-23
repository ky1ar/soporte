<?php
$currentPage = "Seguimiento de Orden"; 
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php'; 
require_once 'includes/common/header.php';

if(isset($_GET['number'])):
    $orderNumber = $_GET['number'];

    $sql = "SELECT o.id, o.number as orders, o.dates,  o.dates as pday, c.name, o.comments, c.email, c.document, c.phone, w.id as wid, w.name as wnm, m.model, b.name as bnm, m.slug, t.name as tnm, r.name as onm, o.state, s.name as snm FROM Orders o INNER JOIN Machine m ON o.machine = m.id INNER JOIN Users c ON o.client = c.id INNER JOIN Brand b ON m.brand = b.id INNER JOIN Users w ON o.worker = w.id INNER JOIN Type t ON o.type = t.id INNER JOIN Status s ON o.state = s.id INNER JOIN Origin r ON o.origin = r.id WHERE o.number = '$orderNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) { 
        $order = $result->fetch_assoc();
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
        $pday = getPday($pday, $tday, $fday);
    }
    $stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin']; 

?>
</head>
<body>
    <?php 
    require_once 'includes/bar/topBar.php';
    require_once 'includes/bar/navigationBar.php';  
    ?>
    <section id="ky1-ord">
        <div class="ky1-wrp">
            <div class="ord-tml">
                <ul class="tml-lst">
                    <?php
                    $sql = "SELECT s.id, s.name FROM Orders_Status os INNER JOIN Status s ON os.stat = s.id WHERE os.orders = $order[id];";
                    $result = $conn->query($sql);
                    $n = 1;
                    $t = $result->num_rows;
                    while ($row = $result->fetch_assoc()):?>
                        <li class="tml-itm tml-act">
                            <i class="tmt-lne"></i>
                            <b class="tmt-dot"><?php echo $n ?></b>
                            <img class="tml-img" src="assets/img/<?php echo $stt_img[$row['id']-1] ?>.svg" alt="">
                            <span><?php echo $row['name'] ?></span>
                        </li>
                    <?php $n++;
                    endwhile; 

                    $sql = "SELECT * FROM Status WHERE id > $t";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()):?>
                        <li class="tml-itm">
                            <i class="tmt-lne"></i>
                            <b class="tmt-dot"><?php echo $n ?></b>
                            <img class="tml-img" src="assets/img/<?php echo $stt_img[$row['id']-1] ?>.svg" alt="">
                            <span><?php echo $row['name'] ?></span>
                        </li>
                    <?php $n++;
                    endwhile;?>
                </ul>
            </div>
            <div class="ord-smy">
                <div class="smy-lft">
                    <div class="itm-hdr">
                        <div class="itm-lft">
                            <h2>Orden <b>00</b><?php echo $order['orders'] ?></h2>
                            <h3><img src="assets/img/tec.svg" alt=""><?php echo $order['wnm'] ?></h3>
                        </div>
                        <span><?php echo $pday ?> días</span>
                    </div>
                    <div class="itm-crd">
                        <div class="imt-dat">
                            <h3><?php echo $order['bnm'] . ' ' . $order['model']?></h3>
                            <h2><?php echo $order['name'] ?></h2>
                            <div class="itm-lnk">
                                <p><?php echo $order['email'] ?></p>
                            </div>
                            <div class="itm-otr">
                                <h5><?php echo $order['tnm'] ?></h5>
                                <h5><?php echo $order['onm'] ?></h5>
                            </div>
                        </div>
                        <img class="itm-img" src="assets/mac/<?php echo $order['slug'] ?>.webp" alt="">
                    </div>
                    <span class="smy-stt">Estado actual de la orden: <b><?php echo $order['snm'] ?></b></span>
                    <div class="smy-tme">
                        <h2>Total de días desde el ingreso</h2>
                        <div class="tme-cnt">
                            <div id="tme-pdy" class="tme-top" data-stt="<?php echo $pday ?>"><b><?php echo $pday ?> días</b></div>
                            <div class="tme-bot"></div>
                            <b>0</b>
                            <b>20</b>
                        </div>
                    </div>
                </div>

                <div class="smy-rgt">
                    <ul>
                        <?php
                        $sql = "SELECT s.name as snm, os.notes, c.name as cnm, os.dates FROM Orders_Status os INNER JOIN Status s ON os.stat = s.id INNER JOIN Users c ON os.changer = c.id  WHERE os.orders = $order[id];";
                        $result = $conn->query($sql);
                        $n = 1;
                        $t = $result->num_rows;
                        setlocale(LC_TIME, 'es_ES');
                        while ($row = $result->fetch_assoc()):
                            $date = strtotime($row['dates']);
                            $date = strftime("%e de %B de %Y", $date);
                            ?>
                            <li class="<?php echo $n == $t ? 'smy-crr':'smy-act' ?>">
                                <i><?php echo $n ?></i>
                                <div class="hst-cnt">
                                    <div class="hst-ttl">
                                        <h3><?php echo $row['snm'] ?></h3>
                                        <h4><img src="assets/img/cal.svg" alt=""><?php echo $date ?></h4>
                                    </div>
                                </div>
                                <div class="hst-dte">
                                    <?php 
                                    if ($n == 9 ) {
                                        echo '<img src="assets/img/crr.svg" alt="">Finalizado';
                                    } elseif ($n == $t) {
                                        echo '<img src="assets/img/crr.svg" alt="">En Proceso';
                                    } else {
                                        echo '<img src="assets/img/chk.svg" alt="">Completado';
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php $n++;
                        endwhile;

                        $sql = "SELECT * FROM Status WHERE id > $t";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()):?>
                            <li>
                                <i><?php echo $n ?></i>
                                <div class="hst-cnt">
                                    <div class="hst-ttl">
                                        <h3><?php echo $row['name'] ?></h3>
                                    </div>
                                </div>
                            </li>
                        <?php $n++;
                        endwhile;
                        ?>
                    </ul>
                </div>
            </div>
            
        </div>
    </section>

    <?php require_once 'includes/common/footer.php'; ?>
    <script>
        if (window.history.replaceState) {
            var cleanURL = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanURL);
        }
    </script>
</body>
</html>
<?php
else:
    header("Location: index.php");
    exit();
endif;
?>