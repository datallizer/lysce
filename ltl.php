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
    <title>Cotizaciones LTL | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
    .spinner-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }

    .spinner-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner {
        width: 3rem;
        height: 3rem;
    }
</style>

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
                                    <a class="btn btn-sm btn-primary float-end" href="form-ltl.php">Nueva cotización</a>
                                    LTL - COTIZACIONES
                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y:scroll;">
                                <table id="miTabla" class="table table-bordered table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cliente</th>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Destino final</th>
                                            <th>Fecha</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT 
                                                    a.*,
                                                    c.cliente AS cliente_nombre,
                                                    p_origen.cliente AS origen_nombre,
                                                    p_destino.cliente AS destino_nombre,
                                                    p_final.cliente AS final_nombre
                                                FROM 
                                                    ltl a
                                                LEFT JOIN 
                                                    clientes c ON a.idCliente = c.id
                                                LEFT JOIN 
                                                    clientes p_origen ON a.idOrigen = p_origen.id
                                                LEFT JOIN 
                                                    clientes p_destino ON a.idDestino = p_destino.id
                                                LEFT JOIN
                                                    clientes p_final ON a.idDestinoFinal = p_final.id ORDER BY id DESC
                                                ";
                                        $query_run = mysqli_query($con, $query);
                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                                $linkHabilitado = $registro['cliente_nombre'] !== null &&
                                                    $registro['origen_nombre'] !== null &&
                                                    $registro['destino_nombre'] !== null &&
                                                    $registro['final_nombre'] !== null;
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['id']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['cliente_nombre']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['origen_nombre']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['destino_nombre']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['final_nombre']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['fecha']; ?></p>
                                                    </td>
                                                    <td style="width: 105px;text-align:center;">

                                                        <a href="<?= $linkHabilitado ? 'generate_ltl.php?id=' . $registro['id'] : '#' ?>"
                                                            class="file-download btn btn-sm m-1 <?= $linkHabilitado ? 'btn-primary' : 'btn-secondary disabled' ?>"
                                                            <?= $linkHabilitado ? '' : 'aria-disabled="true" tabindex="-1"' ?>>
                                                            <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                                        </a>

                                                        <a href="editar-ltl.php?id=<?= $registro['id']; ?>" class="btn btn-warning btn-sm m-1"><i class="bi bi-pencil-square"></i></a>

                                                        <form action="codeltl.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="id" value="<?= $registro['id']; ?>">
                                                            <button type="submit" name="delete" class="btn btn-danger btn-sm m-1"><i class="bi bi-trash-fill"></i></button>
                                                        </form>
                                                        <button type="button" class="btn btn-info btn-sm m-1" data-bs-toggle="modal" data-bs-target="#myModal<?= $registro['id']; ?>" data-id="<?= $registro['id']; ?>"><i class="bi bi-eye-fill"></i></button>

                                                        <div class="modal fade" id="myModal<?= $registro['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $registro['id']; ?>" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                                <div class="modal-content">
                                                                    <?php


                                                                    $query = "SELECT * FROM ltl WHERE id='$registro[id];' ";
                                                                    $query_run = mysqli_query($con, $query);

                                                                    if (mysqli_num_rows($query_run) > 0) {
                                                                        $ltl = mysqli_fetch_array($query_run);
                                                                    ?>
                                                                        <div class="row justify-content-evenly g-0 p-3 w-100" style="max-height: 600px;overflow-y:scroll;">

                                                                            <div class="col-3 mb-3 text-center">
                                                                                <img style="width: 70%;" src="images/logo.png" alt="">
                                                                                <p>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</p>
                                                                            </div>

                                                                            <div class="col-5 mb-3">
                                                                                <h2><b>GRUPO LYSCE S.C.</b></h2>
                                                                                <p style="margin: 0px;">R.F.C GLY170421ES6</p>
                                                                                <p style="margin: 0px;">Sierra del Laurel 312 piso 2, Bosques del Prado Norte, Aguascalientes, Ags. C.P. 20127</p>
                                                                                <p style="margin: 0px;">Tel / Fax +52 (449) 300 3265</p>
                                                                            </div>

                                                                            <div class="col-3 mb-4 text-end">
                                                                                <p style="margin: 5px;"><b>COTIZACIÓN</b></p>
                                                                                <p>Folio: <span style="color:rgb(130, 39, 39);text-transform:uppercase"><?= $ltl['identificador']; ?></span></p>
                                                                                <p style="margin: 5px;">Aguascalientes, Ags a</p>
                                                                                <p><?= $ltl['fecha']; ?></p>
                                                                            </div>

                                                                            <div class="col-12 text-center bg-warning p-1" style="border: 1px solid #666666;border-bottom:0px;">
                                                                                <p><?= $ltl['tipoLtl']; ?></p>
                                                                            </div>

                                                                            <div class="col-12 p-2 text-start" style="border: 1px solid #666666; border-bottom:0px;">
                                                                                <p class="mb-1"><b>Cliente</b></p>
                                                                                <?php
                                                                                $query_client = "SELECT * FROM clientes WHERE id='$ltl[idCliente]'";
                                                                                $query_run_client = mysqli_query($con, $query_client);
                                                                                if (mysqli_num_rows($query_run_client) > 0) {
                                                                                    while ($client = mysqli_fetch_assoc($query_run_client)) {
                                                                                ?>
                                                                                        <p><?= $client['cliente']; ?></p>
                                                                                        <p><b>Domicilio: </b><?= $client['calle']; ?> <?= $client['numexterior']; ?> <?= $client['numinterior']; ?>, <?= $client['colonia']; ?>, <?= $client['city']; ?>, <?= $client['state']; ?>, <?= $client['pais']; ?>. C.P <?= $client['cpostal']; ?></p>
                                                                                        <p><b>Teléfono:</b> <?= $client['telefono']; ?></p>
                                                                                        <p><b>Correo:</b> <?= $client['correo']; ?></p>
                                                                                        <p><b>RFC:</b> <?= $client['rfc']; ?></p>
                                                                                        <p><b>Representante:</b> <?= $client['contacto']; ?></p>
                                                                                <?php
                                                                                    }
                                                                                } else {
                                                                                    echo "No se encontraron registros";
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                            <div class="col-4 p-2 text-start" style="border: 1px solid #666666;">
                                                                                <p class="mb-1"><b>Origen</b></p>
                                                                                <?php
                                                                                $query_client = "SELECT * FROM clientes WHERE id='$ltl[idOrigen]'";
                                                                                $query_run_client = mysqli_query($con, $query_client);
                                                                                if (mysqli_num_rows($query_run_client) > 0) {
                                                                                    while ($client = mysqli_fetch_assoc($query_run_client)) {
                                                                                ?>
                                                                                        <p><?= $client['cliente']; ?></p>
                                                                                        <p><b>Domicilio: </b><?= $client['calle']; ?> <?= $client['numexterior']; ?> <?= $client['numinterior']; ?>, <?= $client['colonia']; ?>, <?= $client['city']; ?>, <?= $client['state']; ?>, <?= $client['pais']; ?>. C.P <?= $client['cpostal']; ?></p>
                                                                                        <p><b>Teléfono:</b> <?= $client['telefono']; ?></p>
                                                                                        <p><b>Correo:</b> <?= $client['correo']; ?></p>
                                                                                        <p><b>RFC:</b> <?= $client['rfc']; ?></p>
                                                                                        <p><b>Representante:</b> <?= $client['contacto']; ?></p>
                                                                                <?php
                                                                                    }
                                                                                } else {
                                                                                    echo "No se encontraron registros";
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                            <div class="col-4 p-2 text-start" style="border: 1px solid #666666;">
                                                                                <p class="mb-1"><b>Destino en frontera</b></p>
                                                                                <?php
                                                                                $query_client = "SELECT * FROM clientes WHERE id='$ltl[idDestino]'";
                                                                                $query_run_client = mysqli_query($con, $query_client);
                                                                                if (mysqli_num_rows($query_run_client) > 0) {
                                                                                    while ($client = mysqli_fetch_assoc($query_run_client)) {
                                                                                ?>
                                                                                        <p><?= $client['cliente']; ?></p>
                                                                                        <p><b>Domicilio: </b><?= $client['calle']; ?> <?= $client['numexterior']; ?> <?= $client['numinterior']; ?>, <?= $client['colonia']; ?>, <?= $client['city']; ?>, <?= $client['state']; ?>, <?= $client['pais']; ?>. C.P <?= $client['cpostal']; ?></p>
                                                                                        <p><b>Teléfono:</b> <?= $client['telefono']; ?></p>
                                                                                        <p><b>Correo:</b> <?= $client['correo']; ?></p>
                                                                                        <p><b>RFC:</b> <?= $client['rfc']; ?></p>
                                                                                        <p><b>Representante:</b> <?= $client['contacto']; ?></p>
                                                                                <?php
                                                                                    }
                                                                                } else {
                                                                                    echo "No se encontraron registros";
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                            <div class="col-4 p-2 text-start" style="border: 1px solid #666666;">
                                                                                <p class="mb-1"><b>Destino Final</b></p>
                                                                                <?php
                                                                                $query_client = "SELECT * FROM clientes WHERE id='$ltl[idDestinoFinal]'";
                                                                                $query_run_client = mysqli_query($con, $query_client);
                                                                                if (mysqli_num_rows($query_run_client) > 0) {
                                                                                    while ($client = mysqli_fetch_assoc($query_run_client)) {
                                                                                ?>
                                                                                        <p><?= $client['cliente']; ?></p>
                                                                                        <p><b>Domicilio: </b><?= $client['calle']; ?> <?= $client['numexterior']; ?> <?= $client['numinterior']; ?>, <?= $client['colonia']; ?>, <?= $client['city']; ?>, <?= $client['state']; ?>, <?= $client['pais']; ?>. C.P <?= $client['cpostal']; ?></p>
                                                                                        <p><b>Teléfono:</b> <?= $client['telefono']; ?></p>
                                                                                        <p><b>Correo:</b> <?= $client['correo']; ?></p>
                                                                                        <p><b>RFC:</b> <?= $client['rfc']; ?></p>
                                                                                        <p><b>Representante:</b> <?= $client['contacto']; ?></p>
                                                                                <?php
                                                                                    }
                                                                                } else {
                                                                                    echo "No se encontraron registros";
                                                                                }
                                                                                ?>
                                                                            </div>

                                                                            <div class="col-7 mt-3 mb-3">
                                                                                <div class="row justify-content-start">
                                                                                    <div class="col-8 text-start">
                                                                                        <p style="margin-bottom: 5px;">
                                                                                            <b>Distancia:</b> <?= number_format($ltl['distanciaOrigenDestinoMillas'], 2, '.', ','); ?> millas <?= number_format($ltl['distanciaOrigenDestinoKms'], 2, '.', ','); ?> Kms
                                                                                        </p>

                                                                                        <p style="margin-bottom: 5px;">
                                                                                            <b>Tiempo / Recorrido:</b> <?= $ltl['tiempoRecorridoOrigenDestino']; ?>
                                                                                        </p>

                                                                                        <p style="margin-bottom: 5px;">
                                                                                            <b>Operador:</b> <?= $ltl['servicio']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-4">
                                                                                        <p style="margin-bottom: 5px;">
                                                                                            <b>Total CFT:</b> <?= number_format($ltl['totalFt3'], 2, '.', ','); ?>
                                                                                        </p>

                                                                                        <p style="margin-bottom: 5px;">
                                                                                            <b>Total m3:</b> <?= number_format($ltl['totalM3'], 2, '.', ','); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-5 mt-3 mb-3 text-end">
                                                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                                                    <b>Distancia:</b> <?= number_format($ltl['distanciaDestinoFinalMillas'], 2, '.', ','); ?> millas <?= number_format($ltl['distanciaDestinoFinalKms'], 2, '.', ','); ?> Kms
                                                                                </p>

                                                                                <p style="margin-bottom: 5px;">
                                                                                    <b>Tiempo / Recorrido:</b> <?= $ltl['tiempoRecorridoDestinoFinal']; ?>
                                                                                </p>

                                                                                <p style="margin-bottom: 5px;">
                                                                                    <b>Operador:</b> <?= $ltl['operador']; ?>
                                                                                </p>

                                                                                <p>
                                                                                    <b>Unidad:</b> <?= $ltl['unidad']; ?>
                                                                                </p>

                                                                            </div>

                                                                            <div class="col-12 text-center p-2">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-secondary">
                                                                                        <p style="color: #fff;"><b>DESCRIPCIÓN DE LAS MERCANCÍAS</b></p>
                                                                                    </div>
                                                                                    <table class="table table-striped">
                                                                                        <tr>
                                                                                            <th>Cantidad</th>
                                                                                            <th>Unidad medida</th>
                                                                                            <th>Descripción</th>
                                                                                            <th>Dimensiones</th>
                                                                                            <th>Peso</th>
                                                                                            <th>Valor factura</th>
                                                                                        </tr>
                                                                                        <?php
                                                                                        // Obtener los ltls de descripcionmercanciasltl relacionados con el ID de ltl
                                                                                        $query_desc = "SELECT * FROM descripcionmercanciasltl WHERE idLtl='$registro[id]'";
                                                                                        $query_run_desc = mysqli_query($con, $query_desc);

                                                                                        if (mysqli_num_rows($query_run_desc) > 0) {
                                                                                            while ($mercancia = mysqli_fetch_assoc($query_run_desc)) {
                                                                                        ?>
                                                                                                <tr>

                                                                                                    <td>
                                                                                                        <p><?= $mercancia['cantidad']; ?></p>
                                                                                                        <p>NMFC</p>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <p><?= $mercancia['unidadMedida']; ?></p>
                                                                                                        <p><?= $mercancia['nmfc']; ?></p>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <p><?= $mercancia['descripcion']; ?></p>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <div class="row">
                                                                                                            <div class="col-6">
                                                                                                                <p><?= $mercancia['largoPlg']; ?> x
                                                                                                                    <?= $mercancia['anchoPlg']; ?> x
                                                                                                                    <?= $mercancia['altoPlg']; ?> inches</p>
                                                                                                                <p><?= number_format($mercancia['piesCubicos'], 2, '.', ','); ?>ft³</p>
                                                                                                            </div>
                                                                                                            <div class="col-6">
                                                                                                                <p><?= $mercancia['largoCm']; ?> x
                                                                                                                    <?= $mercancia['anchoCm']; ?> x
                                                                                                                    <?= $mercancia['altoCm']; ?> mts</p>
                                                                                                                <p><?= number_format($mercancia['metrosCubicos'], 2, '.', ','); ?>m³</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <p><?= number_format($mercancia['libras'], 2, '.', ','); ?> lbs</p>
                                                                                                        <p><?= number_format($mercancia['kilogramos'], 2, '.', ','); ?> kgs</p>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <p>$<?= number_format($mercancia['valorFactura'], 2, '.', ','); ?></p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                        <?php
                                                                                            }
                                                                                        } else {
                                                                                            echo "<tr><td colspan='6' class='text-center'>No se encontraron ltls</td></tr>";
                                                                                        }
                                                                                        ?>
                                                                                    </table>

                                                                                    <div class="row mt-3 mb-3">
                                                                                        <div class="col-3 text-center">
                                                                                            <p>Total bultos: <?= $ltl['totalBultos']; ?></p>
                                                                                            <p>1 <?= $ltl['moneda']; ?> = <?= $ltl['valorMoneda']; ?></p>
                                                                                        </div>

                                                                                        <div class="col-4">
                                                                                            <table class="text-end">
                                                                                                <tr>
                                                                                                    <td>Peso total de la mercancía</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <p><?= number_format($ltl['pesoMercanciaLbs'], 2, '.', ','); ?> lbs</p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2">
                                                                                                        <p><?= number_format($ltl['pesoMercanciaKgs'], 2, '.', ','); ?> kgs</p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>

                                                                                        <div class="col-5">
                                                                                            <table class="text-end w-100">
                                                                                                <tr>
                                                                                                    <td>VALOR TOTAL DE LA MERCANCÍA USD</td>
                                                                                                    <td>
                                                                                                        <p>$<?= number_format($ltl['valorMercancia'], 2, '.', ','); ?></p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>VALOR TOTAL DE LA MERCANCÍA MXN</td>
                                                                                                    <td>
                                                                                                        <p>$<?= number_format($ltl['valorComercial'], 2, '.', ','); ?></p>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12 mt-5">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-secondary">
                                                                                        <p class="text-center" style="color: #fff;"><b>TIPO DE SERVICIO</b></p>
                                                                                    </div>
                                                                                    <table class="table table-striped table-bordered" style="margin-bottom: 0px;" id="servicioTable">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Servicio</th>
                                                                                                <th>Tiempo de transito</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $query_servicio = "SELECT * FROM servicioltl WHERE idLtl='$registro[id]'";
                                                                                            $query_run_servicio = mysqli_query($con, $query_servicio);

                                                                                            if (mysqli_num_rows($query_run_servicio) > 0) {
                                                                                                while ($servicio = mysqli_fetch_assoc($query_run_servicio)) {
                                                                                            ?>
                                                                                                    <tr>

                                                                                                        <td>
                                                                                                            <p><?= $servicio['conceptoServicio']; ?></p>
                                                                                                        </td>

                                                                                                        <td>
                                                                                                            <p><?= $servicio['tiempoServicio']; ?></p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                            <?php
                                                                                                }
                                                                                            } else {
                                                                                                echo "<tr><td colspan='6' class='text-center'>No se registro tipo de servicio</td></tr>";
                                                                                            }
                                                                                            ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12 mt-5">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-secondary">
                                                                                        <p class="text-center" style="color: #fff;"><b>DETERMINACIÓN DE INCREMENTABLES</b></p>
                                                                                    </div>
                                                                                    <table class="table table-striped tabñe-bordered" id="incrementableTable" style="margin-bottom: 0px;">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Incrementable</th>
                                                                                                <th>USD</th>
                                                                                                <th>MXN</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $query_incrementable = "SELECT * FROM incrementablesltl WHERE idLtl='$registro[id]'";
                                                                                            $query_run_incrementable = mysqli_query($con, $query_incrementable);

                                                                                            if (mysqli_num_rows($query_run_incrementable) > 0) {
                                                                                                while ($incrementable = mysqli_fetch_assoc($query_run_incrementable)) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <p><?= $incrementable['incrementable']; ?></p>
                                                                                                        </td>

                                                                                                        <td>
                                                                                                            <p>$<?= number_format($incrementable['incrementableUsd'], 2, '.', ','); ?></p>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <p>$<?= number_format($incrementable['incrementableMx'], 2, '.', ','); ?></p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                            <?php
                                                                                                }
                                                                                            } else {
                                                                                                echo "<tr><td colspan='6' class='text-center'>No se encontraron incrementables</td></tr>";
                                                                                            }
                                                                                            ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                            <tr id="totalRow">
                                                                                                <td class="text-end"><b>TOTAL</b></td>
                                                                                                <td>
                                                                                                    <p>$<?= number_format($ltl['totalIncrementableUsd'], 2, '.', ','); ?> USD</p>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <p>$<?= number_format($ltl['totalIncrementableMx'], 2, '.', ','); ?> MXN</p>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12 mt-5">
                                                                                <div class="card">
                                                                                    <div class="card-header bg-secondary">
                                                                                        <p class="text-center" style="color: #fff;"><b>GASTOS POR FLETE TERRESTRE</b></p>
                                                                                    </div>
                                                                                    <table class="table table-striped table-bordered" id="tablaGasto" style="margin-bottom: 0px;">
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $query_gasto = "SELECT * FROM gastosltl WHERE idLtl='$registro[id]'";
                                                                                            $query_run_gasto = mysqli_query($con, $query_gasto);

                                                                                            if (mysqli_num_rows($query_run_gasto) > 0) {
                                                                                                while ($gasto = mysqli_fetch_assoc($query_run_gasto)) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td>
                                                                                                            <div class="row">
                                                                                                                <div class="col-9">
                                                                                                                    <p><?= $gasto['conceptoGasto']; ?></p>
                                                                                                                </div>
                                                                                                                <?php if ($gasto['conceptoGasto'] == "Seguro de tránsito de mercancía") : ?>
                                                                                                                    <div class="col-3">
                                                                                                                        <p><?= $ltl['porcentajeSeguro']; ?></p>
                                                                                                                    </div>
                                                                                                                <?php endif; ?>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <div class="form-check float-end">
                                                                                                                <input class="form-check-input" type="checkbox" name="ivaGasto[]" id="flexCheck4"
                                                                                                                    <?php if ($gasto['ivaGasto'] == 1) echo 'checked'; ?> disabled>
                                                                                                                <label class="form-check-label" for="flexCheck4"> IVA 16% </label>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                        <td colspan="2" class="text-end">
                                                                                                            <p>$<?= number_format($gasto['montoGasto'], 2, '.', ','); ?> USD</p>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                            <?php
                                                                                                }
                                                                                            } else {
                                                                                                echo "<tr><td colspan='6' class='text-center'>No se encontraron ltls</td></tr>";
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="text-end">
                                                                                                <td colspan="2">Subtotal</td>
                                                                                                <td colspan="2" style="width:20%;">
                                                                                                    <p>$<?= number_format($ltl['subtotalFlete'], 2, '.', ','); ?> USD</p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr class="text-end">
                                                                                                <td colspan="2">I.V.A 16%</td>
                                                                                                <td colspan="2">
                                                                                                    <p>$<?= number_format($ltl['impuestosFlete'], 2, '.', ','); ?> USD</p>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr class="text-end">
                                                                                                <td colspan="2">
                                                                                                    <div class="form-check float-end">
                                                                                                        <input class="form-check-input" type="checkbox" name="retencionFleteCheck" id="retencionCheck"
                                                                                                            <?= (!empty($ltl['retencionFlete']) && $ltl['retencionFlete'] > 0) ? 'checked' : ''; ?> disabled>
                                                                                                        <label class="form-check-label" for="retencionCheck"> Retención 4% </label>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td colspan="2">
                                                                                                    <p>$<?= number_format($ltl['retencionFlete'], 2, '.', ','); ?> USD</p>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <table class="mt-3 bg-warning w-100" style="border: 1px solid #000000;padding:5px;">
                                                                                    <tr class="text-end">
                                                                                        <td style="border-right: 1px solid #000000;padding:5px;"><b>TOTAL USD</b></td>
                                                                                        <td style="width: 180px;">
                                                                                            <p>$<?= number_format($ltl['totalCotizacionNumero'], 2, '.', ','); ?></p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr class="text-center" style="border-top: 1px solid #000000;padding:5px;">
                                                                                        <td colspan="2">
                                                                                            <p><?= $ltl['totalCotizacionTexto']; ?></p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>


                                                                            <div class="col-12 mt-3 text-start">
                                                                                <b>OBSERVACIONES</b>
                                                                                <pre><?= $ltl['observaciones']; ?></pre>
                                                                            </div>


                                                                        </div>
                                                                    <?php
                                                                    } else {
                                                                        echo "<h4>No Such Id Found</h4>";
                                                                    }

                                                                    ?>



                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>





                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<td colspan='8'><p> No se encontro ningun registro </p></td>";
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

    <div class="spinner-overlay" style="z-index: 9999;">
        <div class="spinner-container">
            <div class="spinner-grow text-primary spinner" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script src="js/js.js"></script>
    <script>
        $(document).ready(function() {
            const downloadButtons = document.querySelectorAll(".file-download");
            const spinnerOverlay = document.querySelector(".spinner-overlay");

            downloadButtons.forEach(button => {
                button.addEventListener("click", function(event) {
                    event.preventDefault();
                    $('.spinner-overlay').show();

                    const url = button.href;

                    setTimeout(() => {
                        window.location.href = url;
                        setTimeout(() => {
                            spinnerOverlay.style.display = "none";
                        }, 10000);
                    }, 500);
                });
            });
        });
    </script>


</body>

</html>