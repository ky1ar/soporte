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
            <p id="stat1Result">Estadísticas con stat 1: 0</p>
            <p id="stat9Result">Estadísticas con stat 9: 0</p>
        </div>
    </div>

    <script>
        function setDefaultDates() {
            const today = new Date();
            const yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // El mes comienza desde 0, por lo que sumamos 1
            let dd = today.getDate();

            if (mm < 10) {
                mm = '0' + mm; // Aseguramos que el mes tenga dos dígitos
            }
            if (dd < 10) {
                dd = '0' + dd; // Aseguramos que el día tenga dos dígitos
            }

            const formattedDate = `${yyyy}-${mm}-${dd}`; // Formato yyyy-mm-dd

            // Establecer la fecha de hoy en los campos de entrada
            document.getElementById('start_date').value = formattedDate;
            document.getElementById('end_date').value = formattedDate;
        }

        function fetchData() {
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;

            var formData = new FormData();
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
                        document.getElementById('stat1Result').textContent = 'Estadísticas con stat 1: ' + data.stat1Count;
                        document.getElementById('stat9Result').textContent = 'Estadísticas con stat 9: ' + data.stat9Count;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        window.onload = function() {
            setDefaultDates();
        };
    </script>


</body>

</html>