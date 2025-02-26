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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico">
    <title>Clientes | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                                <h4 style="color:#fff" class="m-1">
                                    <a href="nuevocliente.php" class="btn btn-primary btn-sm float-end btn-sm mb-1">
                                        Nuevo cliente
                                    </a>
                                    CLIENTES
                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y:scroll;">
                                <table id="miTabla" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cliente</th>
                                            <th>Direccion</th>
                                            <th>Telefono</th>
                                            <th>Correo</th>
                                            <th>RFC</th>
                                            <th>Proveedor asociado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $query = "SELECT 
            c.id AS idCliente, 
            c.cliente, 
            c.calle, 
            c.colonia, 
            c.city, 
            c.telefono, 
            c.correo, 
            c.rfc, 
            GROUP_CONCAT(p.proveedor SEPARATOR ', ') AS proveedores_asociados
          FROM clientes c
          LEFT JOIN proveedorcliente pc ON c.id = pc.idCliente
          LEFT JOIN proveedores p ON pc.idProveedor = p.id
          GROUP BY c.id
          ORDER BY c.id DESC";

                                        $query_run = mysqli_query($con, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['idCliente']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['cliente']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['calle']; ?>, <?= $registro['colonia']; ?>, <?= $registro['city']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['telefono']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['correo']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['rfc']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= !empty($registro['proveedores_asociados']) ? $registro['proveedores_asociados'] : 'Sin proveedores asociados'; ?></p>
                                                    </td>
                                                    <td>
                                                        <a href="editarcliente.php?id=<?= $registro['idCliente']; ?>" class="btn btn-warning btn-sm m-1"><i class="bi bi-pencil-square"></i></a>

                                                        <form action="codeclientes.php" method="POST" class="d-inline">
                                                            <button type="submit" name="delete" value="<?= $registro['idCliente']; ?>" class="btn btn-danger btn-sm m-1"><i class="bi bi-trash-fill"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<td colspan='9'><p>No se encontró ningún registro</p></td>";
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script src="js/js.js"></script>
    <script>
        document.getElementById('clienteForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario de manera tradicional

            const form = event.target;
            const formData = new FormData(form);

            fetch('codeclientes.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) // O .json() si esperas una respuesta JSON
                .then(data => {
                    console.log(data); // Procesa la respuesta aquí
                    alert('Formulario enviado con éxito');
                    // Aquí podrías actualizar el DOM según la respuesta
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al enviar el formulario');
                });
        });
    </script>
</body>

</html>