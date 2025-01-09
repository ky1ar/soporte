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

        // Función para hacer la solicitud POST
        function fetchData() {
            // Obtener las fechas y la métrica seleccionada
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const metric = document.getElementById('metric').value;

            // Validar que las fechas estén seleccionadas
            if (!startDate || !endDate) {
                alert("Por favor, seleccione las fechas.");
                return;
            }

            // Crear el objeto de datos a enviar
            const requestData = {
                start_date: startDate,
                end_date: endDate,
                metric: metric // Se añade la métrica seleccionada
            };

            // Realizar la solicitud POST usando Fetch API
            fetch('./routes/searchGraph.php', { // Cambia 'ruta-a-tu-servidor.php' por la ruta correcta del archivo PHP
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Establecer el tipo de contenido como JSON
                    },
                    body: JSON.stringify(requestData), // Convertir los datos a JSON
                })
                .then(response => response.json())
                .then(data => {
                    // Verificar si la respuesta contiene los resultados esperados
                    if (data.error) {
                        alert('Error: ' + data.error);
                        return;
                    }

                    // Mostrar los resultados según la métrica seleccionada
                    if (metric === 'stat8') {
                        displayStat8Results(data);
                    } else if (metric === 'totalTrainings') {
                        displayTotalTrainingsResults(data);
                    } else if (metric === 'trabajo_realizado') {
                        displayTrabajoRealizadoResults(data);
                    } else {
                        displayDefaultResults(data);
                    }
                })
                .catch(error => {
                    alert('Ocurrió un error: ' + error);
                });
        }

        // Función para mostrar los resultados de 'stat8'
        function displayStat8Results(data) {
            // Mostrar los resultados de 'stat8'
            let output = '';
            data.forEach(item => {
                output += `<p>${item.name}: ${item.stat8} registros con stat 8</p>`;
            });
            document.getElementById('results').innerHTML = output;
        }

        // Función para mostrar los resultados de 'totalTrainings'
        function displayTotalTrainingsResults(data) {
            // Mostrar los resultados de 'totalTrainings'
            let output = '';
            data.forEach(item => {
                output += `<p>${item.name}: ${item.totalTrainings} capacitaciones finalizadas</p>`;
            });
            document.getElementById('results').innerHTML = output;
        }

        // Función para mostrar los resultados de 'trabajo_realizado'
        function displayTrabajoRealizadoResults(data) {
            // Mostrar los resultados de 'trabajo_realizado'
            let output = '';
            data.forEach(item => {
                output += `<p>${item.name}: ${item.trabajo_realizado} trabajo realizado</p>`;
            });
            document.getElementById('results').innerHTML = output;
        }

        // Función para mostrar los resultados por defecto
        function displayDefaultResults(data) {
            // Mostrar los resultados de la consulta por defecto
            const results = data[0]; // Solo hay un conjunto de resultados
            document.getElementById('stat1Count').textContent = results.stat1Count || 0;
            document.getElementById('stat8Count').textContent = results.stat8Count || 0;
            document.getElementById('totalTrainings').textContent = results.totalTrainings || 0;

            // Aquí puedes agregar código para mostrar un gráfico de barras, si lo deseas
            // Ejemplo con Chart.js:
            const ctx = document.getElementById('barChart').getContext('2d');
            const barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Equipos Ingresados', 'Equipos Entregados', 'Capacitaciones Finalizadas'],
                    datasets: [{
                        label: 'Estadísticas',
                        data: [results.stat1Count || 0, results.stat8Count || 0, results.totalTrainings || 0],
                        backgroundColor: ['#ffcc00', '#66cc33', '#3399ff'],
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        }


        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>

</body>

</html>