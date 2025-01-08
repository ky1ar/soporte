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

    $currentPage = "Reportes";
    require_once 'includes/app/db.php';
    require_once 'includes/app/globals.php';
    require_once 'includes/common/header_admin.php';

    $workers = [];
    $stmt = $conn->prepare("SELECT id, name FROM Users WHERE levels IN (2, 3) AND id != 203");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $workers[] = $row;
        }
    }
    $stmt->close();

    $stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin'];
} else {
    header("Location: krear3dperu");
    exit();
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="ky1-adm">
    <?php
    require_once 'includes/bar/topBar_admin.php';
    require_once 'includes/bar/navigationBar_admin.php';
    ?>
    <div id="graphicsView">
        <h1 class="tp">Reportes - Soporte Técnico</h1>
        <form method="POST">
            <label for="start_date">Fecha de inicio:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Fecha final:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="worker_id">Trabajador:</label>
            <select id="worker_id" name="worker_id">
                <option value="">Todos</option>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['id']; ?>">
                        <?php echo htmlspecialchars($worker['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="fetchData()">Buscar</button>
        </form>

        <div class="txt">
            <p>
                Equipos Ingresados:
                <span id="stat1Result"></span>
            </p>
            <p>
                Equipos Entregados:
                <span id="stat8Result"></span>
            </p>
            <p>
                Capacitaciones Finalizadas:
                <span id="trainingResult"></span>
            </p>
        </div>

        <div class="graf">
            <canvas id="barChart"></canvas>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <script>
        let barChart = null;
        let pieChart = null;

        function setDefaultDates() {
            const today = new Date();
            const startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            const endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            const formattedStartDate = startDate.toISOString().split('T')[0];
            const formattedEndDate = endDate.toISOString().split('T')[0];
            document.getElementById('start_date').value = formattedStartDate;
            document.getElementById('end_date').value = formattedEndDate;
        }

        function fetchData() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const workerId = document.getElementById('worker_id').value;

            document.getElementById('stat1Result').textContent = 'Cargando...';
            document.getElementById('stat8Result').textContent = 'Cargando...';
            document.getElementById('trainingResult').textContent = 'Cargando...';

            const formData = new FormData();
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            if (workerId) {
                formData.append('worker_id', workerId);
            }

            fetch('./routes/searchGraphics.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('stat1Result').textContent = data.stat1Count;
                        document.getElementById('stat8Result').textContent = data.stat8Count;
                        document.getElementById('trainingResult').textContent = data.totalTrainings;
                        updateCharts(data.stat1Count || 0, data.stat8Count || 0, data.totalTrainings || 0);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function createCharts(stat1Count, stat8Count, totalTrainings) {
            const chartData = {
                labels: ['Equipos Ingresados', 'Equipos Entregados', 'Capacitaciones Finalizadas'],
                datasets: [{
                    label: 'Grafico Informativo',
                    data: [stat1Count, stat8Count, totalTrainings],
                    backgroundColor: [
                        'rgb(101, 199, 255)',
                        'rgb(94, 219, 82)',
                        'rgb(255, 89, 48)'
                    ],
                    borderColor: [
                        'rgb(0, 151, 252)',
                        'rgb(6, 187, 0)',
                        'rgb(236, 132, 13)'
                    ],
                    borderWidth: 1
                }]
            };

            const barChartCanvas = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(barChartCanvas, {
                type: 'bar',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            const pieChartCanvas = document.getElementById('pieChart').getContext('2d');
            pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: chartData
            });
        }

        function updateCharts(stat1Count, stat8Count, totalTrainings) {
            if (barChart && pieChart) {
                barChart.data.datasets[0].data = [stat1Count, stat8Count, totalTrainings];
                pieChart.data.datasets[0].data = [stat1Count, stat8Count, totalTrainings];
                barChart.update();
                pieChart.update();
            } else {
                createCharts(stat1Count, stat8Count, totalTrainings);
            }
        }
        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>
</body>

</html>