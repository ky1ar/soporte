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

        // Función para obtener los datos desde searchGraph.php
        function fetchData() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const metric = document.getElementById('metric_select').value;

            const requestData = {
                start_date: startDate,
                end_date: endDate,
                metric: metric
            };

            // Llamada AJAX a searchGraph.php
            fetch('routes/searchGraph.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestData),
                })
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                    updateTextResults(data);
                })
                .catch(error => console.error('Error al obtener los datos:', error));
        }

        // Función para actualizar los resultados en los textos (stat1, stat8, etc.)
        function updateTextResults(data) {
            document.getElementById('stat1Result').textContent = data.stat1Count || 0;
            document.getElementById('stat8Result').textContent = data.stat8Count || 0;
            document.getElementById('trainingResult').textContent = data.totalTrainings || 0;
            document.getElementById('trabajo_realizado').textContent = data.trabajo_realizado || 0;
        }

        // Función para actualizar el gráfico con los nuevos datos
        function updateChart(data) {
            // Datos de ejemplo para el gráfico
            const labels = data.map(item => item.name);
            const values = data.map(item => item.value); // Puede ser stat8, totalTrainings o trabajo_realizado

            if (barChart) {
                barChart.destroy();
            }

            const ctx = document.getElementById('barChart').getContext('2d');
            barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Métrica Seleccionada',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
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