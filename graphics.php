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

</head>

<body class="ky1-adm">
    <?php
    require_once 'includes/bar/topBar_admin.php';
    require_once 'includes/bar/navigationBar_admin.php';
    ?>
    <div id="graphicsView">
        <form id="dateForm" method="POST">
            <label for="start_date">Fecha de inicio:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Fecha final:</label>
            <input type="date" id="end_date" name="end_date" required>

            <button type="button" onclick="fetchData()">Buscar</button>
        </form>

        <!-- Mostrar los resultados -->
        <div id="result">
            <p>Estadísticas:</p>
            <p>
                Equipos entrantes:
                <span id="stat1Result"></span>
            </p>
            <p>
                Equipos salientes:
                <span id="stat9Result"></span>
            </p>
        </div>
    </div>

    <script>
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
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;

            document.getElementById('stat1Result').textContent = 'Cargando...';
            document.getElementById('stat9Result').textContent = 'Cargando...';

            var formData = new FormData();
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', './routes/searchGraphics.php', true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        var data = JSON.parse(xhr.responseText);
                        document.getElementById('stat1Result').textContent = data.stat1Count || 0; // Manejo de undefined
                        document.getElementById('stat9Result').textContent = data.stat9Count || 0; // Manejo de undefined
                    } catch (error) {
                        document.getElementById('stat1Result').textContent = "Error"; // Indica error en la UI
                        document.getElementById('stat9Result').textContent = "Error"; // Indica error en la UI
                    }
                } else {
                    document.getElementById('stat1Result').textContent = "Error"; // Indica error en la UI
                    document.getElementById('stat9Result').textContent = "Error"; // Indica error en la UI
                }
            };

            xhr.send(formData);
        }

        window.onload = function() {
            setDefaultDates();
            fetchData();
        };
    </script>

</body>

</html>