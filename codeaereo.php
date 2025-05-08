<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    $delete_gastos = "DELETE FROM gastosaereoimpo WHERE idAereo='$id'";
    $delete_incrementables = "DELETE FROM incrementablesaereoimpo WHERE idAereo='$id'";
    $delete_descripcion = "DELETE FROM descripcionmercanciasaereoimpo WHERE idAereo='$id'";
    $delete_destino = "DELETE FROM gastosdestinoaereoimpo WHERE idAereo='$id'";
    $delete_origen = "DELETE FROM gastosorigenaereoimpo WHERE idAereo='$id'";

    $delete_ftl = "DELETE FROM aereo WHERE id='$id'";

    $query_run_gastos = mysqli_query($con, $delete_gastos);
    $query_run_incrementables = mysqli_query($con, $delete_incrementables);
    $query_run_descripcion = mysqli_query($con, $delete_descripcion);
    $query_run_destino = mysqli_query($con, $delete_destino);
    $query_run_origen = mysqli_query($con, $delete_origen);
    $query_run_ftl = mysqli_query($con, $delete_ftl);

    if ($query_run_gastos && $query_run_incrementables && $query_run_descripcion && $query_run_destino && $query_run_origen && $query_run_ftl) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: aereo-importacion.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: aereo-importacion.php");
        exit(0);
    }
}


