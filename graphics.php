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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

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

            <label for="metric_select">Categoría:</label>
            <select id="metric_select" name="metric_select">
                <option value="stat8">Equipos Entregados</option>
                <option value="totalTrainings">Capacitaciones Finalizadas</option>
                <option value="trabajo_realizado">Trabajo Realizado</option>
            </select>

            <button type="button" onclick="fetchData()">Buscar</button>
        </form>

        <div class="graf">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <script>
        let barChart = null;

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
            if (!startDate || !endDate) {
                alert('Por favor, ingresa las fechas de inicio y fin.');
                return;
            }
            const formData = new FormData();
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('metric', metric);

            fetch('routes/searchGraph.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }
                    const labels = [];
                    const values = [];

                    data.forEach(item => {
                        labels.push(item.name);
                        values.push(item.valor !== undefined ? item.valor : 0);
                    });
                    const ctx = document.getElementById('barChart').getContext('2d');
                    if (!ctx) {
                        console.error("El contexto del lienzo no es válido.");
                        return;
                    }
                    //destruir
                    if (barChart) {
                        barChart.destroy();
                    }

                    // Asignar título
                    let chartTitle = "";
                    switch (metric) {
                        case 'stat8':
                            chartTitle = "Equipos Entregados";
                            break;
                        case 'totalTrainings':
                            chartTitle = "Capacitaciones Finalizadas";
                            break;
                        case 'trabajo_realizado':
                            chartTitle = "Trabajo Realizado (Equipos Entregados + Capacitaciones)";
                            break;
                        default:
                            chartTitle = "Gráfico de Métrica";
                            break;
                    }

                    //crear
                    barChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: 'rgba(69, 221, 132, 0.83)',
                                borderColor: 'rgb(0, 211, 53)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: chartTitle,
                                    padding: {
                                        bottom: 20
                                    }
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    formatter: (value) => value,
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    color: '#000'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al obtener los datos. Intenta nuevamente.');
                });
        }

        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>


</body>

</html>