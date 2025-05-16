<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

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
    <title>Editar marítimo LCL | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico" />
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="sb-nav-fixed">
    <?php include 'sidenav.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container mt-3">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>EDITAR COTIZACIÓN
                                    <a href="aereo-exportacion.php" class="btn btn-danger btn-sm float-end">Regresar</a>
                                </h4>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($_GET['id'])) {
                                    $registro_id = mysqli_real_escape_string($con, $_GET['id']);
                                    $query = "SELECT * FROM lcl WHERE id='$registro_id' ";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $registro = mysqli_fetch_array($query_run);
                                        $titulo = $registro['tipoLcl'];
                                ?>
                                        <form action="codelcl.php" method="POST" class="row justify-content-evenly">
                                            <input class="form-control" value="<?= $registro['id']; ?>" type="hidden" name="id">
                                            <div class="col-3 mb-3 text-center">
                                                <img style="width: 70%;" src="images/logo.png" alt="">
                                                <p>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</p>
                                            </div>
                                            <div class="col-4 mb-3">
                                                <h2><b>GRUPO LYSCE S.C.</b></h2>
                                                <p style="margin: 0px;">R.F.C GLY170421ES6</p>
                                                <p style="margin: 0px;">Sierra del Laurel 312 piso 2, Bosques del Prado Norte, Aguascalientes, Ags. C.P. 20127</p>
                                                <p style="margin: 0px;">Tel / Fax +52 (449) 300 3265</p>
                                            </div>
                                            <div class="col-3 mb-3">
                                                <p style="margin: 5px;"><b>COTIZACIÓN</b></p>
                                                <input class="form-control" name="identificador" value="<?= $registro['identificador']; ?>">
                                                <p style="margin: 5px;">Aguascalientes, Ags a</p>
                                                <input class="form-control" type="text" name="fecha" id="" value="<?= $registro['fecha']; ?>">
                                            </div>
                                            <div class="col-12 text-center bg-warning p-1" style="border: 1px solid #666666;border-bottom:0px;">
                                                <select class="form-select bg-warning" name="tipoLcl" required>
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM tiposervicio WHERE tipoServicio = 'lcl'";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($titulo = mysqli_fetch_assoc($result)) {
                                                            $nombre = $titulo['nombreServicio'];
                                                            $selected = ($registro['tipoLcl'] == $nombre) ? "selected" : ""; // Verifica si es el seleccionado
                                                            echo "<option value='$nombre' $selected>$nombre</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="col-12 p-3" style="border: 1px solid #666666; border-bottom:0px;">
                                                <p class="mb-1"><b>Cliente</b></p>
                                                <select class="form-select mb-3" name="idCliente" id="cliente">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1 AND tipo = 'Cliente'";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($cliente = mysqli_fetch_assoc($result)) {
                                                            $nombre = $cliente['cliente'];
                                                            $idCliente = $cliente['id'];
                                                            $selected = ($registro['idCliente'] == $idCliente) ? "selected" : ""; // Verifica si es el seleccionado
                                                            echo "<option value='$idCliente' $selected>$nombre</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <p id="detalleCliente"></p>
                                            </div>

                                            <div class="col-4 p-3" style="border: 1px solid #666666;">
                                                <p class="mb-1"><b>Origen</b></p>
                                                <select class="form-select" name="idOrigen" id="origen">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($origen = mysqli_fetch_assoc($result)) {
                                                            $nombre = $origen['cliente'];
                                                            $id = $origen['id'];
                                                            $tipo = $origen['tipo'];
                                                            $selected = ($registro['idOrigen'] == $id) ? "selected" : "";
                                                            echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <p id="detalleOrigen"></p>
                                            </div>

                                            <div class="col-4 p-3" style="border: 1px solid #666666;">
                                                <p class="mb-1"><b>Destino en frontera</b></p>
                                                <select class="form-select" name="idDestino" id="aduana">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($aduana = mysqli_fetch_assoc($result)) {
                                                            $nombre = $aduana['cliente'];
                                                            $id = $aduana['id'];
                                                            $tipo = $aduana['tipo'];
                                                            $selected = ($registro['idDestino'] == $id) ? "selected" : "";
                                                            echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <p id="detalleAduana"></p>
                                            </div>

                                            <div class="col-4 p-3" style="border: 1px solid #666666;">
                                                <p class="mb-1"><b>Destino Final</b></p>
                                                <select class="form-select" name="idAduana" id="destino">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($destino = mysqli_fetch_assoc($result)) {
                                                            $nombre = $destino['cliente'];
                                                            $id = $destino['id'];
                                                            $tipo = $destino['tipo'];
                                                            $selected = ($registro['idDestinoFinal'] == $id) ? "selected" : ""; // Verifica si es el seleccionado
                                                            echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <p id="detalleDestino"></p>
                                            </div>

                                            <div class="col-7 mt-3 mb-3">
                                                <div class="row justify-content-start">
                                                    <div class="col-8">
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Distancia:</b>
                                                            <input name="distanciaOrigenDestinoMillas" class="form-control" style="width: 90px; display: inline-block;" value="<?= $registro['distanciaOrigenDestinoMillas']; ?>" type="text" id="millas" oninput="convertirAMetros()">
                                                            millas
                                                            <input name="distanciaOrigenDestinoKms" value="<?= $registro['distanciaOrigenDestinoKms']; ?>" class="form-control" style="width: 90px; display: inline-block;" type="text" id="km" oninput="convertirAMillas()"> Kms
                                                        </p><br>

                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Tiempo / Recorrido:</b>
                                                            <input name="tiempoRecorridoOrigenDestino" value="<?= $registro['tiempoRecorridoOrigenDestino']; ?>" class="form-control" style="width: 110px; display: inline-block;" type="text">
                                                        </p><br>

                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Operador:</b>
                                                            <input name="servicio" value="<?= $registro['servicio']; ?>" class="form-control" style="width: 167px; display: inline-block;" type="text">
                                                        </p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Total CFT:</b>
                                                            <input name="totalFt3" class="form-control" style="width: 80px; display: inline-block;" type="text" id="ft3Total" readonly>
                                                        </p><br>

                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Total m3:</b>
                                                            <input name="totalM3" class="form-control" style="width: 80px; display: inline-block;" type="text" id="m3Total" readonly>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-5 mt-3 mb-3 text-end">
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Distancia:</b>
                                                    <input name="distanciaDestinoFinalMillas" value="<?= $registro['distanciaDestinoFinalMillas']; ?>" class="form-control" style="width: 90px; display: inline-block;" type="text" id="milla" oninput="convertirAMetrosDos()">
                                                    millas
                                                    <input name="distanciaDestinoFinalKms" value="<?= $registro['distanciaDestinoFinalKms']; ?>" class="form-control" style="width: 90px; display: inline-block;" type="text" id="kms" oninput="convertirAMillasDos()"> Kms
                                                </p><br>

                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Tiempo / Recorrido:</b>
                                                    <input name="tiempoRecorridoDestinoFinal" value="<?= $registro['tiempoRecorridoDestinoFinal']; ?>" class="form-control" style="width: 80px; display: inline-block;" type="text">
                                                </p><br>

                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Operador:</b>
                                                    <input name="operador" value="<?= $registro['operador']; ?>" class="form-control" style="width: 167px; display: inline-block;" type="text">
                                                </p>

                                                <p style="display: inline-block;">
                                                    <b>Unidad:</b>
                                                    <input name="unidad" value="<?= $registro['unidad']; ?>" class="form-control" style="width: 230px; display: inline-block;" type="text">
                                                </p>

                                            </div>

                                            <div class="col-12 text-center p-2">
                                                <div class="card">
                                                    <div class="card-header bg-secondary">
                                                        <p style="color: #fff;"><b>DESCRIPCIÓN DE LAS MERCANCÍAS</b></p>
                                                    </div>
                                                    <table class="table table-striped" id="miTablaCotizacion" style="margin-bottom: 0px;">
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>Unidad medida</th>
                                                            <th>Descripción</th>
                                                            <th>Dimensiones</th>
                                                            <th>Peso</th>
                                                            <th>Valor factura</th>
                                                        </tr>
                                                        <?php
                                                        // Obtener los registros de descripcionmercanciaslcl relacionados con el ID de lcl
                                                        $query_desc = "SELECT * FROM descripcionmercanciaslcl WHERE idLcl='$registro_id'";
                                                        $query_run_desc = mysqli_query($con, $query_desc);

                                                        if (mysqli_num_rows($query_run_desc) > 0) {
                                                            while ($mercancia = mysqli_fetch_assoc($query_run_desc)) {
                                                        ?>
                                                                <tr>

                                                                    <td>
                                                                        <input style="width: 60px;" class="form-control mb-3" type="text" name="cantidad[]" value="<?= $mercancia['cantidad']; ?>" oninput="convertToCmAndCalculateVolume(this)">
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control mb-1" type="text" name="unidadMedida[]" value="<?= $mercancia['unidadMedida']; ?>">
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control" type="text" name="descripcion[]" value="<?= $mercancia['descripcion']; ?>">
                                                                    </td>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-6">
                                                                                <input class="form-control mb-1" type="text" name="largoPlg[]" placeholder="Largo (pulgadas)" oninput="convertToCmAndCalculateVolume(this)" value="<?= $mercancia['largoPlg']; ?>">
                                                                                <input class="form-control mb-1" type="text" name="anchoPlg[]" placeholder="Ancho (pulgadas)" oninput="convertToCmAndCalculateVolume(this)" value="<?= $mercancia['anchoPlg']; ?>">
                                                                                <input class="form-control" type="text" name="altoPlg[]" placeholder="Alto (pulgadas)" oninput="convertToCmAndCalculateVolume(this)" value="<?= $mercancia['altoPlg']; ?>">
                                                                                <p class="mb-3">pulgadas</p>
                                                                                <input class="form-control" type="text" name="piesCubicos[]" placeholder="pies cúbicos" value="<?= $mercancia['piesCubicos']; ?>" readonly>
                                                                                <p>ft³</p>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <input class="form-control mb-1" type="text" id="altoFilaCm" name="largoCm[]" placeholder="Largo (mts)" oninput="convertToInchesAndCalculateVolume(this)" value="<?= $mercancia['largoCm']; ?>">
                                                                                <input class="form-control mb-1" type="text" id="anchoFilaCm" name="anchoCm[]" placeholder="Ancho (mts)" oninput="convertToInchesAndCalculateVolume(this)" value="<?= $mercancia['anchoCm']; ?>">
                                                                                <input class="form-control" type="text" id="profundidadFilaCm" name="altoCm[]" placeholder="Alto (mts)" oninput="convertToInchesAndCalculateVolume(this)" value="<?= $mercancia['altoCm']; ?>">
                                                                                <p class="mb-3">mts</p>
                                                                                <input class="form-control" type="text" name="metrosCubicos[]" placeholder="metros cúbicos" value="<?= $mercancia['metrosCubicos']; ?>" readonly>
                                                                                <p>m³</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control mb-1" type="text" placeholder="lbs" name="libras[]" id="pesoFilaMercanciaLbs" oninput="convertToKg(this); actualizarTotales();" value="<?= $mercancia['libras']; ?>">
                                                                        <input class="form-control" type="text" placeholder="kgs" name="kilogramos[]" id="pesoFilaMercanciaKgs" oninput="convertToLbs(this); actualizarTotales();" value="<?= $mercancia['kilogramos']; ?>">
                                                                    </td>
                                                                    <td><input class="form-control" id="valorFilaMercancia" type="text" name="valorFactura[]" placeholder="Precio total" oninput="actualizarTotales()" value="<?= $mercancia['valorFactura']; ?>">
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                                                        }
                                                        ?>
                                                    </table>
                                                    <div class="text-center p-2">
                                                        <button class="btn btn-danger" type="button" onclick="eliminarUltimaFila()">-</button>
                                                        <button class="btn btn-secondary" type="button" onclick="agregarFila()">+</button>
                                                    </div>
                                                </div>

                                                <div class="row mt-3 mb-3">
                                                    <div class="col-3 text-center">
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            1 <input class="form-control" style="width: 80px; display: inline-block;" type="text" name="moneda" id="moneda" value="<?= $registro['moneda']; ?>"> = <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="valorMoneda" name="valorMoneda" value="<?= $registro['valorMoneda']; ?>" oninput="actualizarTotales()">
                                                        </p>
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            1 <input class="form-control" style="width: 80px; display: inline-block;" type="text" name="equivalencia" value="<?= $registro['equivalencia']; ?>"> = <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="equivalencia" name="valorEquivalencia" value="<?= $registro['valorEquivalencia']; ?>" oninput="actualizarTotales()">
                                                        </p>
                                                    </div>

                                                    <div class="col-4">
                                                        <table class="text-end">
                                                            <tr>
                                                                <td>Peso total de la mercancía</td>
                                                                <td><input class="form-control" style="width: 120px; display: inline-block;" type="text" value="<?= $registro['pesoMercanciaLbs']; ?>" id="pesoMercanciaLbs" name="pesoMercanciaLbs" readonly> lbs</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><input class="form-control" style="width: 120px; display: inline-block;" type="text" value="<?= $registro['pesoMercanciaKgs']; ?>" id="pesoMercanciaKgs" name="pesoMercanciaKgs" readonly> kgs</td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="col-5">
                                                        <table class="text-end w-100">
                                                            <tr>
                                                                <td>VALOR TOTAL DE LA MERCANCÍA USD</td>
                                                                <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorMercancia" name="valorMercanciaUSD" readonly oninput="actualizarSubtotal();"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>VALOR TOTAL DE LA MERCANCÍA MXN</td>
                                                                <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorComercial" name="valorMercanciaMXN" readonly></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 bg-light mt-5">
                                                <div class="card-header bg-secondary">
                                                    <p class="text-center text-light"><b>GASTOS POR TRASLADO DE MERCANCIAS A AEROPUERTO</b></p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Lugar origen:</b> <input class="form-control" style="width: 300px; display: inline-block;" type="text" name="lugarOrigen" value="<?= $registro['lugarOrigen']; ?>">
                                                        </p>
                                                        <table class="table table-striped origen-table text-start" id="origenTable">
                                                            <tr>
                                                                <th>GASTOS EN ORIGEN</th>
                                                                <th>MIN</th>
                                                                <th>$</th>
                                                                <th>TOTAL</th>
                                                                <th>TOTAL USD</th>
                                                            </tr>
                                                            <?php
                                                            $query_origen = "SELECT * FROM gastosorigenlcl WHERE idLcl='$registro_id'";
                                                            $query_run_origen = mysqli_query($con, $query_origen);

                                                            if (mysqli_num_rows($query_run_origen) > 0) {
                                                                while ($origen = mysqli_fetch_assoc($query_run_origen)) {

                                                            ?>
                                                                    <tr>
                                                                        <td style="min-width: 140px;">
                                                                            <input style="min-width: 100%;" type="text" name="gastosOrigen[]" class="form-control" value="<?= $origen['gastosOrigen']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="euros[]" class="euros form-control" value="<?= $origen['euros']; ?>" oninput="actualizarTotalesOrigen();">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="equivalenciaOrigen[]" class="equivalencia form-control" value="<?= $origen['equivalenciaOrigen']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="usdOrigen[]" class="usOrigen form-control" value="<?= $origen['usdOrigen']; ?>" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFilaOrigen(this)">
                                                                                <i class="bi bi-trash3"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                                                            }
                                                            ?>

                                                            <tr id="filaAmsFee"></tr>
                                                            <tr>
                                                                <td colspan="4" class="text-end"><b>Total</b></td>
                                                                <td colspan="2"><input type="text" style="min-width: 100%;" class="form-control" name="totalOrigenAll" id="totalOrigenAll" readonly></td>
                                                            </tr>
                                                        </table>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="agregarFilaDespuesDeAMS()">Agregar gasto</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Lugar destino:</b> <input class="form-control" style="width: 280px; display: inline-block;" type="text" name="lugarDestino">
                                                        </p>
                                                        <table class="table table-striped destino-table text-start" id="destinoTable">
                                                            <tr>
                                                                <td><b>GASTOS EN DESTINO</b></td>
                                                                <td><b>USD</b></td>
                                                                <td><b>MX</b></td>
                                                            </tr>
                                                            <?php
                                                            $query_destino = "SELECT * FROM gastosdestinolcl WHERE idLcl='$registro_id'";
                                                            $query_run_destino = mysqli_query($con, $query_destino);

                                                            if (mysqli_num_rows($query_run_destino) > 0) {
                                                                while ($destino = mysqli_fetch_assoc($query_run_destino)) {
                                                            ?>
                                                                    <tr>
                                                                        <td style="min-width: 140px;"><input style="min-width: 100%;" type="text" name="gastoDestino[]" class="form-control" value="<?= $destino['gastoDestino']; ?>"></td>
                                                                        <td><input type="text" name="usdDestino[]" class="dolarInputs form-control" value="<?= $destino['usdDestino']; ?>" oninput="updateRowDestinySpents(this)"></td>
                                                                        <td><input type="text" name="mxnDestino[]" class="mxnOutputs form-control" value="<?= $destino['mxnDestino']; ?>"></td>
                                                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFilaDestino(this)"><i class="bi bi-trash3"></i></button></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='3' class='text-center'>No se encontraron registros</td></tr>";
                                                            }
                                                            ?>
                                                            <tr id="filaAmsFeeDestino"></tr>
                                                            <tr>
                                                                <td class="text-end">Subtotal</td>
                                                                <td><input name="subtotalDestinoUsd" class="form-control" readonly></td>
                                                                <td><input name="subtotalDestinoMx" class="form-control" readonly></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Impuestos</td>
                                                                <td><input name="impuestosDestinoUsd" class="form-control" readonly></td>
                                                                <td><input name="impuestosDestinoMx" class="form-control" readonly></td>
                                                                <td>
                                                                    <div class="form-check float-end">
                                                                        <input style="width: 15px !important;" class="form-check-input" type="checkbox" name="ivaDestino" checked>
                                                                        <label class="form-check-label" for="flexCheck2"> IVA 16% </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end">Total</td>
                                                                <td><input name="totalDestinoUsd" id="totalDestinoUsd" class="form-control" readonly></td>
                                                                <td><input name="totalDestinoMx" class="form-control" readonly></td>
                                                            </tr>
                                                        </table>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="agregarFilaDespuesDeAMSDestino()">Agregar gasto</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-10 text-end">
                                                                <p><b>VALOR TOTAL FLETE INT (USD)</b></p>
                                                            </div>
                                                            <div class="col-2 text-end"><input name="valorTotalFlete" class="form-control" readonly id="valorTotalFlete"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-5">
                                                <div class="card">
                                                    <div class="card-header bg-secondary">
                                                        <p class="text-center" style="color: #fff;"><b>DETERMINACIÓN DE INCREMENTABLES</b></p>
                                                    </div>
                                                    <table class="table table-striped table-bordered" id="incrementableTable" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr>
                                                                <th>Incrementable</th>
                                                                <th>USD</th>
                                                                <th>MXN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $query_incrementable = "SELECT * FROM incrementableslcl WHERE idLcl='$registro_id'";
                                                            $query_run_incrementable = mysqli_query($con, $query_incrementable);

                                                            if (mysqli_num_rows($query_run_incrementable) > 0) {
                                                                while ($incrementable = mysqli_fetch_assoc($query_run_incrementable)) {
                                                                    $uniqueId = $incrementable['id']; // Usa el ID real de la BD
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <select class="form-select conceptoIncrementable" name="incrementable[]" data-id="<?= $uniqueId; ?>" onchange="actualizarConceptoGasto(this)">
                                                                                <option value="" disabled selected>Selecciona una opción</option>
                                                                                <?php
                                                                                $query = "SELECT * FROM tipoincrementable WHERE tipo = 'lcl'";
                                                                                $result = mysqli_query($con, $query);
                                                                                $actual_incrementable = $incrementable['incrementable'];

                                                                                if (mysqli_num_rows($result) > 0) {
                                                                                    while ($registro_incrementable = mysqli_fetch_assoc($result)) {
                                                                                        $option_incrementable = $registro_incrementable['incrementable'];
                                                                                        $selected_incrementable = ($option_incrementable == $actual_incrementable) ? "selected" : "";
                                                                                        echo "<option value='$option_incrementable' $selected_incrementable>$option_incrementable</option>";
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="number" name="incrementableUsd[]" class="form-control usd-input" data-id="<?= $uniqueId; ?>" value="<?= $incrementable['incrementableUsd']; ?>" oninput="updateRow(this); sincronizarGasto(this);"></td>
                                                                        <td><input type="text" name="incrementableMx[]" class="form-control mxn-input" value="<?= $incrementable['incrementableMx']; ?>" readonly></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                                                            }
                                                            ?>

                                                        </tbody>
                                                        <tfoot>
                                                            <tr id="totalRow">
                                                                <td class="text-end"><b>TOTAL</b></td>
                                                                <td><input type="text" id="totalUSD" name="totalIncrementableUsd" class="form-control" value="0" readonly></td>
                                                                <td><input type="text" id="totalMXN" name="totalIncrementableMx" class="form-control" value="0" readonly></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <div class="col-12 text-center p-2">
                                                        <button class="btn btn-danger" id="removeRowButton" type="button">-</button>
                                                        <button class="btn btn-secondary" id="addRowButton" type="button" onclick="agregarIncrementable()">+</button>
                                                    </div>
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
                                                            $query_gasto = "SELECT * FROM gastoslcl WHERE idLcl='$registro_id'";
                                                            $query_run_gasto = mysqli_query($con, $query_gasto);

                                                            if (mysqli_num_rows($query_run_gasto) > 0) {
                                                                while ($gasto = mysqli_fetch_assoc($query_run_gasto)) {
                                                                    // Buscar el ID del incrementable correspondiente según el conceptoGasto
                                                                    $conceptoGasto = $gasto['conceptoGasto'];
                                                                    $query_incrementable_id = "SELECT id FROM incrementableslcl WHERE incrementable = '$conceptoGasto' AND idLcl='$registro_id' LIMIT 1";
                                                                    $result_incrementable_id = mysqli_query($con, $query_incrementable_id);
                                                                    $incrementable = mysqli_fetch_assoc($result_incrementable_id);
                                                                    $uniqueId = $incrementable ? $incrementable['id'] : 'null'; // Si no encuentra coincidencia, usa 'null'
                                                            ?>
                                                                    <tr data-id="<?= $uniqueId; ?>">
                                                                        <td>
                                                                            <input type="text" class="form-control conceptoGasto" name="conceptoGasto[]" data-id="<?= $uniqueId; ?>" value="<?= $gasto['conceptoGasto']; ?>">
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check float-end">
                                                                                <input class="form-check-input" type="checkbox" name="ivaGasto[]" data-id="<?= $uniqueId; ?>" <?= ($gasto['ivaGasto'] == 1) ? 'checked' : ''; ?>>
                                                                                <label class="form-check-label"> IVA 16% </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-end">
                                                                            <input type="text" value="<?= $gasto['montoGasto']; ?>" class="form-control montoGasto" name="montoGasto[]" data-id="<?= $uniqueId; ?>" oninput="actualizarSubtotal(); sincronizarIncrementable(this);">
                                                                        </td>
                                                                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                                                            }
                                                            ?>

                                                            <tr class="text-end">
                                                                <td colspan="2">Subtotal</td>
                                                                <td colspan="2" style="width:20%;"><input class="form-control" name="subtotalFlete" type="text" readonly></td>
                                                            </tr>
                                                            <tr class="text-end">
                                                                <td colspan="2">I.V.A 16%</td>
                                                                <td colspan="2"><input class="form-control" name="impuestosFlete" type="text" readonly></td>
                                                            </tr>
                                                            <tr class="text-end">
                                                                <td colspan="2">
                                                                    <div class="form-check float-end">
                                                                        <input class="form-check-input" type="checkbox" name="retencionFleteCheck" id="retencionCheck"
                                                                            <?= (!empty($registro['retencionFlete']) && $registro['retencionFlete'] > 0) ? 'checked' : ''; ?>>
                                                                        <label class="form-check-label" for="retencionCheck"> Retención 4% </label>
                                                                    </div>
                                                                </td>
                                                                <td colspan="2"><input class="form-control" name="retencionFlete" type="text" readonly></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="text-center p-2">
                                                        <button type="button" class="btn btn-secondary" onclick="nuevoGasto()">+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                function actualizarSubtotal() {
                                                    let subtotal = 0;
                                                    let iva = 0;
                                                    let retencion = 0;

                                                    document.querySelectorAll("#tablaGasto tbody tr").forEach((fila) => {
                                                        let montoInput = fila.querySelector('input[name="montoGasto[]"]');
                                                        let checkboxIVA = fila.querySelector('input[name="ivaGasto[]"]');

                                                        if (montoInput) {
                                                            let monto = parseFloat(montoInput.value) || 0;
                                                            subtotal += monto;

                                                            if (checkboxIVA && checkboxIVA.checked) {
                                                                iva += monto * 0.16;
                                                            }
                                                        }
                                                    });

                                                    let checkboxRetencion = document.querySelector('#retencionCheck');
                                                    if (checkboxRetencion && checkboxRetencion.checked) {
                                                        retencion = subtotal * 0.04;
                                                    } else {
                                                        retencion = 0;
                                                    }

                                                    // Actualizar los valores de los inputs
                                                    document.querySelector('input[name="subtotalFlete"]').value = subtotal.toFixed(2);
                                                    document.querySelector('input[name="impuestosFlete"]').value = iva.toFixed(2);
                                                    document.querySelector('input[name="retencionFlete"]').value = retencion.toFixed(2);

                                                    var sumaGastos = 0;

                                                    // Obtener todos los inputs de montoGasto y sumar sus valores
                                                    var montoGastoInputs = document.querySelectorAll("[name='montoGasto[]']");
                                                    montoGastoInputs.forEach(input => {
                                                        var valor = parseFloat(input.value) || 0;
                                                        sumaGastos += valor;
                                                    });

                                                    // Actualizar el campo subtotalFlete con la suma
                                                    var subtotalFleteInput = document.querySelector("[name='subtotalFlete']");
                                                    if (subtotalFleteInput) {
                                                        subtotalFleteInput.value = sumaGastos.toFixed(2);
                                                    }

                                                    // Obtener el valor del input con id="valorTotalFlete" y sumarlo al total
                                                    let valorTotalFleteInput = document.getElementById("valorTotalFlete");
                                                    let valorTotalFlete = valorTotalFleteInput ? parseFloat(valorTotalFleteInput.value) || 0 : 0;

                                                    // Calcular el total de la cotización incluyendo valorTotalFlete
                                                    let totalCotizacion = (subtotal + iva - retencion + valorTotalFlete).toFixed(2);
                                                    document.querySelector('input[name="totalCotizacionNumero"]').value = totalCotizacion;
                                                }

                                                document.addEventListener("input", actualizarSubtotal);
                                                document.addEventListener("change", actualizarSubtotal);
                                            </script>

                                            <table class="mt-3 bg-warning w-100" style="border: 1px solid #000000;padding:5px;">
                                                <tr class="text-end">
                                                    <td style="border-right: 1px solid #000000;padding:5px;"><b>TOTAL USD</b></td>
                                                    <td style="width: 180px;">
                                                        <input class="form-control bg-warning" name="totalCotizacionNumero" id="totalCotizacionNumero" value="<?= $registro['totalCotizacionNumero']; ?>" type="text" readonly>
                                                    </td>
                                                </tr>
                                                <tr class="text-center" style="border-top: 1px solid #000000;padding:5px;">
                                                    <td colspan="2">
                                                        <input class="form-control bg-warning" name="totalCotizacionTexto" id="totalCotizacionTexto" type="text" readonly>
                                                    </td>
                                                </tr>
                                            </table>


                                            <div class="col-12 p-0">
                                                <table class="mt-3 w-100" style="border: 1px solid #000000;">
                                                    <tr class="text-center bg-secondary">
                                                        <td colspan="2" style="border-bottom: 1px solid #000000;padding:5px;color:#fff;"><b>OBSERVACIONES</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <textarea value="" class="form-control" name="observaciones" style="min-height: 200px;" id="observaciones"><?= $registro['observaciones']; ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr style="padding:5px;">
                                                        <td> </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr style="padding:5px;">
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr style="padding:5px;">
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="modal-footer mt-5">
                                                <a href="aereo-exportacion.php" class="btn btn-secondary m-1">Cancelar</a>
                                                <button type="submit" class="btn btn-success m-1" name="update">Guardar</button>
                                            </div>
                                        </form>
                                <?php
                                    } else {
                                        echo "<h4>No Such Id Found</h4>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
        <script>
            var idCliente = "<?php echo $registro['idCliente']; ?>";
            var idOrigen = "<?php echo $registro['idOrigen']; ?>";
            var idAduana = "<?php echo $registro['idDestino']; ?>";
            var idDestino = "<?php echo $registro['idDestinoFinal']; ?>";
            var totalFt3 = "<?php echo $registro['totalFt3']; ?>";
            var totalM3 = "<?php echo $registro['totalM3']; ?>";
            var pesoMercanciaKgs = "<?php echo $registro['pesoMercanciaKgs']; ?>";
            // var pesoMercanciaKgs = "$registro['pesoVolumetrico']; ?>";
            var pesoMercanciaLbs = "<?php echo $registro['pesoMercanciaLbs']; ?>";
            var valorMercancia = "<?php echo $registro['valorMercanciaUSD']; ?>";
            var valorComercial = "<?php echo $registro['valorMercanciaMXN']; ?>";
            var totalIncrementableMx = "<?php echo $registro['totalIncrementableMx']; ?>";
            var totalIncrementableUsd = "<?php echo $registro['totalIncrementableUsd']; ?>";
            var subtotalFlete = "<?php echo $registro['subtotalFlete']; ?>";
            var impuestosFlete = "<?php echo $registro['impuestosFlete']; ?>";
            var retencionFlete = "<?php echo $registro['retencionFlete']; ?>";
            var totalOrigenAll = "<?php echo $registro['totalOrigenAll']; ?>";
            var subtotalDestinoUsd = "<?php echo $registro['subtotalDestinoUsd']; ?>";
            var subtotalDestinoMx = "<?php echo $registro['subtotalDestinoMx']; ?>";
            var impuestosDestinoUsd = "<?php echo $registro['impuestosDestinoUsd']; ?>";
            var impuestosDestinoMx = "<?php echo $registro['impuestosDestinoMx']; ?>";
            var totalDestinoUsd = "<?php echo $registro['totalDestinoUsd']; ?>";
            var totalDestinoMx = "<?php echo $registro['totalDestinoMx']; ?>";

            var lugarDestino = "<?php echo $registro['lugarDestino']; ?>";
            var valorTotalFlete = "<?php echo $registro['valorTotalFlete']; ?>";

            window.onload = function() {


                document.getElementById("cliente").value = idCliente;
                obtenerDetalleCliente(idCliente);
                document.getElementById("origen").value = idOrigen;
                obtenerDetalleOrigen(idOrigen);
                document.getElementById("aduana").value = idAduana;
                obtenerDetalleAduana(idAduana);
                document.getElementById("destino").value = idDestino;
                obtenerDetalleDestino(idDestino);

                document.getElementById("ft3Total").value = totalFt3;
                document.getElementById("m3Total").value = totalM3;
                document.getElementById("pesoMercanciaKgs").value = pesoMercanciaKgs;
                document.getElementById("pesoMercanciaLbs").value = pesoMercanciaLbs;
                document.getElementById("valorMercancia").value = valorMercancia;
                document.getElementById("valorComercial").value = valorComercial;
                document.getElementById("totalMXN").value = totalIncrementableMx;
                document.getElementById("totalUSD").value = totalIncrementableUsd;
                document.querySelector('input[name="subtotalFlete"]').value = subtotalFlete;
                document.querySelector('input[name="impuestosFlete"]').value = impuestosFlete;
                document.querySelector('input[name="retencionFlete"]').value = retencionFlete;
                document.querySelector('input[name="totalOrigenAll"]').value = totalOrigenAll;
                document.querySelector('input[name="subtotalDestinoUsd"]').value = subtotalDestinoUsd;
                document.querySelector('input[name="subtotalDestinoMx"]').value = subtotalDestinoMx;
                document.querySelector('input[name="impuestosDestinoUsd"]').value = impuestosDestinoUsd;
                document.querySelector('input[name="impuestosDestinoMx"]').value = impuestosDestinoMx;
                document.querySelector('input[name="totalDestinoUsd"]').value = totalDestinoUsd;
                document.querySelector('input[name="totalDestinoMx"]').value = totalDestinoMx;
                document.querySelector('input[name="lugarDestino"]').value = lugarDestino;
                document.querySelector('input[name="valorTotalFlete"]').value = valorTotalFlete;

                setTimeout(actualizarTotales, 100);
            };

            function obtenerDetalleCliente(idCliente) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleCliente").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idCliente=" + idCliente);
            }

            function obtenerDetalleOrigen(idOrigen) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleOrigen").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idOrigen=" + idOrigen);
            }

            function obtenerDetalleAduana(idAduana) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleAduana").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idAduana=" + idAduana);
            }

            function obtenerDetalleDestino(idDestino) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleDestino").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idDestino=" + idDestino);
            }


            // Select de cliente
            document.getElementById("cliente").addEventListener("change", function() {
                var idCliente = this.value;

                if (idCliente) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleCliente").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idCliente=" + idCliente);
                } else {
                    document.getElementById("detalleCliente").innerHTML = "";
                }
            });

            // Select de origen
            document.getElementById("origen").addEventListener("change", function() {
                var idOrigen = this.value;

                if (idOrigen) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleOrigen").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idOrigen=" + idOrigen);
                } else {
                    document.getElementById("detalleOrigen").innerHTML = "";
                }
            });

            // Select de Aduana destino
            document.getElementById("aduana").addEventListener("change", function() {
                var idAduana = this.value;

                if (idAduana) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleAduana").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idAduana=" + idAduana);
                } else {
                    document.getElementById("detalleAduana").innerHTML = "";
                }
            });

            // Select de Destino final
            document.getElementById("destino").addEventListener("change", function() {
                var idDestino = this.value;

                if (idDestino) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleDestino").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idDestino=" + idDestino);
                } else {
                    document.getElementById("detalleDestino").innerHTML = "";
                }
            });

            function agregarFila() {
                const tabla = document.getElementById("miTablaCotizacion");
                const nuevaFila = tabla.insertRow();
                nuevaFila.innerHTML = `
                <td>
                <input style="width: 60px;" class="form-control mb-3" type="text" name="cantidad[]" oninput="convertToCmAndCalculateVolume(this)">
                <td>
                    <input class="form-control mb-1" type="text" name="unidadMedida[]">
                </td>
                <td>
                    <input class="form-control" type="text"  name="descripcion[]">
                </td>
                <td>
                    <div class="row">
                        <div class="col-6">
                            <input class="form-control mb-1" type="text" name="largoPlg[]" placeholder="Largo (pulgadas)" oninput="convertToCmAndCalculateVolume(this)">
                            <input class="form-control mb-1" type="text" name="anchoPlg[]" placeholder="Ancho (pulgadas)" oninput="convertToCmAndCalculateVolume(this)">
                            <input class="form-control" type="text" name="altoPlg[]" placeholder="Alto (pulgadas)" oninput="convertToCmAndCalculateVolume(this)">
                            <p class="mb-3">pulgadas</p>
                            <input class="form-control" type="text" name="piesCubicos[]" placeholder="pies cúbicos" readonly>
                            <p>ft³</p>
                        </div>
                        <div class="col-6">
                            <input class="form-control mb-1" type="text" id="altoFilaCm" name="largoCm[]" placeholder="Largo (mts)" oninput="convertToInchesAndCalculateVolume(this)">
                            <input class="form-control mb-1" type="text" id="anchoFilaCm" name="anchoCm[]" placeholder="Ancho (mts)" oninput="convertToInchesAndCalculateVolume(this)">
                            <input class="form-control" type="text" id="profundidadFilaCm" name="altoCm[]" placeholder="Alto (mts)" oninput="convertToInchesAndCalculateVolume(this)">
                            <p class="mb-3">mts</p>
                            <input class="form-control" type="text" name="metrosCubicos[]" placeholder="metros cúbicos" readonly>
                            <p>m³</p>
                        </div>
                    </div>
                </td>
                <td>
                    <input class="form-control mb-1" type="text" placeholder="lbs" name="libras[]" id="pesoFilaMercanciaLbs" oninput="convertToKg(this); actualizarTotales();">
                    <input class="form-control" type="text" placeholder="kgs" name="kilogramos[]" id="pesoFilaMercanciaKgs" oninput="convertToLbs(this); actualizarTotales();">
                </td>
                <td><input class="form-control" id="valorFilaMercancia" type="text" name="valorFactura[]" placeholder="Precio total" oninput="actualizarTotales()"></td>
            `;
                actualizarTotales(); // Actualiza los totales al agregar la fila
            }

            function eliminarUltimaFila() {
                const tabla = document.getElementById("miTablaCotizacion");
                if (tabla.rows.length > 1) {
                    tabla.deleteRow(tabla.rows.length - 1);
                } else {
                    alert("No hay más filas para eliminar.");
                }
                actualizarTotales(); // Actualiza los totales después de eliminar la fila
            }

            // Calcular millas a Km de origen a aduana destino
            function convertirAMetros() {
                var millas = document.getElementById("millas").value;
                var kilometros = millas * 1.60934;
                document.getElementById("km").value = kilometros.toFixed(2);
            }

            function convertirAMillas() {
                var kilometros = document.getElementById("km").value;
                var millas = kilometros / 1.60934;
                document.getElementById("millas").value = millas.toFixed(2);
            }

            // Calcular millas a Km de origen a aduana final
            function convertirAMetrosDos() {
                var millas = document.getElementById("milla").value;
                var kilometros = millas * 1.60934;
                document.getElementById("kms").value = kilometros.toFixed(2);
            }

            function convertirAMillasDos() {
                var kilometros = document.getElementById("kms").value;
                var millas = kilometros / 1.60934;
                document.getElementById("milla").value = millas.toFixed(2);
            }

            function convertToCmAndCalculateVolume(element) {
                const row = element.closest('tr');
                const height = parseFloat(row.querySelector("[placeholder='Largo (pulgadas)']").value) || 0;
                const width = parseFloat(row.querySelector("[placeholder='Ancho (pulgadas)']").value) || 0;
                const deep = parseFloat(row.querySelector("[placeholder='Alto (pulgadas)']").value) || 0;
                const cantidad = parseFloat(row.querySelector("input[name='cantidad[]']").value) || 1;

                // Convertir pulgadas a centímetros y calcular volumen
                row.querySelector("[placeholder='Largo (mts)']").value = (height * 0.0254).toFixed(2);
                row.querySelector("[placeholder='Ancho (mts)']").value = (width * 0.0254).toFixed(2);
                row.querySelector("[placeholder='Alto (mts)']").value = (deep * 0.0254).toFixed(2);
                const altura = height * 0.0254;
                const ancho = width * 0.0254;
                const profundidad = deep * 0.0254;
                const volumeFt3 = ((height * 0.08333) * (width * 0.08333) * (deep * 0.08333)) * cantidad;
                row.querySelector("[placeholder='pies cúbicos']").value = volumeFt3.toFixed(2);
                const volumeM3 = volumeFt3 * 0.0283168;
                row.querySelector("[placeholder='metros cúbicos']").value = volumeM3.toFixed(2);

                const volumetrico = (altura * ancho * profundidad) * cantidad / 0.006;

                // Guardar el valor en un campo oculto o atributo data
                row.dataset.volumetrico = volumetrico;

                // Calcular total
                let totalVolumetrico = 0;
                const filas = document.querySelectorAll("#miTablaCotizacion tr");
                filas.forEach(f => {
                    const v = parseFloat(f.dataset.volumetrico) || 0;
                    totalVolumetrico += v;
                });

                // Mostrar total
                const campoTotal = document.getElementById("pesoVolumetrico");
                if (campoTotal) {
                    campoTotal.value = totalVolumetrico.toFixed(2);
                }

                actualizarTotales(); // Actualiza los totales después de convertir
                actualizarPesoTarifario();
            }

            function convertToInchesAndCalculateVolume(element) {
                const row = element.closest('tr');
                const altura = parseFloat(row.querySelector("[placeholder='Largo (mts)']").value) || 0;
                const ancho = parseFloat(row.querySelector("[placeholder='Ancho (mts)']").value) || 0;
                const profundidad = parseFloat(row.querySelector("[placeholder='Alto (mts)']").value) || 0;
                const cantidad = parseFloat(row.querySelector("input[name='cantidad[]']").value) || 1;
                // Convertir centímetros a pulgadas y calcular volumen
                row.querySelector("[placeholder='Largo (pulgadas)']").value = (altura / 0.0254).toFixed(2);
                row.querySelector("[placeholder='Ancho (pulgadas)']").value = (ancho / 0.0254).toFixed(2);
                row.querySelector("[placeholder='Alto (pulgadas)']").value = (profundidad / 0.0254).toFixed(2);
                const height = altura / 0.0254;
                const width = ancho / 0.0254;
                const deep = profundidad / 0.0254;
                const volumeFt3 = (height * width * deep) / 1728;
                row.querySelector("[placeholder='pies cúbicos']").value = volumeFt3.toFixed(2);
                const volumeM3 = volumeFt3 * 0.0283168;
                row.querySelector("[placeholder='metros cúbicos']").value = volumeM3.toFixed(2);

                const volumetrico = (altura * ancho * profundidad) * cantidad / 0.006;

                // Guardar el valor en un campo oculto o atributo data
                row.dataset.volumetrico = volumetrico;

                // Calcular total
                let totalVolumetrico = 0;
                const filas = document.querySelectorAll("#miTablaCotizacion tr");
                filas.forEach(f => {
                    const v = parseFloat(f.dataset.volumetrico) || 0;
                    totalVolumetrico += v;
                });

                // Mostrar total
                const campoTotal = document.getElementById("pesoVolumetrico");
                if (campoTotal) {
                    campoTotal.value = totalVolumetrico.toFixed(2);
                }

                actualizarTotales(); // Actualiza los totales después de convertir
            }

            function convertToKg(element) {
                const row = element.closest('tr');
                const lbs = parseFloat(row.querySelector("[placeholder='lbs']").value) || 0;
                const kg = lbs * 0.453592;
                row.querySelector("[placeholder='kgs']").value = kg.toFixed(2);
            }

            function convertToLbs(element) {
                const row = element.closest('tr');
                const kg = parseFloat(row.querySelector("[placeholder='kgs']").value) || 0;
                const lbs = kg / 0.453592;
                row.querySelector("[placeholder='lbs']").value = lbs.toFixed(2);
            }

            function actualizarTotales() {
                const tabla = document.getElementById("miTablaCotizacion");
                let totalLbs = 0;
                let totalKgs = 0;
                let totalValor = 0;
                let totalFt3 = 0;
                let totalM3 = 0;

                for (let i = 1; i < tabla.rows.length; i++) {
                    const fila = tabla.rows[i];

                    // Obtener elementos de la fila
                    const pesoLbsInput = fila.querySelector("input[name='libras[]']");
                    const pesoKgsInput = fila.querySelector("input[name='kilogramos[]']");
                    const valorInput = fila.querySelector("input[id='valorFilaMercancia']");
                    const ft3Input = fila.querySelector("[placeholder='pies cúbicos']");
                    const m3Input = fila.querySelector("[placeholder='metros cúbicos']");

                    // Obtener valores de la fila
                    let pesoLbs = parseFloat(pesoLbsInput.value) || 0;
                    let pesoKgs = parseFloat(pesoKgsInput.value) || 0;
                    let valor = parseFloat(valorInput.value) || 0;
                    let ft3 = parseFloat(ft3Input.value) || 0;
                    let m3 = parseFloat(m3Input.value) || 0;

                    // Sumar totales correctamente (solo una vez)
                    totalLbs += pesoLbs;
                    totalKgs += pesoKgs;
                    totalValor += valor;
                    totalFt3 += ft3;
                    totalM3 += m3;

                }

                // Mostrar los totales en los inputs correspondientes
                document.getElementById("pesoMercanciaLbs").value = totalLbs.toFixed(2);
                document.getElementById("pesoMercanciaKgs").value = totalKgs.toFixed(2);
                document.getElementById("valorMercancia").value = totalValor.toFixed(2);
                document.getElementById("ft3Total").value = totalFt3.toFixed(2);
                document.getElementById("m3Total").value = totalM3.toFixed(2);

                const filas = document.querySelectorAll("#miTablaCotizacion tr");

                filas.forEach(fila => {
                    const v = parseFloat(fila.dataset.volumetrico) || 0;
                    totalVolumetrico += v;
                });

                const campoTotal = document.getElementById("pesoVolumetrico");
                if (campoTotal) {
                    campoTotal.value = totalVolumetrico.toFixed(2);
                }

                // Actualizar otros cálculos
                actualizarValorComercial();
                actualizarValoresUSD_MXN();
                updateTotal();
            }

            function actualizarValorComercial() {
                const valorMercancia = parseFloat(document.getElementById("valorMercancia").value) || 0;
                const valorMoneda = parseFloat(document.getElementById("valorMoneda").value) || 0;

                // Multiplicar el valor de la mercancia por el valor de la moneda
                const valorComercial = valorMercancia * valorMoneda;

                // Mostrar el valor comercial en el input correspondiente
                document.getElementById("valorComercial").value = valorComercial.toFixed(2);
            }


            const tableBody = document.querySelector("#incrementableTable tbody");
            const totalUSD = document.getElementById("totalUSD");
            const totalMXN = document.getElementById("totalMXN");
            const addRowButton = document.getElementById("addRowButton");
            const removeServiceButton = document.getElementById("removeServiceButton");
            const removeRowButton = document.getElementById("removeRowButton");

            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("removeRowButton").addEventListener("click", removeRow);
                document.getElementById("valorMoneda").addEventListener("input", actualizarValoresUSD_MXN);

                // Escuchar cambios en los campos de input
                document.getElementById("origenTable").addEventListener("input", function(e) {
                    if (e.target.matches(".amOrigen") || e.target.id === "pesoTarifario") {
                        actualizarTotalesOrigen();
                    }
                });

                observarCambio(); // Iniciar la observación del cambio de valor
                convertirNumeroATexto();
            });

            // Función para agregar una nueva fila de incrementables
            function agregarIncrementable() {
                var tabla = document.getElementById("incrementableTable").getElementsByTagName("tbody")[0];
                var nuevaFila = document.createElement("tr");

                var uniqueId = Date.now(); // Generar un ID único para vincular con el gasto
                nuevaFila.innerHTML = `
        <td>
            <select class="form-select conceptoIncrementable" name="incrementable[]" data-id="${uniqueId}" onchange="actualizarConceptoGasto(this)">
                <option value="" disabled selected>Selecciona una opción</option>
                <?php
                $query = "SELECT * FROM tipoincrementable WHERE tipo = 'lcl'";
                $result = mysqli_query($con, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($registro = mysqli_fetch_assoc($result)) {
                        $nombre = $registro['incrementable'];
                        echo "<option value='$nombre'>" . $nombre . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td><input type="number" name="incrementableUsd[]" class="form-control usd-input" data-id="${uniqueId}" value="" oninput="updateRow(this); sincronizarGasto(this);"></td>
        <td><input type="text" name="incrementableMx[]" class="form-control mxn-input" value="0" readonly></td>
    `;

                tabla.appendChild(nuevaFila);
                setTimeout(() => nuevoGasto(uniqueId), 10);
                updateTotal();
            }


            // Función para actualizar el valor en MXN cuando cambia USD
            function updateRow(input) {
                const valorCambio = parseFloat(document.getElementById("valorMoneda").value) || 0;
                const dolarValue = parseFloat(input.value) || 0;

                // Encuentra la fila actual y el campo de salida (MXN)
                const row = input.closest("tr");
                const mxnOutput = row.querySelector(".mxn-input");

                // Calcula y actualiza el valor en MXN
                mxnOutput.value = (dolarValue * valorCambio).toFixed(2);

                // Actualiza los totales
                updateTotal();
            }

            // Función para actualizar todos los valores USD-MXN cuando cambia el tipo de cambio
            function actualizarValoresUSD_MXN() {
                const valorCambio = parseFloat(document.getElementById("valorMoneda").value) || 0;
                const dolarInputs = document.querySelectorAll(".usd-input");

                dolarInputs.forEach((input) => {
                    const row = input.closest("tr");
                    const mxnOutput = row.querySelector(".mxn-input");
                    const dolarValue = parseFloat(input.value) || 0;
                    mxnOutput.value = (dolarValue * valorCambio).toFixed(2);
                });

                updateTotal();
            }

            // Función para actualizar los totales en la tabla
            function updateTotal() {
                let totalUSDValue = 0;
                let totalMXNValue = 0;

                document.querySelectorAll(".usd-input").forEach(input => {
                    totalUSDValue += parseFloat(input.value) || 0;
                });

                document.querySelectorAll(".mxn-input").forEach(input => {
                    totalMXNValue += parseFloat(input.value) || 0;
                });

                // Actualizar los inputs de totales
                document.getElementById("totalUSD").value = totalUSDValue.toFixed(2);
                document.getElementById("totalMXN").value = totalMXNValue.toFixed(2);
            }

            // Función para actualizar el concepto del gasto al seleccionar un incrementable
            function actualizarConceptoGasto(selectElement) {
                var id = selectElement.getAttribute("data-id"); // Obtener el data-id de la fila seleccionada
                var conceptoInput = document.querySelector(`.conceptoGasto[data-id="${id}"]`); // Buscar el input correcto en gastos

                if (conceptoInput) {
                    conceptoInput.value = selectElement.value; // Asigna el nuevo valor del select al campo conceptoGasto correspondiente
                }
            }


            // Función para actualizar el monto del gasto cuando cambia el valor en USD
            function actualizarMontoGasto(inputElement) {
                const row = inputElement.closest("tr"); // Encuentra la fila actual
                const index = Array.from(document.querySelectorAll(".usd-input")).indexOf(inputElement);

                if (index !== -1) {
                    const montoGastoInputs = document.querySelectorAll(".montoGasto");

                    if (montoGastoInputs[index]) {
                        montoGastoInputs[index].value = inputElement.value; // Pasa el valor de USD a montoGasto
                    } else {
                        console.warn("No se encontró el campo correspondiente en montoGasto[]");
                    }
                }
            }


            // Función para agregar una nueva fila en la tabla de gastos
            function nuevoGasto(incrementableId = null) {
                var tabla = document.getElementById("tablaGasto").getElementsByTagName("tbody")[0];
                var filas = tabla.getElementsByTagName("tr");
                var filaSubtotal = null;

                for (var i = 0; i < filas.length; i++) {
                    var celdas = filas[i].getElementsByTagName("td");
                    if (celdas.length > 1 && celdas[0].innerText.trim() === "Subtotal") {
                        filaSubtotal = filas[i];
                        break;
                    }
                }

                if (filaSubtotal) {
                    var uniqueId = incrementableId || Date.now(); // Si viene de un incrementable, usa su ID

                    var nuevaFila = document.createElement("tr");
                    nuevaFila.setAttribute("data-id", uniqueId); // Asignar el mismo ID

                    nuevaFila.innerHTML = `
            <td><input type="text" class="form-control conceptoGasto" name="conceptoGasto[]" data-id="${uniqueId}"></td>
            <td>
                <div class="form-check float-end">
                    <input class="form-check-input" type="checkbox" name="ivaGasto[]" checked>
                    <label class="form-check-label">IVA 16%</label>
                </div>
            </td>
            <td class="text-end">
                <input type="text" class="form-control montoGasto" name="montoGasto[]" data-id="${uniqueId}" oninput="actualizarSubtotal(); sincronizarIncrementable(this);">
            </td>
            <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
        `;

                    tabla.insertBefore(nuevaFila, filaSubtotal);
                }
            }

            function sincronizarGasto(input) {
                var id = input.getAttribute("data-id");
                var gastoInput = document.querySelector(`.montoGasto[data-id="${id}"]`);

                if (gastoInput) {
                    gastoInput.value = input.value; // Asigna el mismo valor al campo de gasto correspondiente
                }
            }

            function sincronizarIncrementable(input) {
                var id = input.getAttribute("data-id");
                var incrementableInput = document.querySelector(`.usd-input[data-id="${id}"]`);

                if (incrementableInput) {
                    incrementableInput.value = input.value; // Asigna el mismo valor al campo de incrementable correspondiente
                }
            }



            // Función para eliminar una fila de la tabla de gastos
            function eliminarFila(boton) {
                var fila = boton.closest("tr");
                var conceptoGasto = fila.querySelector(".conceptoGasto").value; // Obtener el concepto del gasto
                fila.remove(); // Eliminar la fila del gasto

                eliminarIncrementable(conceptoGasto); // Llamar a la función para eliminar el incrementable correspondiente
                actualizarSubtotal(); // Actualizar subtotal después de eliminar la fila

            }

            function eliminarIncrementable(conceptoGasto) {
                const tablaIncrementables = document.getElementById("incrementableTable").getElementsByTagName("tbody")[0];
                const filasIncrementables = tablaIncrementables.getElementsByTagName("tr");

                for (let i = 0; i < filasIncrementables.length; i++) {
                    const filaIncrementable = filasIncrementables[i];
                    const conceptoIncrementable = filaIncrementable.querySelector(".conceptoIncrementable").value;

                    // Si el concepto del incrementable coincide con el concepto del gasto, lo eliminamos
                    if (conceptoIncrementable === conceptoGasto) {
                        filaIncrementable.remove();
                        break; // Salir del bucle después de eliminar la fila correspondiente
                    }
                }
                updateTotal();
            }

            function removeServiceRow() {
                const tablaServicio = document.getElementById("servicioTable").getElementsByTagName("tbody")[0];
                const filasServicios = tablaServicio.getElementsByTagName("tr");

                if (filasServicios.length > 0) {
                    const filaAEliminar = filasServicios[filasServicios.length - 1];
                    filaAEliminar.remove();

                } else {
                    alert("No hay más filas para eliminar.");
                }
            }

            function removeRow() {
                const tablaIncrementables = document.getElementById("incrementableTable").getElementsByTagName("tbody")[0];
                const filasIncrementables = tablaIncrementables.getElementsByTagName("tr");

                if (filasIncrementables.length > 0) {
                    // Obtener la última fila de incrementables
                    const filaAEliminar = filasIncrementables[filasIncrementables.length - 1];

                    // Obtener el concepto del incrementable que se va a eliminar
                    const conceptoIncrementable = filaAEliminar.querySelector(".conceptoIncrementable").value;

                    // Eliminar la fila de la tabla de incrementables
                    filaAEliminar.remove();

                    // Buscar y eliminar la fila correspondiente en la tabla de gastos
                    eliminarFilaGasto(conceptoIncrementable);

                    // Actualizar totales
                    updateTotal();
                } else {
                    alert("No hay más filas para eliminar.");
                }
            }

            function eliminarFilaGasto(concepto) {
                const tablaGasto = document.getElementById("tablaGasto").getElementsByTagName("tbody")[0];
                const filasGasto = tablaGasto.getElementsByTagName("tr");

                for (let i = 0; i < filasGasto.length; i++) {
                    let inputConcepto = filasGasto[i].querySelector("input[name='conceptoGasto[]']");
                    if (inputConcepto && inputConcepto.value.trim() === concepto.trim()) {
                        filasGasto[i].remove();
                        break; // Salir del bucle después de eliminar la fila correspondiente
                    }
                }
            }

            function numeroALetras(num) {
                const unidades = ["", "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE"];
                const especiales = ["DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE"];
                const decenas = ["", "", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA", "NOVENTA"];
                const centenas = ["", "CIENTO", "DOSCIENTOS", "TRESCIENTOS", "CUATROCIENTOS", "QUINIENTOS", "SEISCIENTOS", "SETECIENTOS", "OCHOCIENTOS", "NOVECIENTOS"];

                function convertir(n) {
                    if (n === 0) return "CERO";
                    if (n === 100) return "CIEN";
                    if (n < 10) return unidades[n];
                    if (n < 20) return especiales[n - 10];
                    if (n < 30) return "VEINTI" + unidades[n % 10];
                    if (n < 100) return decenas[Math.floor(n / 10)] + (n % 10 !== 0 ? " Y " + unidades[n % 10] : "");
                    if (n < 1000) return centenas[Math.floor(n / 100)] + (n % 100 !== 0 ? " " + convertir(n % 100) : "");
                    if (n < 1000000) return (n < 2000 ? "MIL" : convertir(Math.floor(n / 1000)) + " MIL") + (n % 1000 !== 0 ? " " + convertir(n % 1000) : "");
                    if (n < 1000000000) return convertir(Math.floor(n / 1000000)) + " MILLONES" + (n % 1000000 !== 0 ? " " + convertir(n % 1000000) : "");
                    return "NÚMERO DEMASIADO GRANDE";
                }

                return convertir(num);
            }

            function convertirNumeroATexto() {
                let numeroInput = document.getElementById("totalCotizacionNumero");
                let textoInput = document.getElementById("totalCotizacionTexto");

                let numero = parseFloat(numeroInput.value.replace(/[^0-9.]/g, '')) || 0;
                let parteEntera = Math.floor(numero);
                let centavos = Math.round((numero - parteEntera) * 100);

                let texto = numeroALetras(parteEntera) + " DÓLARES";
                if (centavos > 0) {
                    texto += " CON " + numeroALetras(centavos) + " CENTAVOS";
                }
                textoInput.value = texto;
            }

            function observarCambio() {
                let numeroInput = document.getElementById("totalCotizacionNumero");
                let ultimoValor = numeroInput.value;

                setInterval(() => {
                    if (numeroInput.value !== ultimoValor) {
                        ultimoValor = numeroInput.value;
                        convertirNumeroATexto();
                    }
                }, 500); // Verifica cada 500ms si el valor cambió
            }

            function agregarFilaDespuesDeAMS() {
                const filaAMS = document.getElementById("filaAmsFee");
                const nuevaFila = document.createElement("tr");

                nuevaFila.innerHTML = `
        <td><input type="text" style="min-width: 100%;" class="form-control" name="gastosOrigen[]"></td>
        <td><input type="text" name="euros[]" class="euros form-control" oninput="actualizarTotalesOrigen();"></td>
        <td><input type="text" name="equivalenciaOrigen[]" class="equivalencia form-control" readonly></td>
        <td><input type="text" name="usdOrigen[]" class="usOrigen form-control" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFilaOrigen(this)"><i class="bi bi-trash3"></i></button></td>
    `;

                filaAMS.parentNode.insertBefore(nuevaFila, filaAMS.nextSibling);
            }

            function eliminarFilaOrigen(boton) {
                const fila = boton.closest("tr");
                fila.remove();

                actualizarTotalesOrigen();
                actualizarSubtotal();
            }


            function agregarFilaDespuesDeAMSDestino() {
                const filaAMS = document.getElementById("filaAmsFeeDestino");
                const nuevaFila = document.createElement("tr");

                nuevaFila.innerHTML = `
       <td><input type="text" style="min-width: 100%;" name="gastoDestino[]" class="form-control"></td>
        <td><input type="number" name="usdDestino[]" class="dolarInputs form-control" value="" oninput="updateRowDestinySpents(this)"></td>
        <td><input type="text" name="mxnDestino[]" class="mxnOutputs form-control" oninput="updateFromMXN(this)"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFilaDestino(this)"><i class="bi bi-trash3"></i></button></td>
    `;

                filaAMS.parentNode.insertBefore(nuevaFila, filaAMS.nextSibling);
            }

            function eliminarFilaDestino(boton) {
                const fila = boton.closest("tr");
                fila.remove();
                calcularTotalesDestino();
                actualizarSubtotal();
            }

            function updateRowDestinySpents(input) {
                const row = input.closest("tr");
                const usdInput = row.querySelector(".dolarInputs");
                const mxnOutput = row.querySelector(".mxnOutputs");
                const tipoCambio = parseFloat(document.getElementById("valorMoneda").value) || 0;

                const usdValue = parseFloat(usdInput.value) || 0;
                mxnOutput.value = tipoCambio > 0 ? (usdValue * tipoCambio).toFixed(2) : '';

                calcularTotalesDestino();
                actualizarValorTotalFlete();
            }

            function updateFromMXN(input) {
                const row = input.closest("tr");
                const usdInput = row.querySelector(".dolarInputs");
                const mxnOutput = row.querySelector(".mxnOutputs");
                const tipoCambio = parseFloat(document.getElementById("valorMoneda").value) || 0;

                const mxnValue = parseFloat(mxnOutput.value) || 0;
                usdInput.value = tipoCambio > 0 ? (mxnValue / tipoCambio).toFixed(2) : '';

                calcularTotalesDestino();
            }

            function calcularTotalesDestino() {
                const usdInputs = document.querySelectorAll(".dolarInputs");
                const mxnOutputs = document.querySelectorAll(".mxnOutputs");

                let subtotalUsd = 0;
                let subtotalMx = 0;

                usdInputs.forEach(input => {
                    subtotalUsd += parseFloat(input.value) || 0;
                });

                mxnOutputs.forEach(input => {
                    subtotalMx += parseFloat(input.value) || 0;
                });

                const ivaChecked = document.querySelector("input[name='ivaDestino']").checked;
                const impuestosUsd = ivaChecked ? subtotalUsd * 0.16 : 0;
                const impuestosMx = ivaChecked ? subtotalMx * 0.16 : 0;

                document.querySelector("input[name='subtotalDestinoUsd']").value = subtotalUsd.toFixed(2);
                document.querySelector("input[name='subtotalDestinoMx']").value = subtotalMx.toFixed(2);
                document.querySelector("input[name='impuestosDestinoUsd']").value = impuestosUsd.toFixed(2);
                document.querySelector("input[name='impuestosDestinoMx']").value = impuestosMx.toFixed(2);
                document.querySelector("input[name='totalDestinoUsd']").value = (subtotalUsd + impuestosUsd).toFixed(2);
                document.querySelector("input[name='totalDestinoMx']").value = (subtotalMx + impuestosMx).toFixed(2);

                actualizarValorTotalFlete();

            }

            // Recalcula totales cuando se cambia el checkbox o el tipo de cambio
            document.addEventListener("DOMContentLoaded", function() {
                const ivaCheckbox = document.querySelector("input[name='ivaDestino']");
                const tipoCambioInput = document.getElementById("valorMoneda");

                ivaCheckbox.addEventListener("change", calcularTotalesDestino);
                tipoCambioInput.addEventListener("input", () => {
                    document.querySelectorAll(".dolarInputs").forEach(input => updateRowDestinySpents(input));
                });
            });

            function actualizarValorTotalFlete() {
                const totalDestino = parseFloat(document.getElementById("totalDestinoUsd")?.value || 0);
                const totalOrigenAll = parseFloat(document.getElementById("totalOrigenAll")?.value || 0);
                const totalFlete = totalDestino + totalOrigenAll;

                const totalFleteField = document.getElementById("valorTotalFlete");
                if (totalFleteField) {
                    totalFleteField.value = totalFlete.toFixed(2);
                }
            }

            function actualizarTotalesOrigen() {
                // Obtener valor global de equivalencia
                const equivalenciaGlobal = parseFloat(document.getElementById("equivalencia").value) || 0;

                // Inicializar suma total
                let sumaTotal = 0;

                // Iterar sobre cada fila de la tabla
                document.querySelectorAll("#origenTable tr").forEach(row => {
                    const eurosInput = row.querySelector(".euros");
                    const equivalenciaInput = row.querySelector(".equivalencia");
                    const usdOrigenInput = row.querySelector(".usOrigen");

                    // Si la fila contiene los elementos necesarios
                    if (eurosInput && equivalenciaInput && usdOrigenInput) {
                        const euros = parseFloat(eurosInput.value) || 0;

                        // Asignar la equivalencia global si aún no está
                        equivalenciaInput.value = equivalenciaGlobal;

                        const equivalencia = parseFloat(equivalenciaInput.value) || 0;
                        const totalUSD = euros * equivalencia;

                        usdOrigenInput.value = totalUSD.toFixed(2);
                        sumaTotal += totalUSD;
                    }
                });

                // Actualizar el campo total
                document.getElementById("totalOrigenAll").value = sumaTotal.toFixed(2);

                actualizarValorTotalFlete();
            }

            document.getElementById("equivalencia").addEventListener("input", actualizarTotalesOrigen);
        </script>
</body>

</html>