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
            // Obtener los valores de las fechas y la métrica seleccionada
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const metric = document.getElementById('metric_select').value;

            // Validar que las fechas sean válidas
            if (!startDate || !endDate) {
                alert('Por favor, ingresa las fechas de inicio y fin.');
                return;
            }

            // Crear un objeto FormData para enviar los parámetros por POST
            const formData = new FormData();
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('metric', metric);

            // Enviar la solicitud AJAX usando fetch
            fetch('routes/searchGraph.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Verificar si hay un error en los datos
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }

                    // Procesar los datos y prepararlos para el gráfico
                    const labels = []; // Etiquetas (por ejemplo, nombres de usuario)
                    const values = []; // Valores correspondientes a cada usuario

                    data.forEach(item => {
                        labels.push(item.name); // Nombre del usuario
                        values.push(item.valor !== undefined ? item.valor : 0); // Asegúrate de agregar 0 si el valor es undefined
                    });

                    // Obtener el contexto del canvas
                    const ctx = document.getElementById('barChart').getContext('2d');

                    if (!ctx) {
                        console.error("El contexto del lienzo no es válido.");
                        return;
                    }

                    // Si existe un gráfico previo, destruirlo
                    if (barChart) {
                        barChart.destroy();
                    }

                    // Crear un nuevo gráfico
                    barChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: metric,
                                data: values,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
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