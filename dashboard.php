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

$query = "SELECT COUNT(*) AS total FROM ftl";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
$totalFTL = $row['total'];

$query_aereo_impo = "SELECT COUNT(*) AS totalaereoimpo FROM aereoimportacion";
$result_aereo_impo = mysqli_query($con, $query_aereo_impo);
$row_aereo_impo = mysqli_fetch_assoc($result_aereo_impo);
$total_aereo_impo = $row_aereo_impo['totalaereoimpo'];
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
                    </div>
                    <div class="col-12 col-md-2 text-center">
                        <a href="ftl.php" style="text-decoration: none;">
                            <div class="card border-dark mb-3">
                                <div class="card-header bg-dark">
                                    <p style="color: #ffffff;">FTL</p>
                                </div>
                                <div class="card-body text-dark">
                                    <h3 class="card-title"><?php echo $totalFTL; ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-2 text-center">
                        <a href="ltl.php" style="text-decoration: none;">
                            <div class="card border-dark mb-3">
                                <div class="card-header bg-dark">
                                    <p style="color: #ffffff;">LTL</p>
                                </div>
                                <div class="card-body text-dark">
                                    <h3 class="card-title"><?php echo $totalFTL; ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-2 text-center">
                        <a href="aereo-importacion.php" style="text-decoration: none;">
                            <div class="card border-dark mb-3">
                                <div class="card-header bg-dark">
                                    <p style="color: #ffffff;">AÉREO IMPO</p>
                                </div>
                                <div class="card-body text-dark">
                                    <h3 class="card-title"><?php echo $total_aereo_impo; ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-2 text-center">
                        <a href="aereo-exportacion.php" style="text-decoration: none;">
                            <div class="card border-dark mb-3">
                                <div class="card-header bg-dark">
                                    <p style="color: #ffffff;">AÉREO EXPO</p>
                                </div>
                                <div class="card-body text-dark">
                                    <h3 class="card-title"><?php echo $totalFTL; ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-2 text-center">
                        <a href="lcl.php" style="text-decoration: none;">
                            <div class="card border-dark mb-3">
                                <div class="card-header bg-dark">
                                    <p style="color: #ffffff;">LCL</p>
                                </div>
                                <div class="card-body text-dark">
                                    <h3 class="card-title"><?php echo $totalFTL; ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-2 text-center">
                        <a href="fcl.php" style="text-decoration: none;">
                            <div class="card border-dark mb-3">
                                <div class="card-header bg-dark">
                                    <p style="color: #ffffff;">FCL</p>
                                </div>
                                <div class="card-body text-dark">
                                    <h3 class="card-title"><?php echo $totalFTL; ?></h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-4">
                        <canvas id="graficaPie" width="400" height="400"></canvas>
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
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('graficaPie').getContext('2d');
            var graficaPie = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['FTL', 'Aéreo Importación'],
                    datasets: [{
                        data: [<?php echo $totalFTL; ?>, <?php echo $total_aereo_impo; ?>],
                        backgroundColor: ['#4287f5', '#f56c42'], // Colores de la gráfica
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>