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
                                <tr>
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
        </div>
    </section>
    <script type="text/javascript" src="assets/js/test.js?v=<?php echo $GLOBALS['ver']; ?>"></script>
</body>

</html>