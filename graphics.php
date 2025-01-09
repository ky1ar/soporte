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
            <select id="metric_select" name="metric_select">
                <option value="">Todos</option>
                <option value="stat8">Equipos Entregados</option>
                <option value="totalTrainings">Capacitaciones Finalizadas</option>
                <option value="trabajo_realizado">Trabajo Realizado</option>
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
            <p>
                Trabajo Realizado:
                <span id="trabajo_realizado"></span>
            </p>
        </div>

        <div class="graf">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <script>
        let barChart = null;

        // Función para actualizar las fechas predeterminadas
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
            const metric = document.getElementById('metric_select').value;

            const requestData = {
                start_date: startDate,
                end_date: endDate,
                metric: metric
            };

            fetch('routes/searchGraph.php', { // Cambia 'path_to_searchGraph.php' por la ruta correcta
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    const labels = data.labels;
                    let chartData = [];

                    if (metric === 'stat8') {
                        chartData = data.data;
                    } else if (metric === 'totalTrainings') {
                        chartData = data.data;
                    } else if (metric === 'trabajo_realizado') {
                        chartData = data.data;
                    } else {
                        // Default case: show all metrics in a grouped way
                        chartData = data.data.map(item => ({
                            stat1: item.stat1,
                            stat8: item.stat8,
                            totalTrainings: item.totalTrainings
                        }));
                    }

                    updateChart(labels, chartData);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function updateChart(labels, data) {
            if (barChart) {
                barChart.destroy();
            }

            barChart = new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Metric',
                        data: data, // Adjusted data format according to the selected metric
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>


</body>

</html>