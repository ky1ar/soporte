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
                        </form>
                    </div>

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
                        // Obtener filtros
                        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
                        $filterState = isset($_GET['filterState']) ? $_GET['filterState'] : '';
                        $filterClient = isset($_GET['filterClient']) ? $_GET['filterClient'] : '';

                        // Construir consulta base
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

                        // Agregar condiciones según los filtros
                        if (!empty($startDate) && !empty($endDate)) {
                            $sql .= " AND training_date BETWEEN '$startDate' AND '$endDate'";
                        } elseif (!empty($startDate)) {
                            $sql .= " AND training_date >= '$startDate'";
                        } elseif (!empty($endDate)) {
                            $sql .= " AND training_date <= '$endDate'";
                        }

                        if (!empty($filterState)) {
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
                                        <img
                                            width="48"
                                            class="tbl-img"
                                            src="assets/mac/<?php echo $row['machine_slug'] ?>.webp" alt="">
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
                                    <td class="row-act"><img class="tbl-tec" src="assets/img/dot.svg" alt=""></td>
                                </tr>
                        <?php
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