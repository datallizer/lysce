<?php
session_start();
require 'dbcon.php';

$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;

if (!empty($alert)) {
    $title = isset($alert['title']) ? json_encode($alert['title']) : '"Notificación"';
    $message = isset($alert['message']) ? json_encode($alert['message']) : '""';
    $icon = isset($alert['icon']) ? json_encode($alert['icon']) : '"info"';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: $title,
                    " . (!empty($alert['message']) ? "text: $message," : "") . "
                    icon: $icon,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hacer algo si se confirma la alerta
                    }
                });
            });
        </script>";
    unset($_SESSION['alert']);
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
    } else {
        $_SESSION['alert'] = [
            'title' => 'USUARIO NO ENCONTRADO',
            'icon' => 'ERROR'
        ];
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['alert'] = [
        'message' => 'Para acceder debes iniciar sesión primero',
        'title' => 'SESIÓN NO INICIADA',
        'icon' => 'info'
    ];
    header('Location: login.php');
    exit();
}

// Crear los 12 meses del año actual
$meses = [];
for ($i = 1; $i <= 12; $i++) {
    $meses[] = date('Y') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT); // Ej: 2025-01
}

// Inicializar estructura con 0
$data = [];
foreach ($meses as $mes) {
    $data[$mes] = ['ftl' => 0, 'ltl' => 0, 'aereo' => 0, 'aereoexpo' => 0, 'lcl' => 0, 'fcl' => 0];
}

// Consulta para obtener los conteos por mes y tipo
$sql = "
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total, 'ftl' AS tipo FROM ftl WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY mes
    UNION ALL
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total, 'ltl' AS tipo FROM ltl WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY mes
    UNION ALL
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total, 'aereo' AS tipo FROM aereo WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY mes
    UNION ALL
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total, 'aereoexpo' AS tipo FROM aereoexpo WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY mes
    UNION ALL
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total, 'lcl' AS tipo FROM lcl WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY mes
    UNION ALL
    SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(*) AS total, 'fcl' AS tipo FROM fcl WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY mes
";


$resultado = $con->query($sql);

// Llenar los datos reales
while ($row = $resultado->fetch_assoc()) {
    $mes = $row['mes'];
    $tipo = $row['tipo'];
    $total = (int)$row['total'];

    if (isset($data[$mes])) {
        $data[$mes][$tipo] = $total;
    }
}

// Preparar para Chart.js
$labels = array_keys($data);
$ftl = [];
$ltl = [];
$aereo = [];
$lcl = [];
$fcl = [];

foreach ($data as $mes => $valores) {
    $ftl[] = $valores['ftl'];
    $ltl[] = $valores['ltl'];
    $aereo[] = $valores['aereo'];
    $aereoexpo[] = $valores['aereoexpo'];
    $lcl[] = $valores['lcl'];
    $fcl[] = $valores['fcl'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico">
    <title>Dashboard | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="sb-nav-fixed">
    <?php include 'sidenav.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container-fluid">
                <div class="row mt-4 mb-5 justify-content-center align-items-center">
                    <div class="col-12 mb-3">
                        <h3>DASHBOARD</h3>
                        <p class="small" style="color:rgb(180, 180, 180);">Cotizaciones por mes</p>
                    </div>
                    <div class="col-12 col-md-10">
                        <canvas style="min-height: 450px;" id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('lineChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                        label: 'FTL',
                        data: <?= json_encode($ftl) ?>,
                        borderColor: 'rgb(236, 117, 117)',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointBackgroundColor: 'rgba(236, 117, 117, 0.5)',
                        pointBorderColor: 'rgb(236, 117, 117)',
                        pointHoverRadius: 13,
                    },
                    {
                        label: 'LTL',
                        data: <?= json_encode($ltl) ?>,
                        borderColor: 'rgb(44, 129, 185)',
                        backgroundColor: 'rgba(17, 40, 56, 0.1)',
                        fill: true,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointBackgroundColor: 'rgba(44, 129, 185, 0.5)',
                        pointBorderColor: 'rgb(44, 129, 185)',
                        pointHoverRadius: 13,
                    },
                    {
                        label: 'Aéreo Importación',
                        data: <?= json_encode($aereo) ?>,
                        borderColor: 'rgb(83, 170, 116)',
                        backgroundColor: 'rgba(83, 170, 116, 0.1)',
                        fill: true,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointBackgroundColor: 'rgba(83, 170, 116, 0.5)',
                        pointBorderColor: 'rgb(83, 170, 116)',
                        pointHoverRadius: 13,
                    },
                    {
                        label: 'Aéreo Exportación',
                        data: <?= json_encode($aereoexpo) ?>,
                        borderColor: 'rgb(255, 193, 7)',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        fill: true,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointBackgroundColor: 'rgba(255, 193, 7, 0.5)',
                        pointBorderColor: 'rgb(255, 193, 7)',
                        pointHoverRadius: 13,
                    },
                    {
                        label: 'Marítimo LCL',
                        data: <?= json_encode($lcl) ?>,
                        borderColor: 'rgb(176, 48, 183)',
                        backgroundColor: 'rgba(176, 48, 183, 0.1)',
                        fill: true,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointBackgroundColor: 'rgba(176, 48, 183, 0.5)',
                        pointBorderColor: 'rgb(176, 48, 183)',
                        pointHoverRadius: 13,
                    },
                    {
                        label: 'Marítimo FCL',
                        data: <?= json_encode($fcl) ?>,
                        borderColor: 'rgb(94, 94, 94)',
                        backgroundColor: 'rgba(94, 94, 94, 0.1)',
                        fill: true,
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointBackgroundColor: 'rgba(94, 94, 94, 0.5)',
                        pointBorderColor: 'rgb(94, 94, 94)',
                        pointHoverRadius: 13,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>