if (isset($_POST['save'])) {
    $email = $_SESSION['email'];
    $fecha = isset($_POST['fecha']) ? mysqli_real_escape_string($con, $_POST['fecha']) : '';
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
    $pesoFisicoReal = mysqli_real_escape_string($con, $_POST['pesoFisicoReal']);
    $pesoVolumetrico = mysqli_real_escape_string($con, $_POST['pesoVolumetrico']);
    $pesoTarifario = mysqli_real_escape_string($con, $_POST['pesoTarifario']);
    $valorMercanciaUSD = mysqli_real_escape_string($con, $_POST['valorMercanciaUSD']);
    $valorMercanciaMXN = mysqli_real_escape_string($con, $_POST['valorMercanciaMXN']);
    $subtotalOrigen = mysqli_real_escape_string($con, $_POST['subtotalOrigen']);
    $totalOrigenAll = mysqli_real_escape_string($con, $_POST['totalOrigenAll']);
    $lugarDestino = mysqli_real_escape_string($con, $_POST['lugarDestino']);
    $subtotalDestinoUsd = mysqli_real_escape_string($con, $_POST['subtotalDestinoUsd']);
    $subtotalDestinoMx = mysqli_real_escape_string($con, $_POST['subtotalDestinoMx']);
    $impuestosDestinoUsd = mysqli_real_escape_string($con, $_POST['impuestosDestinoUsd']);
    $impuestosDestinoMx = mysqli_real_escape_string($con, $_POST['impuestosDestinoMx']);
    $totalDestinoUsd = mysqli_real_escape_string($con, $_POST['totalDestinoUsd']);
    $totalDestinoMx = mysqli_real_escape_string($con, $_POST['totalDestinoMx']);
    $valorTotalFlete = mysqli_real_escape_string($con, $_POST['valorTotalFlete']);
    $totalIncrementableUsd = mysqli_real_escape_string($con, $_POST['totalIncrementableUsd']);
    $totalIncrementableMx = mysqli_real_escape_string($con, $_POST['totalIncrementableMx']);
    $subtotalFlete = mysqli_real_escape_string($con, $_POST['subtotalFlete']);
    $impuestosFlete = mysqli_real_escape_string($con, $_POST['impuestosFlete']);
    $retencionFlete = mysqli_real_escape_string($con, $_POST['retencionFlete']);
    $totalCotizacionNumero = mysqli_real_escape_string($con, $_POST['totalCotizacionNumero']);
    $totalCotizacionTexto = mysqli_real_escape_string($con, $_POST['totalCotizacionTexto']);
    $tipoAereoImpo = mysqli_real_escape_string($con, $_POST['tipoAereoImpo']);
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);

    $sql = "INSERT INTO aereo (
        asignado, fecha, idCliente, idOrigen, idDestino, idDestinoFinal,
        distanciaOrigenDestinoMillas, distanciaOrigenDestinoKms, tiempoRecorridoOrigenDestino,
        servicio, totalFt3, totalM3, distanciaDestinoFinalMillas, distanciaDestinoFinalKms,
        tiempoRecorridoDestinoFinal, operador, unidad, moneda, valorMoneda,
        pesoFisicoReal, pesoVolumetrico, pesoTarifario, valorMercanciaUSD, valorMercanciaMXN,
        subtotalOrigen, totalOrigenAll, lugarDestino, subtotalDestinoUsd, subtotalDestinoMx,
        impuestosDestinoUsd, impuestosDestinoMx, totalDestinoUsd, totalDestinoMx,
        valorTotalFlete, totalIncrementableUsd, totalIncrementableMx,
        subtotalFlete, impuestosFlete, retencionFlete, totalCotizacionNumero,
        totalCotizacionTexto, tipoAereoImpo, observaciones
    ) VALUES (
        '$email', '$fecha', '$idCliente', '$idOrigen', '$idDestino', '$idDestinoFinal',
        '$distanciaOrigenDestinoMillas', '$distanciaOrigenDestinoKms', '$tiempoRecorridoOrigenDestino',
        '$servicio', '$totalFt3', '$totalM3', '$distanciaDestinoFinalMillas', '$distanciaDestinoFinalKms',
        '$tiempoRecorridoDestinoFinal', '$operador', '$unidad', '$moneda', '$valorMoneda',
        '$pesoFisicoReal', '$pesoVolumetrico', '$pesoTarifario', '$valorMercanciaUSD', '$valorMercanciaMXN',
        '$subtotalOrigen', '$totalOrigenAll', '$lugarDestino', '$subtotalDestinoUsd', '$subtotalDestinoMx',
        '$impuestosDestinoUsd', '$impuestosDestinoMx', '$totalDestinoUsd', '$totalDestinoMx',
        '$valorTotalFlete', '$totalIncrementableUsd', '$totalIncrementableMx',
        '$subtotalFlete', '$impuestosFlete', '$retencionFlete', '$totalCotizacionNumero',
        '$totalCotizacionTexto', '$tipoAereoImpo', '$observaciones'
    )";


    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        // Obtener el ID insertado en la tabla `ftl`
        $idAereo = mysqli_insert_id($con);

        // Insertar los datos de cada fila en `descripcionMercanciasFtl`
        $cantidad = $_POST['cantidad'];
        $unidadMedida = $_POST['unidadMedida'];
        $descripcion = $_POST['descripcion'];
        $largoCm = $_POST['largoCm'];
        $anchoCm = $_POST['anchoCm'];
        $altoCm = $_POST['altoCm'];
        $largoPlg = $_POST['largoPlg'];
        $anchoPlg = $_POST['anchoPlg'];
        $altoPlg = $_POST['altoPlg'];
        $piesCubicos = $_POST['piesCubicos'];
        $metrosCubicos = $_POST['metrosCubicos'];
        $libras = $_POST['libras'];
        $kilogramos = $_POST['kilogramos'];
        $valorFactura = $_POST['valorFactura'];

        for ($i = 0; $i < count($cantidad); $i++) {
            $cantidad_val = mysqli_real_escape_string($con, $cantidad[$i]);
            $unidadMedida_val = mysqli_real_escape_string($con, $unidadMedida[$i]);
            $descripcion_val = mysqli_real_escape_string($con, $descripcion[$i]);
            $largoCm_val = mysqli_real_escape_string($con, $largoCm[$i]);
            $anchoCm_val = mysqli_real_escape_string($con, $anchoCm[$i]);
            $altoCm_val = mysqli_real_escape_string($con, $altoCm[$i]);
            $largoPlg_val = mysqli_real_escape_string($con, $largoPlg[$i]);
            $anchoPlg_val = mysqli_real_escape_string($con, $anchoPlg[$i]);
            $altoPlg_val = mysqli_real_escape_string($con, $altoPlg[$i]);
            $piesCubicos_val = mysqli_real_escape_string($con, $piesCubicos[$i]);
            $metrosCubicos_val = mysqli_real_escape_string($con, $metrosCubicos[$i]);
            $libras_val = mysqli_real_escape_string($con, $libras[$i]);
            $kilogramos_val = mysqli_real_escape_string($con, $kilogramos[$i]);
            $valorFactura_val = mysqli_real_escape_string($con, $valorFactura[$i]);

            $sql_detalle = "INSERT INTO descripcionmercanciasaereoimpo (
                idAereo, cantidad, unidadMedida, descripcion, 
                largoCm, anchoCm, altoCm, largoPlg, anchoPlg, altoPlg, 
                piesCubicos, metrosCubicos, libras, kilogramos, valorFactura
            ) VALUES (
                '$idAereo', '$cantidad_val', '$unidadMedida_val', '$descripcion_val', 
                '$largoCm_val', '$anchoCm_val', '$altoCm_val', '$largoPlg_val', '$anchoPlg_val', '$altoPlg_val', 
                '$piesCubicos_val', '$metrosCubicos_val', '$libras_val', '$kilogramos_val', '$valorFactura_val'
            )";

            mysqli_query($con, $sql_detalle);
        }

        if (!empty($_POST['gastosOrigen'])) {
            $gastosOrigen = $_POST['gastosOrigen'];
            $minimoOrigen = $_POST['minimoOrigen'];
            $amountOrigen = $_POST['amountOrigen'];
            $totalOrigen = $_POST['totalOrigen'];
            $usdOrigen = $_POST['usdOrigen'];

            for ($i = 0; $i < count($gastosOrigen); $i++) {
                $origenes = isset($gastosOrigen[$i]) ? mysqli_real_escape_string($con, $gastosOrigen[$i]) : '';
                $minimos = isset($minimoOrigen[$i]) ? mysqli_real_escape_string($con, $minimoOrigen[$i]) : '';
                $amounts = isset($amountOrigen[$i]) ? mysqli_real_escape_string($con, $amountOrigen[$i]) : '';
                $totales = isset($totalOrigen[$i]) ? mysqli_real_escape_string($con, $totalOrigen[$i]) : '';
                $usds = isset($usdOrigen[$i]) ? mysqli_real_escape_string($con, $usdOrigen[$i]) : '';

                $sql_origen = "INSERT INTO gastosorigenaereoimpo (idAereo, gastosOrigen, minimoOrigen, amountOrigen, totalOrigen, usdOrigen)
                               VALUES ('$idAereo', '$origenes', '$minimos', '$amounts', '$totales', '$usds')";

                mysqli_query($con, $sql_origen);
            }
        }


        if (!empty($_POST['gastoDestino'])) {
            $gastoDestino = $_POST['gastoDestino'];
            $usdDestino = $_POST['usdDestino'];
            $mxnDestino = $_POST['mxnDestino'];

            for ($i = 0; $i < count($gastoDestino); $i++) {
                $destinos = mysqli_real_escape_string($con, $gastoDestino[$i]);
                $usDestino = mysqli_real_escape_string($con, $usdDestino[$i]);
                $mxDestino = mysqli_real_escape_string($con, $mxnDestino[$i]);

                $sql_destino = "INSERT INTO gastosdestinoaereoimpo (idAereo, gastoDestino, usdDestino, mxnDestino) VALUES ('$idAereo', '$destinos', '$usDestino', '$mxDestino')";

                mysqli_query($con, $sql_destino);
            }
        }

        if (!empty($_POST['incrementable'])) {
            $incrementables = $_POST['incrementable'];
            $incrementablesUsd = $_POST['incrementableUsd'];
            $incrementablesMx = $_POST['incrementableMx'];

            for ($i = 0; $i < count($incrementables); $i++) {
                $incrementable = mysqli_real_escape_string($con, $incrementables[$i]);
                $incrementableUsd = mysqli_real_escape_string($con, $incrementablesUsd[$i]);
                $incrementableMx = mysqli_real_escape_string($con, $incrementablesMx[$i]);

                $sql_incrementable = "INSERT INTO incrementablesaereoimpo (idAereo, incrementable, incrementableUsd, incrementableMx) 
                                      VALUES ('$idAereo', '$incrementable', '$incrementableUsd', '$incrementableMx')";

                mysqli_query($con, $sql_incrementable);
            }
        }

        if (!empty($_POST['conceptoGasto'])) {
            $conceptoGasto = $_POST['conceptoGasto'];
            $montoGasto = $_POST['montoGasto'];
            $ivaGasto = isset($_POST['ivaGasto']) ? $_POST['ivaGasto'] : [];

            for ($i = 0; $i < count($conceptoGasto); $i++) {
                $concepto = mysqli_real_escape_string($con, $conceptoGasto[$i]);
                $monto = mysqli_real_escape_string($con, $montoGasto[$i]);
                $iva = isset($ivaGasto[$i]) ? 1 : 0;

                $sql_gasto = "INSERT INTO gastosaereoimpo (idAereo, conceptoGasto, montoGasto, ivaGasto) VALUES ('$idAereo', '$concepto', '$monto', '$iva')";
                mysqli_query($con, $sql_gasto);
            }
        }

        $_SESSION['alert'] = [
            'title' => 'COTIZACIÓN REGISTRADA EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: aereo-importacion.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL CREAR LA COTIZACIÓN',
            'icon' => 'error'
        ];
        header("Location: aereo-importacion.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $fecha = mysqli_real_escape_string($con, $_POST['fecha']);
    $idCliente = mysqli_real_escape_string($con, $_POST['idCliente']);
    $idOrigen = mysqli_real_escape_string($con, $_POST['idOrigen']);
    $idDestino = mysqli_real_escape_string($con, $_POST['idAduana']);
    $idDestinoFinal = mysqli_real_escape_string($con, $_POST['idDestino']);
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
    $pesoFisicoReal = mysqli_real_escape_string($con, $_POST['pesoFisicoReal']);
    $pesoVolumetrico = mysqli_real_escape_string($con, $_POST['pesoVolumetrico']);
    $pesoTarifario = mysqli_real_escape_string($con, $_POST['pesoTarifario']);
    $valorMercanciaUSD = mysqli_real_escape_string($con, $_POST['valorMercanciaUSD']);
    $valorMercanciaMXN = mysqli_real_escape_string($con, $_POST['valorMercanciaMXN']);


    $subtotalOrigen = mysqli_real_escape_string($con, $_POST['subtotalOrigen']);
    $totalOrigenAll = mysqli_real_escape_string($con, $_POST['totalOrigenAll']);
    $lugarDestino = mysqli_real_escape_string($con, $_POST['lugarDestino']);
    $subtotalDestinoUsd = mysqli_real_escape_string($con, $_POST['subtotalDestinoUsd']);
    $subtotalDestinoMx = mysqli_real_escape_string($con, $_POST['subtotalDestinoMx']);
    $impuestosDestinoUsd = mysqli_real_escape_string($con, $_POST['impuestosDestinoUsd']);
    $impuestosDestinoMx = mysqli_real_escape_string($con, $_POST['impuestosDestinoMx']);
    $totalDestinoUsd = mysqli_real_escape_string($con, $_POST['totalDestinoUsd']);
    $totalDestinoMx = mysqli_real_escape_string($con, $_POST['totalDestinoMx']);
    $valorTotalFlete = mysqli_real_escape_string($con, $_POST['valorTotalFlete']);

    $subtotalFlete = mysqli_real_escape_string($con, $_POST['subtotalFlete']);
    $impuestosFlete = mysqli_real_escape_string($con, $_POST['impuestosFlete']);
    $retencionFlete = mysqli_real_escape_string($con, $_POST['retencionFlete']);
    $totalCotizacionNumero = mysqli_real_escape_string($con, $_POST['totalCotizacionNumero']);
    $totalCotizacionTexto = mysqli_real_escape_string($con, $_POST['totalCotizacionTexto']);
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);
    $totalIncrementableUsd = mysqli_real_escape_string($con, $_POST['totalIncrementableUsd']);
    $totalIncrementableMx = mysqli_real_escape_string($con, $_POST['totalIncrementableMx']);
    $tipoAereoImpo = mysqli_real_escape_string($con, $_POST['tipoAereoImpo']);

    $sql = "UPDATE aereo SET 
        fecha = '$fecha',
        idCliente = '$idCliente',
        idOrigen = '$idOrigen',
        idDestino = '$idDestino',
        idDestino = '$idDestinoFinal',
        distanciaOrigenDestinoMillas = '$distanciaOrigenDestinoMillas',
        distanciaOrigenDestinoKms = '$distanciaOrigenDestinoKms',
        tiempoRecorridoOrigenDestino = '$tiempoRecorridoOrigenDestino',
        servicio = '$servicio',
        totalFt3 = '$totalFt3',
        totalM3 = '$totalM3',
        distanciaDestinoFinalMillas = '$distanciaDestinoFinalMillas',
        distanciaDestinoFinalKms = '$distanciaDestinoFinalKms',
        tiempoRecorridoDestinoFinal = '$tiempoRecorridoDestinoFinal',
        operador = '$operador',
        unidad = '$unidad',
        moneda = '$moneda',
        valorMoneda = '$valorMoneda',
        pesoFisicoReal = '$pesoFisicoReal',
        pesoVolumetrico = '$pesoVolumetrico',
        pesoTarifario = '$pesoTarifario',
        valorMercanciaUSD = '$valorMercanciaUSD',
        valorMercanciaMXN = '$valorMercanciaMXN',
        subtotalOrigen = '$subtotalOrigen',
        totalOrigenAll = '$totalOrigenAll',
        lugarDestino = '$lugarDestino',
        subtotalDestinoUsd = '$subtotalDestinoUsd',
        subtotalDestinoMx = '$subtotalDestinoMx',
        impuestosDestinoUsd = '$impuestosDestinoUsd',
        impuestosDestinoMx = '$impuestosDestinoMx',
        totalDestinoUsd = '$totalDestinoUsd',
        totalDestinoMx = '$totalDestinoMx',
        valorTotalFlete = '$valorTotalFlete',
        subtotalFlete = '$subtotalFlete',
        impuestosFlete = '$impuestosFlete',
        retencionFlete = '$retencionFlete',
        totalCotizacionNumero = '$totalCotizacionNumero',
        totalCotizacionTexto = '$totalCotizacionTexto',
        observaciones = '$observaciones',
        totalIncrementableUsd = '$totalIncrementableUsd',
        totalIncrementableMx = '$totalIncrementableMx',
        tipoAereoImpo = '$tipoAereoImpo'
    WHERE id = '$id'";

    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        // Actualizar tablas relacionadas

        // Eliminar registros actuales y volver a insertar (Alternativa: UPDATE)
        mysqli_query($con, "DELETE FROM descripcionmercanciasaereoimpo WHERE idAereo = '$id'");
        mysqli_query($con, "DELETE FROM incrementablesaereoimpo WHERE idAereo = '$id'");
        mysqli_query($con, "DELETE FROM gastosorigenaereoimpo WHERE idAereo = '$id'");
        mysqli_query($con, "DELETE FROM gastosdestinoaereoimpo WHERE idAereo = '$id'");
        mysqli_query($con, "DELETE FROM gastosaereoimpo WHERE idAereo = '$id'");

        // Insertar nueva información
        foreach ($_POST['cantidad'] as $i => $cantidad) {
            $cantidad_val = mysqli_real_escape_string($con, $cantidad);
            $unidadMedida_val = mysqli_real_escape_string($con, $_POST['unidadMedida'][$i]);
            $descripcion_val = mysqli_real_escape_string($con, $_POST['descripcion'][$i]);
            $largoCm_val = mysqli_real_escape_string($con, $_POST['largoCm'][$i]);
            $anchoCm_val = mysqli_real_escape_string($con, $_POST['anchoCm'][$i]);
            $altoCm_val = mysqli_real_escape_string($con, $_POST['altoCm'][$i]);
            $largoPlg_val = mysqli_real_escape_string($con, $_POST['largoPlg'][$i]);
            $anchoPlg_val = mysqli_real_escape_string($con, $_POST['anchoPlg'][$i]);
            $altoPlg_val = mysqli_real_escape_string($con, $_POST['altoPlg'][$i]);
            $piesCubicos_val = mysqli_real_escape_string($con, $_POST['piesCubicos'][$i]);
            $metrosCubicos_val = mysqli_real_escape_string($con, $_POST['metrosCubicos'][$i]);
            $libras_val = mysqli_real_escape_string($con, $_POST['libras'][$i]);
            $kilogramos_val = mysqli_real_escape_string($con, $_POST['kilogramos'][$i]);
            $valorFactura_val = mysqli_real_escape_string($con, $_POST['valorFactura'][$i]);

            // Actualizar los registros en la tabla descripcionmercanciasftl
            $sql_detalle = "INSERT INTO descripcionmercanciasaereoimpo (
                idAereo, cantidad, unidadMedida, descripcion,
                largoCm, anchoCm, altoCm, largoPlg, anchoPlg, altoPlg,
                piesCubicos, metrosCubicos, libras, kilogramos, valorFactura
            ) VALUES (
                '$id', '$cantidad_val', '$unidadMedida_val', '$descripcion_val',
                '$largoCm_val', '$anchoCm_val', '$altoCm_val', '$largoPlg_val', '$anchoPlg_val', '$altoPlg_val',
                '$piesCubicos_val', '$metrosCubicos_val', '$libras_val', '$kilogramos_val', '$valorFactura_val'
            )";
            mysqli_query($con, $sql_detalle);
        }


        if (!empty($_POST['gastoDestino']) && is_array($_POST['gastoDestino'])) {
            foreach ($_POST['gastoDestino'] as $i => $concepto) {
                $concepto_val = mysqli_real_escape_string($con, $concepto);
                $usd_val = mysqli_real_escape_string($con, $_POST['usdDestino'][$i]);
                $mxn_val = mysqli_real_escape_string($con, $_POST['mxnDestino'][$i]);

                $sql_destino = "INSERT INTO gastosdestinoaereoimpo (idAereo, gastoDestino, usdDestino, mxnDestino)
                                 VALUES ('$id', '$concepto_val', '$usd_val', '$mxn_val')";
                mysqli_query($con, $sql_destino);
            }
        }

        if (!empty($_POST['gastosOrigen']) && is_array($_POST['gastosOrigen'])) {
            foreach ($_POST['gastosOrigen'] as $i => $concepto) {
                $concepto_val = isset($concepto) ? mysqli_real_escape_string($con, $concepto) : '';
                $min_val = isset($_POST['minimoOrigen'][$i]) ? mysqli_real_escape_string($con, $_POST['minimoOrigen'][$i]) : '';
                $am_val = isset($_POST['amountOrigen'][$i]) ? mysqli_real_escape_string($con, $_POST['amountOrigen'][$i]) : '';
                $tot_val = isset($_POST['totalOrigen'][$i]) ? mysqli_real_escape_string($con, $_POST['totalOrigen'][$i]) : '';
                $usd_val = isset($_POST['usdOrigen'][$i]) ? mysqli_real_escape_string($con, $_POST['usdOrigen'][$i]) : '';

                $sql_origen = "INSERT INTO gastosorigenaereoimpo (idAereo, gastosOrigen, minimoOrigen, amountOrigen, totalOrigen, usdOrigen)
                               VALUES ('$id', '$concepto_val', '$min_val', '$am_val', '$tot_val', '$usd_val')";
                mysqli_query($con, $sql_origen);
            }
        }

        if (!empty($_POST['incrementable']) && is_array($_POST['incrementable'])) {
            foreach ($_POST['incrementable'] as $i => $incrementable) {
                $incrementable_val = mysqli_real_escape_string($con, $incrementable);
                $usd_val = mysqli_real_escape_string($con, $_POST['incrementableUsd'][$i]);
                $mx_val = mysqli_real_escape_string($con, $_POST['incrementableMx'][$i]);

                $sql_incrementable = "INSERT INTO incrementablesaereoimpo (idAereo, incrementable, incrementableUsd, incrementableMx)
                                      VALUES ('$id', '$incrementable_val', '$usd_val', '$mx_val')";
                mysqli_query($con, $sql_incrementable);
            }
        }


        if (!empty($_POST['conceptoGasto']) && is_array($_POST['conceptoGasto'])) {
            foreach ($_POST['conceptoGasto'] as $i => $gasto) {
                $concepto_val = mysqli_real_escape_string($con, $gasto);
                $monto_val = mysqli_real_escape_string($con, $_POST['montoGasto'][$i]);
                $iva_val = isset($_POST['ivaGasto'][$i]) ? 1 : 0;

                $sql_gasto = "INSERT INTO gastosaereoimpo (idAereo, conceptoGasto, montoGasto, ivaGasto)
                              VALUES ('$id', '$concepto_val', '$monto_val', '$iva_val')";
                mysqli_query($con, $sql_gasto);
            }
        }


        $_SESSION['alert'] = ['title' => "COTIZACIÓN ACTUALIZADA EXITOSAMENTE", 'icon' => 'success'];
        header("Location: aereo-importacion.php");
        exit;
    } else {
        $_SESSION['alert'] = ['title' => 'ERROR AL ACTUALIZAR', 'icon' => 'error'];
        header("Location: aereo-importacion.php");
        exit;
    }
}
