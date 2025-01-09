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
            <label for="worker_id">Técnico:</label>
            <select id="worker_id" name="worker_id">
                <option value="">General</option>
                <?php foreach ($workers as $worker): ?>
                    <option value="<?php echo $worker['id']; ?>">
                        <?php echo htmlspecialchars($worker['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="metric_select">Categoría:</label>
            <select id="metric_select" name="metric_select">
                <option value="">General</option>
                <option value="stat8">Equipos Reparados</option>
                <option value="totalTrainings">Capacitaciones Realizadas</option>
                <option value="trabajo_realizado">Total de Actividad</option>
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
                    const colors = [];

                    data.forEach(item => {
                        labels.push(item.name);
                        values.push(item.valor !== undefined ? item.valor : 0);
                        const randomColor = `rgb(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)})`;
                        colors.push(randomColor);
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
                            chartTitle = "Equipos Reparados";
                            break;
                        case 'totalTrainings':
                            chartTitle = "Capacitaciones Realizadas";
                            break;
                        case 'trabajo_realizado':
                            chartTitle = "Total de Actividad (Equipos Reparados + Capacitaciones Realizadas)";
                            break;
                        default:
                            chartTitle = "Reporte General";
                            break;
                    }

                    //crear
                    barChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colors,
                                borderColor: colors,
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
                                    font: {
                                        size: 16,
                                        weight: 'bold'
                                    },
                                    padding: {
                                        bottom: 50
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