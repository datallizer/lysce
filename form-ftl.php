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
    <title>FTL | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="sb-nav-fixed">
    <?php include 'sidenav.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container-fluid p-5">
                <form action="codeftl.php" method="POST" class="row justify-content-evenly">
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
                        <input class="form-control" type="text" name="identificador" placeholder="Folio">
                        <p style="margin: 5px;">Aguascalientes, Ags a</p>
                        <input class="form-control" type="text" name="fecha" id="expedicion" value="">
                    </div>
                    <div class="col-12 text-center bg-warning p-1" style="border: 1px solid #666666;border-bottom:0px;">
                        <select class="form-select bg-warning" name="tipoFtl" id="tipoFtlSelect" required onchange="agregarTipoServicio()">
                            <option value="" disabled selected>Selecciona una opción</option>
                            <?php
                            $query = "SELECT * FROM tiposervicio WHERE tipoServicio = 'ftl'";
                            $result = mysqli_query($con, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($registro = mysqli_fetch_assoc($result)) {
                                    $nombre = $registro['nombreServicio'];
                                    echo "<option value='$nombre'>" . $nombre . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 p-3" style="border: 1px solid #666666; border-bottom:0px;">
                        <p class="mb-1"><b>Cliente</b></p>
                        <select class="form-select mb-3" name="idCliente" required id="cliente">
                            <option value="" disabled selected>Selecciona una opción</option>
                            <?php
                            $query = "SELECT * FROM clientes WHERE estatus = 1 AND tipo = 'Cliente' ORDER BY cliente ASC";
                            $result = mysqli_query($con, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($registro = mysqli_fetch_assoc($result)) {
                                    $nombre = $registro['cliente'];
                                    $idCliente = $registro['id'];
                                    echo "<option value='$idCliente'>" . $nombre . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <p id="detalleCliente"></p>
                    </div>
                    <div class="col-4 p-3" style="border: 1px solid #666666;">
                        <p class="mb-1"><b>Origen</b></p>
                        <select class="form-select" name="idOrigen" required id="origen" disabled>
                            <option value="" disabled selected>Selecciona primero un cliente</option>
                        </select>
                        <p id="detalleOrigen"></p>
                    </div>
                    <div class="col-4 p-3" style="border: 1px solid #666666;">
                        <p class="mb-1"><b>Destino en frontera</b></p>
                        <select class="form-select" name="idAduana" required id="aduana" disabled>
                            <option value="" disabled selected>Selecciona primero un cliente</option>
                        </select>

                        <p id="detalleAduana"></p>
                    </div>
                    <div class="col-4 p-3" style="border: 1px solid #666666;">
                        <p class="mb-1"><b>Destino Final</b></p>
                        <select class="form-select" name="idDestino" required id="destino" disabled>
                            <option value="" disabled selected>Selecciona primero un cliente</option>
                        </select>
                        <p id="detalleDestino"></p>
                    </div>

                    <div class="col-7 mt-3 mb-3">
                        <div class="row justify-content-start">
                            <div class="col-8">
                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Distancia:</b>
                                    <input name="distanciaOrigenDestinoMillas" class="form-control" style="width: 90px; display: inline-block;" type="text" id="millas" oninput="convertirAMetros()">
                                    millas
                                    <input name="distanciaOrigenDestinoKms" class="form-control" style="width: 90px; display: inline-block;" type="text" id="km" oninput="convertirAMillas()"> Kms
                                </p><br>

                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Tiempo / Recorrido:</b>
                                    <input name="tiempoRecorridoOrigenDestino" class="form-control" style="width: 110px; display: inline-block;" type="text" id="recorrido">
                                </p><br>

                                <!-- <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Operador:</b> -->
                                    <input name="servicio" class="form-control" style="width: 167px; display: inline-block;" type="hidden" id="servicio">
                                <!-- </p> -->
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
                            <input name="distanciaDestinoFinalMillas" class="form-control" style="width: 90px; display: inline-block;" type="text" id="milla" oninput="convertirAMetrosDos()">
                            millas
                            <input name="distanciaDestinoFinalKms" class="form-control" style="width: 90px; display: inline-block;" type="text" id="kms" oninput="convertirAMillasDos()"> Kms
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Tiempo / Recorrido:</b>
                            <input name="tiempoRecorridoDestinoFinal" class="form-control" style="width: 80px; display: inline-block;" type="text" id="recorrido">
                        </p><br>

                        <!-- <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Operador:</b> -->
                            <input name="operador" class="form-control" style="width: 167px; display: inline-block;" type="hidden" id="servicio">
                        <!-- </p> -->

                        <p style="display: inline-block;">
                            <b>Unidad:</b>
                            <input name="unidad" class="form-control" style="width: 230px; display: inline-block;" type="text" id="servicio" value="Servicio tráiler directo FTL">
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
                            </table>
                            <div class="text-center p-2">
                                <button class="btn btn-danger" type="button" onclick="eliminarUltimaFila()">-</button>
                                <button class="btn btn-secondary" type="button" onclick="agregarFila()">+</button>
                            </div>
                        </div>

                        <div class="row mt-3 mb-3">
                            <div class="col-3 text-center">
                                <p style="display: inline-block;margin-bottom: 5px;">
                                    Total bultos <input class="form-control" type="text" name="totalBultos" id="totalBultos" style="width: 80px; display: inline-block;" readonly>
                                </p>
                                <p style="display: inline-block;margin-bottom: 5px;">
                                    1 <input class="form-control" style="width: 80px; display: inline-block;" type="text" name="moneda" id="moneda" value="USD"> = <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="valorMoneda" name="valorMoneda" oninput="actualizarTotales()">
                                </p>
                            </div>

                            <div class="col-4">
                                <table class="text-end">
                                    <tr>
                                        <td>Peso total de la mercancía</td>
                                        <td><input class="form-control" style="width: 120px; display: inline-block;" type="text" id="pesoMercanciaLbs" name="pesoMercanciaLbs" readonly> lbs</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input class="form-control" style="width: 120px; display: inline-block;" type="text" id="pesoMercanciaKgs" name="pesoMercanciaKgs" readonly> kgs</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-5">
                                <table class="text-end w-100">
                                    <tr>
                                        <td>VALOR TOTAL DE LA MERCANCÍA USD</td>
                                        <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorMercancia" name="valorMercancia" readonly oninput="actualizarSubtotal();"></td>
                                    </tr>
                                    <tr>
                                        <td>VALOR TOTAL DE LA MERCANCÍA MXN</td>
                                        <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorComercial" name="valorComercial" readonly></td>
                                    </tr>
                                </table>
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

                                </tbody>
                            </table>
                            <div class="col-12 text-center p-2">
                                <button class="btn btn-danger" id="removeServiceButton" type="button">-</button>
                                <button class="btn btn-secondary" id="addServiceButton" type="button" onclick="agregarTipoServicio()">+</button>
                            </div>
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
                                    <tr>
                                        <td><input type="text" class="form-control" name="conceptoGasto[]" value="Cruce fronterizo"></td>
                                        <td>
                                            <div class="form-check float-end">
                                                <input class="form-check-input" type="checkbox" name="ivaGasto[]" id="flexCheck1">
                                                <label class="form-check-label" for="flexCheck1"> IVA 16% </label>
                                            </div>
                                        </td>
                                        <td class="text-end"><input type="text" class="form-control" name="montoGasto[]" oninput="actualizarSubtotal()"></td>
                                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="conceptoGasto[]" value="Flete en país destino"></td>
                                        <td>
                                            <div class="form-check float-end">
                                                <input class="form-check-input" type="checkbox" name="ivaGasto[]" id="flexCheck2" checked>
                                                <label class="form-check-label" for="flexCheck2"> IVA 16% </label>
                                            </div>
                                        </td>
                                        <td class="text-end"><input type="text" class="form-control" name="montoGasto[]" oninput="actualizarSubtotal()"></td>
                                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control" name="conceptoGasto[]" value="Seguro de tránsito de mercancía" readonly>
                                                </div>
                                                <div class="col-3">
                                                    <input type="text" class="form-control" name="porcentajeSeguro" value="0%" oninput="actualizarSubtotal();">
                                                </div>
                                                <p style="font-size: 11px;" class="text-end">Establezca el porcentaje en 0% para omitir el calculo de seguro*</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check float-end">
                                                <input class="form-check-input" type="checkbox" name="ivaGasto[]" id="flexCheck4" checked>
                                                <label class="form-check-label" for="flexCheck4"> IVA 16% </label>
                                            </div>
                                        </td>
                                        <td colspan="2" class="text-end">
                                            <input type="text" id="montoSeguro" class="form-control" name="montoGasto[]" oninput="actualizarSubtotal()" readonly>
                                        </td>
                                    </tr>
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
                                                <input class="form-check-input" type="checkbox" name="retencionFleteCheck" id="retencionCheck">
                                                <label class="form-check-label" for="retencionCheck"> Retención 4% </label>
                                            </div>
                                        </td>
                                        <td colspan="2"><input class="form-control" name="retencionFlete" type="text" value="0.00" readonly></td>
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

                            // Calcular el monto del seguro
                            let valorMercancia = parseFloat(document.querySelector('input[name="valorMercancia"]').value) || 0;
                            let porcentajeSeguroInput = document.querySelector('input[name="porcentajeSeguro"]').value;
                            let montoSeguroInput = document.querySelector('input[id="montoSeguro"]');

                            // Convertir porcentaje a decimal (ejemplo: "38%" -> 0.38)
                            let porcentajeSeguro = parseFloat(porcentajeSeguroInput.replace('%', '')) / 100;
                            let montoSeguro = valorMercancia * porcentajeSeguro;

                            // Si el porcentaje es 0%, el monto del seguro debe ser 0
                            if (porcentajeSeguro === 0) {
                                montoSeguro = 0;
                            } else if (montoSeguro < 120 && porcentajeSeguro > 0) {
                                // Si el monto calculado es menor que 120, se fija en 120
                                montoSeguro = 120;
                            }

                            montoSeguroInput.value = montoSeguro.toFixed(2);

                            // Actualizar el campo subtotalFlete con la suma
                            var subtotalFleteInput = document.querySelector("[name='subtotalFlete']");
                            if (subtotalFleteInput) {
                                subtotalFleteInput.value = sumaGastos.toFixed(2);
                            }

                            // Calcular el total de la cotización
                            let totalCotizacion = (subtotal + iva - retencion).toFixed(2);
                            document.querySelector('input[name="totalCotizacionNumero"]').value = totalCotizacion;
                        }

                        document.addEventListener("input", actualizarSubtotal);
                        document.addEventListener("change", actualizarSubtotal);
                    </script>






                    <table class="mt-3 bg-warning w-100" style="border: 1px solid #000000;padding:5px;">
                        <tr class="text-end">
                            <td style="border-right: 1px solid #000000;padding:5px;"><b>TOTAL USD</b></td>
                            <td style="width: 180px;">
                                <input class="form-control bg-warning" name="totalCotizacionNumero" id="totalCotizacionNumero" type="text" readonly>
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
                                    <textarea value="" class="form-control" name="observaciones" style="min-height: 200px;" id="observaciones">-Se recomienda servicio de seguro de transito de mercancías
-Precio válido por 30 días
-Solicitar equipo con 24 horas de anticipación
-Precio sujeto a cambio a base de disponibilidad
-La mercancía viaja por cuenta y riesgo del cliente 
-Costo del seguro .0038% sobre valor mercancías / mínimo $ 120.00 USD
</textarea>
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
                        <a href="ftl.php" class="btn btn-secondary m-1">Cancelar</a>
                        <button type="submit" class="btn btn-success m-1" name="save">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script>
        // function validateFields() {
        //     const m3Total = parseFloat(document.getElementById('m3Total')?.value) || 0;
        //     const valorMercancia = parseFloat(document.getElementById('valorMercancia')?.value) || 0;
        //     const totalIncrementableUsd = parseFloat(document.getElementsByName('totalIncrementableUsd')[0]?.value) || 0;

        //     const montoGastos = document.getElementsByName('montoGasto[]');
        //     let montoGastoValid = true;
        //     for (let i = 0; i < montoGastos.length; i++) {
        //         const val = parseFloat(montoGastos[i]?.value) || 0;
        //         if (val === 0) {
        //             montoGastoValid = false;
        //             break;
        //         }
        //     }

        //     const isValid = m3Total !== 0 && valorMercancia !== 0 && totalIncrementableUsd !== 0 && montoGastoValid;

        //     document.querySelector('button[name="save"]').disabled = !isValid;
        // }

        // Obtiene la fecha actual para la cotizacion
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('expedicion').value = formattedDate;

        // Select de cliente
        document.getElementById("cliente").addEventListener("change", function() {
            var idCliente = this.value;

            if (idCliente) {
                // --- PRIMERA PETICIÓN: obtener los asociados ---
                var xhr1 = new XMLHttpRequest();
                xhr1.open("POST", "obtener_asociados.php", true);
                xhr1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr1.onreadystatechange = function() {
                    if (xhr1.readyState === 4 && xhr1.status === 200) {
                        const opciones = "<option value='' disabled selected>Selecciona una opción</option>" + xhr1.responseText;

                        // Actualizar y habilitar los selects
                        const origen = document.getElementById("origen");
                        const aduana = document.getElementById("aduana");
                        const destino = document.getElementById("destino");

                        origen.innerHTML = opciones;
                        aduana.innerHTML = opciones;
                        destino.innerHTML = opciones;

                        origen.disabled = false;
                        aduana.disabled = false;
                        destino.disabled = false;

                        // Limpiar detalles anteriores
                        document.getElementById("detalleOrigen").innerHTML = "";
                        document.getElementById("detalleAduana").innerHTML = "";
                        document.getElementById("detalleDestino").innerHTML = "";
                    }
                };
                xhr1.send("idCliente=" + idCliente);

                // --- SEGUNDA PETICIÓN: obtener el detalle del cliente seleccionado ---
                var xhr2 = new XMLHttpRequest();
                xhr2.open("POST", "obtener_cliente.php", true);
                xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr2.onreadystatechange = function() {
                    if (xhr2.readyState === 4 && xhr2.status === 200) {
                        document.getElementById("detalleCliente").innerHTML = xhr2.responseText;
                    }
                };
                xhr2.send("idCliente=" + idCliente);
            } else {
                // Si se deselecciona el cliente, limpiar todo
                const selects = ["origen", "aduana", "destino"];
                selects.forEach(id => {
                    const select = document.getElementById(id);
                    select.innerHTML = "<option value='' disabled selected>Selecciona primero un cliente</option>";
                    select.disabled = true;
                });

                document.getElementById("detalleCliente").innerHTML = "";
                document.getElementById("detalleOrigen").innerHTML = "";
                document.getElementById("detalleAduana").innerHTML = "";
                document.getElementById("detalleDestino").innerHTML = "";
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
                <p>NMFC</p></td>
                <td>
                    <input class="form-control mb-1" type="text" name="unidadMedida[]">
                    <input class="form-control" type="text" name="nmfc[]" readonly>
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
            const volumeFt3 = ((height * 0.08333) * (width * 0.08333) * (deep * 0.08333)) * cantidad;
            row.querySelector("[placeholder='pies cúbicos']").value = volumeFt3.toFixed(2);
            const volumeM3 = volumeFt3 * 0.0283168;
            row.querySelector("[placeholder='metros cúbicos']").value = volumeM3.toFixed(2);

            actualizarTotales(); // Actualiza los totales después de convertir
        }

        function convertToInchesAndCalculateVolume(element) {
            const row = element.closest('tr');
            const altura = parseFloat(row.querySelector("[placeholder='Largo (mts)']").value) || 0;
            const ancho = parseFloat(row.querySelector("[placeholder='Ancho (mts)']").value) || 0;
            const profundidad = parseFloat(row.querySelector("[placeholder='Alto (mts)']").value) || 0;

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
            let totalCantidad = 0;

            for (let i = 1; i < tabla.rows.length; i++) {
                const fila = tabla.rows[i];

                // Obtener elementos de la fila
                const pesoLbsInput = fila.querySelector("input[name='libras[]']");
                const pesoKgsInput = fila.querySelector("input[name='kilogramos[]']");
                const valorInput = fila.querySelector("input[id='valorFilaMercancia']");
                const ft3Input = fila.querySelector("[placeholder='pies cúbicos']");
                const m3Input = fila.querySelector("[placeholder='metros cúbicos']");
                const cantidadInput = fila.querySelector("input[name='cantidad[]']");
                const nmfcInput = fila.querySelector("input[name='nmfc[]']");

                // Obtener valores de la fila
                let pesoLbs = parseFloat(pesoLbsInput.value) || 0;
                let pesoKgs = parseFloat(pesoKgsInput.value) || 0;
                let valor = parseFloat(valorInput.value) || 0;
                let ft3 = parseFloat(ft3Input.value) || 0;
                let m3 = parseFloat(m3Input.value) || 0;
                let cantidad = parseFloat(cantidadInput.value) || 0;

                // Sumar totales correctamente (solo una vez)
                totalLbs += pesoLbs;
                totalKgs += pesoKgs;
                totalValor += valor;
                totalFt3 += ft3;
                totalM3 += m3;
                totalCantidad += cantidad;

                // Calcular la relación pies cúbicos / peso en libras
                let ratio = pesoLbs > 0 ? pesoLbs / ft3 : 0;
                let nmfc = '';

                // Asignar el valor de NMFC según la tabla
                if (ratio >= 50) nmfc = 50;
                else if (ratio >= 35) nmfc = 55;
                else if (ratio >= 30) nmfc = 60;
                else if (ratio >= 22.5) nmfc = 65;
                else if (ratio >= 15) nmfc = 70;
                else if (ratio >= 13.5) nmfc = 77.5;
                else if (ratio >= 12) nmfc = 85;
                else if (ratio >= 10.5) nmfc = 92.5;
                else if (ratio >= 9) nmfc = 100;
                else if (ratio >= 8) nmfc = 110;
                else if (ratio >= 7) nmfc = 125;
                else if (ratio >= 6) nmfc = 150;
                else if (ratio >= 5) nmfc = 175;
                else if (ratio >= 4) nmfc = 200;
                else if (ratio >= 3) nmfc = 250;
                else if (ratio >= 2) nmfc = 300;
                else if (ratio >= 1) nmfc = 400;
                else if (ratio > 0) nmfc = 500;

                // Asignar el valor calculado al campo NMFC
                if (nmfcInput) nmfcInput.value = nmfc;
            }

            // Mostrar los totales en los inputs correspondientes
            document.getElementById("pesoMercanciaLbs").value = totalLbs.toFixed(2);
            document.getElementById("pesoMercanciaKgs").value = totalKgs.toFixed(2);
            document.getElementById("valorMercancia").value = totalValor.toFixed(2);
            document.getElementById("ft3Total").value = totalFt3.toFixed(2);
            document.getElementById("m3Total").value = totalM3.toFixed(2);
            document.getElementById("totalBultos").value = totalCantidad; // Ahora totalBultos es correcto

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

        function agregarTipoServicio() {
            var tipoSeleccionado = document.getElementById("tipoFtlSelect").value;

            // Si el usuario no ha seleccionado nada, no hacer nada
            if (tipoSeleccionado === "Selecciona un servicio") {
                return;
            }

            var tabla = document.getElementById("servicioTable").getElementsByTagName("tbody")[0];

            // Crear nueva fila
            var nuevaFilaServicio = document.createElement("tr");

            nuevaFilaServicio.innerHTML = `
        <td>
            <select class="form-select" name="conceptoServicio[]">
                <option value="" disabled selected>Selecciona una opción</option>
                <?php
                $query = "SELECT * FROM tiposervicio WHERE tipoServicio = 'ftl'";
                $result = mysqli_query($con, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($registro = mysqli_fetch_assoc($result)) {
                        $nombre = $registro['nombreServicio'];
                        echo "<option value='$nombre'>" . $nombre . "</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td><input type="text" name="tiempoServicio[]" class="form-control"></td>
    `;

            // Agregar la nueva fila a la tabla
            tabla.appendChild(nuevaFilaServicio);

            // Seleccionar automáticamente el mismo valor en el nuevo select
            var nuevoSelect = nuevaFilaServicio.querySelector("select");
            nuevoSelect.value = tipoSeleccionado;
        }

        const tableBody = document.querySelector("#incrementableTable tbody");
        const totalUSD = document.getElementById("totalUSD");
        const totalMXN = document.getElementById("totalMXN");
        const addRowButton = document.getElementById("addRowButton");
        const removeServiceButton = document.getElementById("removeServiceButton");
        const removeRowButton = document.getElementById("removeRowButton");

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("removeServiceButton").addEventListener("click", removeServiceRow);
            document.getElementById("removeRowButton").addEventListener("click", removeRow);
            document.getElementById("valorMoneda").addEventListener("input", actualizarValoresUSD_MXN);

            // document.getElementById('m3Total')?.addEventListener('input', validateFields);
            // document.getElementById('valorMercancia')?.addEventListener('input', validateFields);
            // document.getElementsByName('totalIncrementableUsd')[0]?.addEventListener('input', validateFields);

            // const montoGastos = document.getElementsByName('montoGasto[]');
            // for (let i = 0; i < montoGastos.length; i++) {
            //     montoGastos[i]?.addEventListener('input', validateFields);
            // }

            // // Validar al cargar
            // validateFields();

            observarCambio(); // Iniciar la observación del cambio de valor
            convertirNumeroATexto();
            agregarFila()
            agregarIncrementable();
            agregarTipoServicio();
        });

        // Función para agregar una nueva fila de incrementables
        function agregarIncrementable() {
            var tabla = document.getElementById("incrementableTable").getElementsByTagName("tbody")[0];

            // Crear nueva fila
            var nuevaFila = document.createElement("tr");

            nuevaFila.innerHTML = `
        <td>
            <select class="form-select conceptoIncrementable" name="incrementable[]" onchange="actualizarConceptoGasto(this)">
                <option value="" disabled selected>Selecciona una opción</option>
                <?php
                $query = "SELECT * FROM tipoincrementable WHERE tipo = 'ftl'";
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
        <td><input type="number" name="incrementableUsd[]" class="form-control usd-input" value="" oninput="actualizarMontoGasto(this); updateRow(this);"></td>
        <td><input type="text" name="incrementableMx[]" class="form-control mxn-input" value="0" readonly></td>
    `;

            // Agregar la nueva fila a la tabla de incrementables
            tabla.appendChild(nuevaFila);

            // Llamar a nuevoGasto solo una vez
            setTimeout(nuevoGasto, 10);

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
            var index = Array.from(document.querySelectorAll(".conceptoIncrementable")).indexOf(selectElement);
            var conceptoGastoInputs = document.querySelectorAll(".conceptoGasto");
            if (conceptoGastoInputs[index]) {
                conceptoGastoInputs[index].value = selectElement.value;
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
        function nuevoGasto() {
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
                var nuevaFila = document.createElement("tr");

                nuevaFila.innerHTML = `
            <td><input type="text" class="form-control conceptoGasto" name="conceptoGasto[]"></td>
            <td>
                <div class="form-check float-end">
                    <input class="form-check-input" type="checkbox" name="ivaGasto[]" checked>
                    <label class="form-check-label">IVA 16%</label>
                </div>
            </td>
            <td class="text-end"><input type="text" class="form-control montoGasto" name="montoGasto[]" oninput="actualizarSubtotal()"></td>
            <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></td>
        `;

                tabla.insertBefore(nuevaFila, filaSubtotal);
            }
        }

        // Función para eliminar una fila de la tabla de gastos
        function eliminarFila(boton) {
            var fila = boton.closest("tr"); // Encuentra la fila actual
            if (!fila) return; // Si no hay fila, salir

            var conceptoInput = fila.querySelector(".conceptoGasto");
            var conceptoGasto = conceptoInput ? conceptoInput.value : null; // Verifica si existe el input

            fila.remove(); // Elimina la fila de la tabla

            if (conceptoGasto) {
                eliminarIncrementable(conceptoGasto); // Elimina el incrementable solo si hay concepto
            }

            actualizarSubtotal(); // Recalcular subtotal
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
                if (n < 30) return n === 20 ? "VEINTE" : "VEINTI" + unidades[n % 10];
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

        async function obtenerTipoDeCambio() {
            try {
                const response = await fetch('tipo-cambio.php');
                if (!response.ok) {
                    throw new Error(`Error al obtener datos del backend: ${response.status}`);
                }

                const data = await response.json();
                const serie = data.bmx.series[0].datos;

                if (serie && serie.length > 0) {
                    const ultimoDato = serie[0];
                    console.log(`Tipo de cambio: ${ultimoDato.dato} (Fecha: ${ultimoDato.fecha})`);
                    document.getElementById("valorMoneda").value = ultimoDato.dato;
                } else {
                    console.warn("No hay datos disponibles en la serie.");
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        obtenerTipoDeCambio();
    </script>
</body>

</html>