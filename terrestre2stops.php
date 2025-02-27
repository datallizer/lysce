<?php
session_start();
require 'dbcon.php';

// Verificar si hay un mensaje en la sesión
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';

if (!empty($message)) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'NOTIFICACIÓN',
                    text: " . json_encode($message) . ",
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    unset($_SESSION['message']);
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Consultar si el usuario existe
$query = "SELECT * FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2 Stops - Cotizaciones</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/ico.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="sb-nav-fixed">
    <?php include 'sidenav.php'; ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container-fluid">
                <div class="row mt-4 mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-1 text-white">
                                    <button class="btn btn-sm btn-primary float-end" onclick="window.location.href='terrestre2stops-form.php'">
                                        Nueva cotización
                                    </button>
                                    2 Stops - COTIZACIONES
                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y: scroll;">
                                <table id="miTabla" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cliente</th>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Destino Final</th>
                                            <th>Fecha</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT 
                                                    a.*, 
                                                    c.cliente AS cliente_nombre, 
                                                    p_origen.proveedor AS origen_nombre, 
                                                    p_destino.proveedor AS destino_nombre, 
                                                    p_final.proveedor AS final_nombre
                                                  FROM terrestre2stops a
                                                  LEFT JOIN clientes c ON a.idCliente = c.id
                                                  LEFT JOIN proveedores p_origen ON a.idOrigen = p_origen.id
                                                  LEFT JOIN proveedores p_destino ON a.idDestino = p_destino.id
                                                  LEFT JOIN proveedores p_final ON a.idDestinoFinal = p_final.id
                                                  ORDER BY a.id DESC";

                                        $query_run = mysqli_query($con, $query);

                                        if ($query_run && mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($registro['id']); ?></td>
                                                    <td><?= htmlspecialchars($registro['cliente_nombre']); ?></td>
                                                    <td><?= htmlspecialchars($registro['origen_nombre']); ?></td>
                                                    <td><?= htmlspecialchars($registro['destino_nombre']); ?></td>
                                                    <td><?= htmlspecialchars($registro['final_nombre']); ?></td>
                                                    <td><?= htmlspecialchars($registro['fecha']); ?></td>
                                                    <td>
                                                        <a href="generate_terrestre2stops.php?id=<?= $registro['id']; ?>" class="btn btn-primary btn-sm m-1">
                                                            <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                                        </a>
                                                        <form action="codecotizaciones.php" method="POST" class="d-inline">
                                                            <button type="submit" name="delete" value="<?= $registro['id']; ?>" class="btn btn-danger btn-sm m-1">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'><p class='text-center'>No se encontró ningún registro</p></td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
</body>
</html>
