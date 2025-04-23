<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM aereo WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];        
        header("Location: aereoimpointernacional.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: aereoimpointernacional.php");
        exit(0);
    }
}

if (isset($_POST['save'])) {
    $fecha = mysqli_real_escape_string($con, $_POST['fecha']);
    $idCliente = mysqli_real_escape_string($con, $_POST['idCliente']);
    $idOrigen = mysqli_real_escape_string($con, $_POST['idOrigen']);
    $idDestino = mysqli_real_escape_string($con, $_POST['idDestino']);
    $idDestinoFinal = mysqli_real_escape_string($con, $_POST['idDestinoFinal']);
    $distanciaOrigenDestinoMillas = mysqli_real_escape_string($con, $_POST['distanciaOrigenDestinoMillas']);
    $distanciaOrigenDestinoKms = mysqli_real_escape_string($con, $_POST['distanciaOrigenDestinoKms']);
    $tiempoRecorridoOrigenDestino = mysqli_real_escape_string($con, $_POST['tiempoRecorridoOrigenDestino']);
    $servicio = mysqli_real_escape_string($con, $_POST['servicio']);
    $totalFt3 = mysqli_real_escape_string($con, $_POST['totalFt3']);
    $totalM3 = mysqli_real_escape_string($con, $_POST['totalM3']);
    $distanciaDestinoFinalMillas = mysqli_real_escape_string($con, $_POST['distanciaDestinoFinalMillas']);
    $distanciaDestinoFinalKms = mysqli_real_escape_string($con, $_POST['distanciaDestinoFinalKms']);
    $tiempoRecorridoDestinoFinal = mysqli_real_escape_string($con, $_POST['tiempoRecorridoDestinoFinal']);
    $operador = mysqli_real_escape_string($con, $_POST['operador']);
    $unidad = mysqli_real_escape_string($con, $_POST['unidad']);
    $moneda = mysqli_real_escape_string($con, $_POST['moneda']);
    $valorMoneda = mysqli_real_escape_string($con, $_POST['valorMoneda']);
    $pesoMercanciaLbs = mysqli_real_escape_string($con, $_POST['pesoMercanciaLbs']);
    $pesoMercanciaKgs = mysqli_real_escape_string($con, $_POST['pesoMercanciaKgs']);
    $pesoCargableKgs = mysqli_real_escape_string($con, $_POST['pesoCargableKgs']);
    $pesoCotizacion = mysqli_real_escape_string($con, $_POST['pesoCotizacion']);
    $valorMercancia = mysqli_real_escape_string($con, $_POST['valorMercancia']);
    $valorComercial = mysqli_real_escape_string($con, $_POST['valorComercial']);
    $collectionFeeOrigenUno = mysqli_real_escape_string($con, $_POST['collectionFeeOrigenUno']);
    $collectionFeeOrigenDos = mysqli_real_escape_string($con, $_POST['collectionFeeOrigenDos']);
    $collectionFeeOrigenTotal = mysqli_real_escape_string($con, $_POST['collectionFeeOrigenTotal']);
    $collectionFeeOrigenTotalUsd = mysqli_real_escape_string($con, $_POST['collectionFeeOrigenTotalUsd']);
    $screeningChargeUno = mysqli_real_escape_string($con, $_POST['screeningChargeUno']);
    $screeningChargeDos = mysqli_real_escape_string($con, $_POST['screeningChargeDos']);
    $screeningChargeTotal = mysqli_real_escape_string($con, $_POST['screeningChargeTotal']);
    $screeningChargeTotalUsd = mysqli_real_escape_string($con, $_POST['screeningChargeTotalUsd']);
    $terminalHandlingUno = mysqli_real_escape_string($con, $_POST['terminalHandlingUno']);
    $terminalHandlingDos = mysqli_real_escape_string($con, $_POST['terminalHandlingDos']);
    $terminalHandlingTotal = mysqli_real_escape_string($con, $_POST['terminalHandlingTotal']);
    $terminalHandlingTotalUsd = mysqli_real_escape_string($con, $_POST['terminalHandlingTotalUsd']);
    $airportTransferUno = mysqli_real_escape_string($con, $_POST['airportTransferUno']);
    $airportTransferDos = mysqli_real_escape_string($con, $_POST['airportTransferDos']);
    $airportTransferTotal = mysqli_real_escape_string($con, $_POST['airportTransferTotal']);
    $airportTransferTotalUsd = mysqli_real_escape_string($con, $_POST['airportTransferTotalUsd']);
    $exportsCustomsUno = mysqli_real_escape_string($con, $_POST['exportsCustomsUno']);
    $exportsCustomsDos = mysqli_real_escape_string($con, $_POST['exportsCustomsDos']);
    $exportsCustomsTotal = mysqli_real_escape_string($con, $_POST['exportsCustomsTotal']);
    $exportsCustomsTotalUsd = mysqli_real_escape_string($con, $_POST['exportsCustomsTotalUsd']);
    $xRayUno = mysqli_real_escape_string($con, $_POST['xRayUno']);
    $xRayDos = mysqli_real_escape_string($con, $_POST['xRayDos']);
    $xRayTotal = mysqli_real_escape_string($con, $_POST['xRayTotal']);
    $xRayTotalUsd = mysqli_real_escape_string($con, $_POST['xRayTotalUsd']);
    $airportTaxUno = mysqli_real_escape_string($con, $_POST['airportTaxUno']);
    $airportTaxDos = mysqli_real_escape_string($con, $_POST['airportTaxDos']);
    $airportTaxTotal = mysqli_real_escape_string($con, $_POST['airportTaxTotal']);
    $airportTaxTotalUsd = mysqli_real_escape_string($con, $_POST['airportTaxTotalUsd']);
    $amsFeeOrigenUno = mysqli_real_escape_string($con, $_POST['amsFeeOrigenUno']);
    $amsFeeOrigenDos = mysqli_real_escape_string($con, $_POST['amsFeeOrigenDos']);
    $amsFeeOrigenTotal = mysqli_real_escape_string($con, $_POST['amsFeeOrigenTotal']);
    $amsFeeOrigenTotalUsd = mysqli_real_escape_string($con, $_POST['amsFeeOrigenTotalUsd']);
    $adicionalOrigenUnoTitle = mysqli_real_escape_string($con, $_POST['adicionalOrigenUnoUno']);
    $adicionalOrigenUnoUno = mysqli_real_escape_string($con, $_POST['adicionalOrigenUnoUno']);
    $adicionalOrigenUnoDos = mysqli_real_escape_string($con, $_POST['adicionalOrigenUnoDos']);
    $adicionalOrigenUnoTotal = mysqli_real_escape_string($con, $_POST['adicionalOrigenUnoTotal']);
    $adicionalOrigenUnoTotalUsd = mysqli_real_escape_string($con, $_POST['adicionalOrigenUnoTotalUsd']);
    $adicionalOrigenDosTitle = mysqli_real_escape_string($con, $_POST['adicionalOrigenDosUno']);
    $adicionalOrigenDosUno = mysqli_real_escape_string($con, $_POST['adicionalOrigenDosUno']);
    $adicionalOrigenDosDos = mysqli_real_escape_string($con, $_POST['adicionalOrigenDosDos']);
    $adicionalOrigenDosTotal = mysqli_real_escape_string($con, $_POST['adicionalOrigenDosTotal']);
    $adicionalOrigenDosTotalUsd = mysqli_real_escape_string($con, $_POST['adicionalOrigenDosTotalUsd']);
    $hawbDos = mysqli_real_escape_string($con, $_POST['hawbDos']);
    $hawbTotal = mysqli_real_escape_string($con, $_POST['hawbTotal']);
    $hawbTotalUSD = mysqli_real_escape_string($con, $_POST['hawbTotalUSD']);
    $fscADos = mysqli_real_escape_string($con, $_POST['fscADos']);
    $fscATotal = mysqli_real_escape_string($con, $_POST['fscATotal']);
    $fscATotalUsd = mysqli_real_escape_string($con, $_POST['fscATotalUsd']);
    $sscADos = mysqli_real_escape_string($con, $_POST['sscADos']);
    $sscATotal = mysqli_real_escape_string($con, $_POST['sscATotal']);
    $sscATotalUsd = mysqli_real_escape_string($con, $_POST['sscATotalUsd']);
    $subtotalOrigen = mysqli_real_escape_string($con, $_POST['subtotalOrigen']);
    $totalOrigen = mysqli_real_escape_string($con, $_POST['totalOrigen']);
    $lugarDestino = mysqli_real_escape_string($con, $_POST['lugarDestino']);
    $handlingUsd = mysqli_real_escape_string($con, $_POST['handlingUsd']);
    $handlingMx = mysqli_real_escape_string($con, $_POST['handlingMx']);
    $desconsolUsd = mysqli_real_escape_string($con, $_POST['desconsolUsd']);
    $desconsolMx = mysqli_real_escape_string($con, $_POST['desconsolMx']);
    $collectionFeeUsd = mysqli_real_escape_string($con, $_POST['collectionFeeUsd']);
    $collectionFeeMx = mysqli_real_escape_string($con, $_POST['collectionFeeMx']);
    $amsFeeUsd = mysqli_real_escape_string($con, $_POST['amsFeeUsd']);
    $amsFeeMx = mysqli_real_escape_string($con, $_POST['amsFeeMx']);
    $adicionalDestinoUno = mysqli_real_escape_string($con, $_POST['adicionalDestinoUnoUsd']);
    $adicionalDestinoUnoUsd = mysqli_real_escape_string($con, $_POST['adicionalDestinoUnoUsd']);
    $adicionalDestinoUnoMx = mysqli_real_escape_string($con, $_POST['adicionalDestinoUnoMx']);
    $adicionalDestinoDos = mysqli_real_escape_string($con, $_POST['adicionalDestinoDosUsd']);
    $adicionalDestinoDosUsd = mysqli_real_escape_string($con, $_POST['adicionalDestinoDosUsd']);
    $adicionalDestinoDosMx = mysqli_real_escape_string($con, $_POST['adicionalDestinoDosMx']);
    $subtotalDestinoUsd = mysqli_real_escape_string($con, $_POST['subtotalDestinoUsd']);
    $subtotalDestinoMx = mysqli_real_escape_string($con, $_POST['subtotalDestinoMx']);
    $impuestosDestinoUsd = mysqli_real_escape_string($con, $_POST['impuestosDestinoUsd']);
    $impuestosDestinoMx = mysqli_real_escape_string($con, $_POST['impuestosDestinoMx']);
    $totalDestinoUsd = mysqli_real_escape_string($con, $_POST['totalDestinoUsd']);
    $totalDestinoMx = mysqli_real_escape_string($con, $_POST['totalDestinoMx']);
    $valorTotalFlete = mysqli_real_escape_string($con, $_POST['valorTotalFlete']);
    $fleteExtranjeroUsd = mysqli_real_escape_string($con, $_POST['fleteExtranjeroUsd']);
    $fleteExtranjeroMx = mysqli_real_escape_string($con, $_POST['fleteExtranjeroMx']);
    $maniobrasUsd = mysqli_real_escape_string($con, $_POST['maniobrasUsd']);
    $maniobrasMx = mysqli_real_escape_string($con, $_POST['maniobrasMx']);
    $almacenajeUsd = mysqli_real_escape_string($con, $_POST['almacenajeUsd']);
    $almacenajeMx = mysqli_real_escape_string($con, $_POST['almacenajeMx']);
    $totalIncrementableUsd = mysqli_real_escape_string($con, $_POST['totalIncrementableUsd']);
    $totalIncrementableMx = mysqli_real_escape_string($con, $_POST['totalIncrementableMx']);
    $subtotalFlete = mysqli_real_escape_string($con, $_POST['subtotalFlete']);
    $retencionFlete = mysqli_real_escape_string($con, $_POST['retencionFlete']);
    $tipoAereoImpo = mysqli_real_escape_string($con, $_POST['tipoAereoImpo']);

    $sql = "INSERT INTO aereo (
        fecha, idCliente, idOrigen, idDestino, idDestinoFinal, distanciaOrigenDestinoMillas, distanciaOrigenDestinoKms, tiempoRecorridoOrigenDestino, servicio, totalFt3, totalM3, distanciaDestinoFinalMillas, distanciaDestinoFinalKms, tiempoRecorridoDestinoFinal, operador, unidad, moneda, valorMoneda, pesoMercanciaLbs, pesoMercanciaKgs, pesoCargableKgs, pesoCotizacion, valorMercancia,
    valorComercial, collectionFeeOrigenUno, collectionFeeOrigenDos, collectionFeeOrigenTotal, collectionFeeOrigenTotalUsd, screeningChargeUno, screeningChargeDos, screeningChargeTotal, screeningChargeTotalUsd, terminalHandlingUno, terminalHandlingDos, terminalHandlingTotal, terminalHandlingTotalUsd, airportTransferUno, airportTransferDos, airportTransferTotal, airportTransferTotalUsd,
    exportsCustomsUno, exportsCustomsDos, exportsCustomsTotal, exportsCustomsTotalUsd, xRayUno, xRayDos, xRayTotal, xRayTotalUsd, airportTaxUno, airportTaxDos, airportTaxTotal, airportTaxTotalUsd, amsFeeOrigenUno, amsFeeOrigenDos, amsFeeOrigenTotal, amsFeeOrigenTotalUsd, adicionalOrigenUnoTitle, adicionalOrigenUnoUno, adicionalOrigenUnoDos, 
    adicionalOrigenUnoTotal, adicionalOrigenUnoTotalUsd, adicionalOrigenDosTitle, adicionalOrigenDosUno, adicionalOrigenDosDos, adicionalOrigenDosTotal, adicionalOrigenDosTotalUsd, hawbDos, hawbTotal, hawbTotalUSD, fscADos, fscATotal, fscATotalUsd, sscADos, sscATotal, sscATotalUsd, subtotalOrigen, totalOrigen, lugarDestino, 
    handlingUsd, handlingMx, desconsolUsd, desconsolMx, collectionFeeUsd, collectionFeeMx, amsFeeUsd, amsFeeMx, adicionalDestinoUno, adicionalDestinoUnoUsd, adicionalDestinoUnoMx, adicionalDestinoDos, adicionalDestinoDosUsd, adicionalDestinoDosMx, subtotalDestinoUsd, subtotalDestinoMx, impuestosDestinoUsd, impuestosDestinoMx, totalDestinoUsd,
    totalDestinoMx, valorTotalFlete, fleteExtranjeroUsd, fleteExtranjeroMx, maniobrasUsd, maniobrasMx, almacenajeUsd, almacenajeMx, totalIncrementableUsd, totalIncrementableMx, subtotalFlete, retencionFlete, tipoAereoImpo
    ) VALUES (
        '$fecha', '$idCliente', '$idOrigen', '$idDestino', '$idDestinoFinal', '$distanciaOrigenDestinoMillas', '$distanciaOrigenDestinoKms', '$tiempoRecorridoOrigenDestino', '$servicio', '$totalFt3', '$totalM3', '$distanciaDestinoFinalMillas', '$distanciaDestinoFinalKms', '$tiempoRecorridoDestinoFinal', '$operador', '$unidad', '$moneda', '$valorMoneda', '$pesoMercanciaLbs', '$pesoMercanciaKgs', '$pesoCargableKgs', '$pesoCotizacion', '$valorMercancia',
    '$valorComercial', '$collectionFeeOrigenUno', '$collectionFeeOrigenDos', '$collectionFeeOrigenTotal', '$collectionFeeOrigenTotalUsd', '$screeningChargeUno', '$screeningChargeDos', '$screeningChargeTotal', '$screeningChargeTotalUsd', '$terminalHandlingUno', '$terminalHandlingDos', '$terminalHandlingTotal', '$terminalHandlingTotalUsd', '$airportTransferUno', '$airportTransferDos', '$airportTransferTotal', '$airportTransferTotalUsd',
    '$exportsCustomsUno', '$exportsCustomsDos', '$exportsCustomsTotal', '$exportsCustomsTotalUsd', '$xRayUno', '$xRayDos', '$xRayTotal', '$xRayTotalUsd', '$airportTaxUno', '$airportTaxDos', '$airportTaxTotal', '$airportTaxTotalUsd', '$amsFeeOrigenUno', '$amsFeeOrigenDos', '$amsFeeOrigenTotal', '$amsFeeOrigenTotalUsd', '$adicionalOrigenUnoTitle', '$adicionalOrigenUnoUno', '$adicionalOrigenUnoDos', 
    '$adicionalOrigenUnoTotal', '$adicionalOrigenUnoTotalUsd', '$adicionalOrigenDosTitle', '$adicionalOrigenDosUno', '$adicionalOrigenDosDos', '$adicionalOrigenDosTotal', '$adicionalOrigenDosTotalUsd', '$hawbDos', '$hawbTotal', '$hawbTotalUSD', '$fscADos', '$fscATotal', '$fscATotalUsd', '$sscADos', '$sscATotal', '$sscATotalUsd', '$subtotalOrigen', '$totalOrigen', '$lugarDestino',
    '$handlingUsd', '$handlingMx', '$desconsolUsd', '$desconsolMx', '$collectionFeeUsd', '$collectionFeeMx', '$amsFeeUsd', '$amsFeeMx', '$adicionalDestinoUno', '$adicionalDestinoUnoUsd', '$adicionalDestinoUnoMx', '$adicionalDestinoDos', '$adicionalDestinoDosUsd', '$adicionalDestinoDosMx', '$subtotalDestinoUsd', '$subtotalDestinoMx', '$impuestosDestinoUsd', '$impuestosDestinoMx', '$totalDestinoUsd',
    '$totalDestinoMx', '$valorTotalFlete', '$fleteExtranjeroUsd', '$fleteExtranjeroMx', '$maniobrasUsd', '$maniobrasMx', '$almacenajeUsd', '$almacenajeMx', '$totalIncrementableUsd', '$totalIncrementableMx', '$subtotalFlete', '$retencionFlete', '$tipoAereoImpo'
    )";
    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        $_SESSION['alert'] = [
            'title' => 'GUARDADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: aereoimpointernacional.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL GUARDAR',
            'icon' => 'error'
        ];
        header("Location: aereoimpointernacional.php");
        exit(0);
    }
}
