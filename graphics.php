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

    $currentPage = "Graficos";
    require_once 'includes/app/db.php';
    require_once 'includes/app/globals.php';
    require_once 'includes/common/header_admin.php';
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
        <form method="POST">
            <label for="start_date">Fecha de inicio:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Fecha final:</label>
            <input type="date" id="end_date" name="end_date" required>

            <button type="button" onclick="fetchData()">Buscar</button>
        </form>
        <div class="txt">
            <p class="ttl">Estadísticas</p>
            <p>
                Equipos entrantes:
                <span id="stat1Result"></span>
            </p>
            <p>
                Equipos salientes:
                <span id="stat9Result"></span>
            </p>
        </div>
        <div>
            <canvas id="barChart"></canvas>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <script>
        let barChart = null;
        let pieChart = null;

        function setDefaultDates() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = (today.getMonth() + 1).toString().padStart(2, '0');
            const dd = today.getDate().toString().padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;
            document.getElementById('start_date').value = formattedDate;
            document.getElementById('end_date').value = formattedDate;
        }

        function fetchData() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            document.getElementById('stat1Result').textContent = 'Cargando...';
            document.getElementById('stat9Result').textContent = 'Cargando...';
            const formData = new FormData();
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);

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
                        document.getElementById('stat9Result').textContent = data.stat9Count;
                        updateCharts(data.stat1Count || 0, data.stat9Count || 0);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function createCharts(stat1Count, stat9Count) {
            const chartData = {
                labels: ['Equipos Ingresados', 'Equipos Entregados'],
                datasets: [{
                    label: 'Grafico de Equipos',
                    data: [stat1Count, stat9Count],
                    backgroundColor: [
                        'rgb(92, 190, 255)',
                        'rgb(231, 78, 78)'
                    ],
                    borderColor: [
                        'rgb(0, 100, 167)',
                        'rgb(184, 0, 40)'
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

            // Gráfico de torta
            const pieChartCanvas = document.getElementById('pieChart').getContext('2d');
            pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: chartData
            });
        }

        function updateCharts(stat1Count, stat9Count) {
            if (barChart && pieChart) {
                barChart.data.datasets[0].data = [stat1Count, stat9Count];
                pieChart.data.datasets[0].data = [stat1Count, stat9Count];
                barChart.update();
                pieChart.update();
            } else {
                createCharts(stat1Count, stat9Count);
            }
        }
        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>

</body>

</html>