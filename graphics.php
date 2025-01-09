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
            // Obtener los valores de las fechas y la métrica seleccionada
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const metric = document.getElementById('metric_select').value;

            // Crear un objeto con los datos para enviar al servidor
            const requestData = {
                start_date: startDate,
                end_date: endDate,
                metric: metric
            };

            // Realizar la solicitud POST al servidor
            fetch('./routes/searchGraph.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json()) // Obtener la respuesta en formato JSON
                .then(data => {
                    const labels = data.labels; // Obtener las etiquetas (nombres de trabajadores)
                    let chartData = [];

                    // Según la métrica seleccionada, asignar los datos correspondientes
                    if (metric === 'stat8') {
                        chartData = data.data; // Para la métrica 'stat8', usar los datos directamente
                    } else if (metric === 'totalTrainings') {
                        chartData = data.data; // Para la métrica 'totalTrainings', usar los datos directamente
                    } else if (metric === 'trabajo_realizado') {
                        chartData = data.data; // Para la métrica 'trabajo_realizado', usar los datos directamente
                    } else {
                        // Si no se selecciona una métrica específica, usar todos los datos en un formato agrupado
                        chartData = data.data.map(item => ({
                            stat1: item.stat1,
                            stat8: item.stat8,
                            totalTrainings: item.totalTrainings
                        }));
                    }

                    // Actualizar el gráfico con los datos obtenidos
                    updateChart(labels, chartData);
                })
                .catch(error => console.error('Error fetching data:', error)); // Manejar errores
        }

        function updateChart(labels, data) {
            // Si ya existe un gráfico, destruirlo antes de crear uno nuevo
            if (barChart) {
                barChart.destroy();
            }

            // Crear un nuevo gráfico de barras usando los datos
            barChart = new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: labels, // Las etiquetas (nombres de trabajadores)
                    datasets: [{
                        label: 'Metric', // Etiqueta del gráfico
                        data: data, // Datos obtenidos
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo de las barras
                        borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true, // Hacer el gráfico responsive
                    scales: {
                        y: {
                            beginAtZero: true // Iniciar el eje Y en cero
                        }
                    }
                }
            });
        }

        // Llamar a fetchData() cuando se actualicen las fechas o la métrica
        document.getElementById('start_date').addEventListener('change', fetchData);
        document.getElementById('end_date').addEventListener('change', fetchData);
        document.getElementById('metric_select').addEventListener('change', fetchData);


        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>


</body>

</html>