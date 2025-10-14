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
    <title>Proveedores | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
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
                                <h4 style="color:#fff">PROVEEDOR / AGENTE ADUANAL

                                    <div class="float-end">
                                        <a href="nuevoproveedor.php" class="btn btn-primary btn-sm m-1">
                                            Nuevo proveedor / agente aduanal
                                        </a>
                                        <button type="button" class="btn btn-primary btn-sm m-1" data-bs-toggle="modal" data-bs-target="#asociarModal">
                                            Asociar
                                        </button>
                                    </div>

                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y: scroll;">
                                <table id="miTabla" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Proveedor / Agente aduanal</th>
                                            <th>Domicilio</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th>RFC</th>
                                            <th>Cliente asociado</th>
                                            <th style="width: 55px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM clientes WHERE tipo = 'proveedor' ORDER BY id DESC";

                                        $query_run = mysqli_query($con, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['id']; ?></p>
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
                                                        <?php
                                                        // Consulta para obtener los proveedores asociados a este cliente
                                                        $idProveedor = $registro['id'];
                                                        $proveedores_query = "SELECT p.cliente, pc.id as idasoc 
                                                                                FROM proveedorcliente pc
                                                                                JOIN clientes p ON pc.idCliente = p.id
                                                                                WHERE pc.idProveedor = '$idProveedor'";

                                                        $proveedores_run = mysqli_query($con, $proveedores_query);

                                                        if (mysqli_num_rows($proveedores_run) > 0) {
                                                            while ($proveedor = mysqli_fetch_assoc($proveedores_run)) {
                                                                echo "<div class='d-flex'><p>{$proveedor['cliente']}</p><form action='codeclientes.php' method='post'>
                                                                        <input type='hidden' name='id' value='{$proveedor['idasoc']}'>
                                                                        <input type='hidden' name='ubicacion' value='proveedores'>
                                                                        <button class='btn btn-sm btn-outline-danger' type='submit' name='desasociar'><i class='bi bi-x-lg'></i></button>
                                                        </form></div>";
                                                            }
                                                        } else {
                                                            echo "<p>Sin asociados</p>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#myModal<?= $registro['id']; ?>" data-id="<?= $registro['id']; ?>"><i class="bi bi-eye-fill"></i></button>

                                                        <div class="modal fade" id="myModal<?= $registro['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $registro['id']; ?>" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                                                                <div class="modal-content">
                                                                    <?php
                                                                    $cliente_id = $registro['id'];
                                                                    $query = "SELECT * FROM clientes WHERE id='$cliente_id' ";
                                                                    $query_run = mysqli_query($con, $query);

                                                                    if (mysqli_num_rows($query_run) > 0) {
                                                                        $cliente = mysqli_fetch_array($query_run);
                                                                    ?>
                                                                        <div class="modal-header">
                                                                            <h2 style="text-transform: uppercase;" class="modal-title" id="exampleModalLabel<?= $registro['id']; ?>">INFORMACIÓN DEL PROVEEDOR / AGENTE ADUANAL</h2>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="container-fluid g-0">
                                                                                <div class="row justify-content-center">
                                                                                    <div class="col-12 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['cliente']; ?>" placeholder="Nombre" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">Proveedor / Supplier name</label><br>
                                                                                    </div>

                                                                                    <div class="col-12 col-md-10 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['calle']; ?> <?= $cliente['numexterior']; ?> <?= $cliente['numinterior']; ?>, <?= $cliente['colonia']; ?>, <?= $cliente['city']; ?>, <?= $cliente['state']; ?>, <?= $cliente['pais']; ?>. C.P. <?= $cliente['cpostal']; ?>" placeholder="Calle" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">Domicilio / Address</label><br>
                                                                                    </div>

                                                                                    <div class="col-12 col-md-2 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['telefono']; ?>" placeholder="Nombre" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">Teléfono / Phone</label><br>
                                                                                    </div>

                                                                                    <div class="col-12 col-md-5 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['contacto']; ?>" placeholder="Nombre" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">Contacto / Representant</label><br>
                                                                                    </div>

                                                                                    <div class="col-12 col-md-4 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['correo']; ?>" placeholder="Nombre" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">Correo / Email</label><br>
                                                                                    </div>

                                                                                    <div class="col-12 col-md-3 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['rfc']; ?>" placeholder="Nombre" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">RFC / Tax ID</label><br>
                                                                                    </div>

                                                                                    <div class="col-12 form-floating">
                                                                                        <input type="text" class="form-control" value="<?= $cliente['web']; ?>" placeholder="Nombre" disabled>
                                                                                        <label for="exampleFormControlInput1" class="form-label">Sitio web / Web site</label><br>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                    } else {
                                                                        echo "<h4>No Such Id Found</h4>";
                                                                    }
                                                                        ?>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a href="editarproveedor.php?id=<?= $registro['id']; ?>" class="btn btn-warning btn-sm m-1"><i class="bi bi-pencil-square"></i></a>

                                                        <form action="codeclientes.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="tipo" value="<?= $registro['tipo']; ?>">
                                                            <input type="hidden" name="id" value="<?= $registro['id']; ?>">
                                                            <button type="submit" name="delete" class="btn btn-danger btn-sm m-1"><i class="bi bi-trash-fill"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<td colspan='8'><p>No se encontró ningún registro</p></td>";
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

    <!-- Modal Asociar -->
    <div class="modal fade" id="asociarModal" tabindex="-1" aria-labelledby="asociarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asociarLabel">ASOCIAR CLIENTE - PROVEEDOR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="codeclientes.php" method="post">
                    <div class="modal-body row">

                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" name="idCliente" id="floatingSelect">
                                <option selected>Selecciona un cliente para asociar</option>
                                <?php
                                $query = "SELECT * FROM clientes WHERE estatus = 1 AND tipo = 'Cliente' ORDER BY cliente ASC";
                                $result = mysqli_query($con, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($registro = mysqli_fetch_assoc($result)) {
                                        $nombre = $registro['cliente'];
                                        $contacto = $registro['contacto'];
                                        $idOrigen = $registro['id'];
                                        echo "<option value='$idOrigen'>" . $nombre . ' - ' . $contacto . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="floatingSelect">Cliente</label>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <select class="form-select" name="idProveedor" id="floatingSelect">
                                <option selected>Selecciona un proveedor para asociar</option>
                                <?php
                                $query = "SELECT * FROM clientes WHERE estatus = 1 AND tipo = 'Proveedor' ORDER BY cliente ASC";
                                $result = mysqli_query($con, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($registro = mysqli_fetch_assoc($result)) {
                                        $nombre = $registro['cliente'];
                                        $contacto = $registro['contacto'];
                                        $idOrigen = $registro['id'];
                                        echo "<option value='$idOrigen'>" . $nombre . ' - ' . $contacto . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="floatingSelect">Proveedor</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="asociar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script>
        $(document).ready(function() {
            $('#miTabla').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 25
            });
        });
    </script>
</body>

</html>