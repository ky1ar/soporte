<?php
session_start();
$inactiveTime = 3600;
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > $inactiveTime) {
            session_unset();
            session_destroy();
            header("Location: krear3dperu");
            exit();
        }
    }
    $_SESSION['last_activity'] = time();

    $s_id = $_SESSION['user_id'];
    $s_levels = $_SESSION['user_levels'];
    $s_name = $_SESSION['user_name'];
    $s_nick = $_SESSION['user_nick'];
    $s_role = $_SESSION['user_role'];

    $currentPage = "H. de Capacitaciones";
    require_once 'includes/app/db.php';
    require_once 'includes/app/globals.php';
    require_once 'includes/common/header_admin.php';
    $stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin'];
} else {
    header("Location: krear3dperu");
    exit();
}
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
                        <h3>Historial de Capacitaciones
                            <p>Listado de usuarios y solicitudes de capacitación</p>
                        </h3>
                    </div>
                    <div class="filtros">
                        <form id="filterForm" method="GET">
                            <label for="startDate">Fecha Inicial:</label>
                            <input type="date" id="startDate" name="startDate" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] : ''; ?>">

                            <label for="endDate">Fecha Final:</label>
                            <input type="date" id="endDate" name="endDate" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] : ''; ?>">

                            <label for="filterState">Estado:</label>
                            <select id="filterState" name="filterState">
                                <option value="">Todos</option>
                                <?php
                                $stateQuery = "SELECT id, name FROM State";
                                $stateResult = $conn->query($stateQuery);
                                if ($stateResult->num_rows > 0) {
                                    while ($state = $stateResult->fetch_assoc()) {
                                        $selected = isset($_GET['filterState']) && $_GET['filterState'] == $state['id'] ? 'selected' : '';
                                        echo "<option value='{$state['id']}' $selected>{$state['name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="filterClient">Cliente:</label>
                            <input type="text" id="filterClient" name="filterClient" value="<?php echo isset($_GET['filterClient']) ? $_GET['filterClient'] : ''; ?>" placeholder="Nombre del cliente">

                            <button type="submit">Filtrar</button>
                            <button type="button" id="clearFilters">Limpiar Filtros</button>
                        </form>

                    </div>

                    <script>
                        document.getElementById('clearFilters').addEventListener('click', function() {
                            document.getElementById('startDate').value = '';
                            document.getElementById('endDate').value = '';
                            document.getElementById('filterState').selectedIndex = 0;
                            document.getElementById('filterClient').value = '';
                            window.location.href = window.location.pathname;
                        });
                    </script>

                    <table aria-describedby="Training Report" class="rpt-tbl" border="0">
                        <tr class="row-hdr">
                            <th>#</th>
                            <th>Equipo</th>
                            <th>Técnico</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        <?php
                        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
                        $filterState = isset($_GET['filterState']) ? $_GET['filterState'] : '';
                        $filterClient = isset($_GET['filterClient']) ? $_GET['filterClient'] : '';

                        $sql = "SELECT
                            t.id as training_id,
                            m.slug as machine_slug,
                            b.name as brand_name,
                            m.model as machine_model,
                            w.name as worker_name,
                            t.name as client_name,
                            training_date,
                            training_start,
                            s.name as state_name,
                            s.id as state_id
                            FROM Training t
                            INNER JOIN Machine m ON t.machine = m.id
                            INNER JOIN Brand b ON m.brand = b.id
                            LEFT JOIN Users w ON t.worker = w.id
                            INNER JOIN State s ON t.training_state = s.id
                            WHERE 1=1";

                        if (!empty($startDate) && !empty($endDate)) {
                            $startDate .= " 00:00:00";
                            $endDate .= " 23:59:59";
                            $sql .= " AND training_date BETWEEN '$startDate' AND '$endDate'";
                        } elseif (!empty($startDate)) {
                            $startDate .= " 00:00:00";
                            $sql .= " AND training_date >= '$startDate'";
                        } elseif (!empty($endDate)) {
                            $endDate .= " 23:59:59";
                            $sql .= " AND training_date <= '$endDate'";
                        }

                        if ($filterState !== '') {
                            $sql .= " AND s.id = '$filterState'";
                        }
                        if (!empty($filterClient)) {
                            $sql .= " AND t.name LIKE '%$filterClient%'";
                        }
                        $sql .= " ORDER BY t.id DESC";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                                setlocale(LC_TIME, 'es_ES');
                                $date = strtotime($row['training_date']);
                                $date = strftime("%e de %B de %Y", $date);
                        ?>
                                <tr>
                                    <td><?php echo $row['training_id'] ?></td>
                                    <td class="row-odr">
                                        <img width="48" class="tbl-img" src="assets/mac/<?php echo $row['machine_slug'] ?>.webp" alt="">
                                        <div class="tbl-odr">
                                            <?php echo $row['brand_name'] ?><span><?php echo $row['machine_model'] ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $row['worker_name'] ? $row['worker_name'] : 'No aplica' ?></td>
                                    <td><?php echo $row['client_name'] ?></td>
                                    <td><?php echo $date ?></td>
                                    <td><?php echo substr($row['training_start'], 0, -3) ?></td>
                                    <td class="row-tst">
                                        <span class="st<?php echo $row['state_id'] ?>">
                                            <?php echo $row['state_name'] ?>
                                        </span>
                                    </td>
                                    <td class="row-act"><img class="tbl-tec" src="assets/img/mas-info.webp" alt=""></td>
                                </tr>
                        <?php
                            endwhile;
                        endif;
                        ?>
                    </table>
                    <span id="totalRows" data-total="<?php echo $result->num_rows; ?>"></span>
                    <div class="pagination" id="pagination"></div>
                    <p class="filas">Resultados: <?php echo isset($result) ? $result->num_rows : 0; ?> fila(s)</p>

                </div>
            </div>
        </div>
    </section>
    <div class="modalBox">
        <div id="viewTraining">
            <div class="header">
                <div class="block">
                    <h2 class="date">Miércoles 22 de enero</h2>
                    <div class="box">
                        <span class="schedule">09:30</span>
                        <div class="admin" style="display: none;">Administrador</div>
                    </div>
                </div>
                <div class="content">
                    <div class="staticWorker">
                        <img src="assets/img/worker.svg" alt="">
                        <span class="worker"></span>
                    </div>
                    <div class="sect-pruebas" id="sectpruebas">
                        <div class="pruebas">
                            <div>
                                <label for="comevi">Comentarios:</label>
                                <textarea type="text" id="comevi" name="comevi"></textarea>
                            </div>
                            <div class="archi">
                                <div>
                                    <label for="imgevi">Archivo:</label>
                                    <input type="file" id="imgevi" name="imgevi" accept="image/*, .pdf">

                                </div>
                                <div class="evi" id="viewEvidencias"><img width="12" height="12" src="assets/img/invoice.svg" alt="">Sin Archivos</div>
                            </div>
                            <button type="submit" id="upd_evi">
                                Guardar
                            </button>
                        </div>
                        <div class="result">
                            <p></p>
                            <img src="" alt="">
                        </div>
                    </div>
                    <div class="staticMeet" style="display: none;">
                        <img src="assets/img/meet.svg" alt="">
                        <a class="meet" href="https://meet.google.com/tsw-qnsk-win" target="_blank">https://meet.google.com/tsw-qnsk-win</a>
                    </div>
                    <div class="editableMeet" style="display: none;">
                        <a href="https://meet.google.com/" target="_blank"><img src="assets/img/meet.svg" alt=""></a>
                        <input class="upd_meet" id="meetLink" type="text" placeholder="Ingrese el link de Google Meet">
                        <div id="actionMessage"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="left">
                    <div class="title">
                        <h2 class="model">A1</h2>
                        <h2 class="count">(Ya tiene 1 capacitación)</h2>
                    </div>
                    <h3 class="name">PEDRO NICOLAS CHAVEZ PRADO</h3>
                    <h3 class="document">09140833</h3>
                    <div class="invoice" id="viewInvoice" data-src="../uploads/invoices/678bc58a7b610_1737213322.pdf">
                        <img width="12" height="12" src="assets/img/invoice.svg" alt="">Comprobante
                    </div>
                    <div class="link">
                        <p class="email">pedronotas53@gmail.com</p>
                        <div class="flex">
                            <img src="assets/img/wsp2.svg" alt="">
                            <a class="phone" href="https://api.whatsapp.com/send?phone=51997618358" target="_blank" rel="nofollow">+51997618358</a>
                        </div>

                    </div>
                </div>
                <img class="image" src="assets/mac/a1.webp" alt="">
            </div>
            <img class="modalClose" src="assets/img/x.svg" alt="">
        </div>
    </div>
    <script type="text/javascript" src="assets/js/test.js?v=<?php echo $GLOBALS['ver']; ?>"></script>
</body>

</html>