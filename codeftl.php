<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['save'])) {
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
    $pesoMercanciaLbs = mysqli_real_escape_string($con, $_POST['pesoMercanciaLbs']);
    $pesoMercanciaKgs = mysqli_real_escape_string($con, $_POST['pesoMercanciaKgs']);
    $totalBultos = mysqli_real_escape_string($con, $_POST['totalBultos']);
    $valorMercancia = mysqli_real_escape_string($con, $_POST['valorMercancia']);
    $valorComercial = mysqli_real_escape_string($con, $_POST['valorComercial']);
    $fleteTerrestre = mysqli_real_escape_string($con, $_POST['fleteTerrestre']);
    $subtotalFlete = mysqli_real_escape_string($con, $_POST['subtotalFlete']);
    $impuestosFlete = mysqli_real_escape_string($con, $_POST['impuestosFlete']);
    $retencionFlete = mysqli_real_escape_string($con, $_POST['retencionFlete']);
    $totalCotizacionNumero = mysqli_real_escape_string($con, $_POST['totalCotizacionNumero']);
    $totalCotizacionTexto = mysqli_real_escape_string($con, $_POST['totalCotizacionTexto']);
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);
    $totalIncrementableUsd = mysqli_real_escape_string($con, $_POST['totalIncrementableUsd']);
    $totalIncrementableMx = mysqli_real_escape_string($con, $_POST['totalIncrementableMx']);
    
    $sql = "INSERT INTO ftl (
        fecha, idCliente, idOrigen, idDestino, idDestinoFinal,
        distanciaOrigenDestinoMillas, distanciaOrigenDestinoKms, tiempoRecorridoOrigenDestino, servicio, 
        totalFt3, totalM3, distanciaDestinoFinalMillas, distanciaDestinoFinalKms, tiempoRecorridoDestinoFinal, 
        operador, unidad, moneda, valorMoneda, pesoMercanciaLbs, pesoMercanciaKgs, totalBultos, 
        valorMercancia, valorComercial, subtotalFlete, impuestosFlete, retencionFlete, totalCotizacionNumero, totalCotizacionTexto, observaciones, totalIncrementableUsd, totalIncrementableMx
    ) VALUES (
        '$fecha', '$idCliente', '$idOrigen', '$idDestino', '$idDestinoFinal',
        '$distanciaOrigenDestinoMillas', '$distanciaOrigenDestinoKms', '$tiempoRecorridoOrigenDestino', '$servicio', 
        '$totalFt3', '$totalM3', '$distanciaDestinoFinalMillas', '$distanciaDestinoFinalKms', '$tiempoRecorridoDestinoFinal', 
        '$operador', '$unidad', '$moneda', '$valorMoneda', '$pesoMercanciaLbs', '$pesoMercanciaKgs', '$totalBultos', 
        '$valorMercancia', '$valorComercial', '$subtotalFlete', '$impuestosFlete', '$retencionFlete', '$totalCotizacionNumero', '$totalCotizacionTexto', '$observaciones', '$totalIncrementableUsd', '$totalIncrementableMx'
    )";

    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        // Obtener el ID insertado en la tabla `ftl`
        $idFtl = mysqli_insert_id($con);

        // Insertar los datos de cada fila en `descripcionMercanciasFtl`
        $cantidad = $_POST['cantidad'];
        $unidadMedida = $_POST['unidadMedida'];
        $nmfc = $_POST['nmfc'];
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
            $nmfc_val = mysqli_real_escape_string($con, $nmfc[$i]);
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

            $sql_detalle = "INSERT INTO descripcionMercanciasFtl (
                idFtl, cantidad, unidadMedida, nmfc, descripcion, 
                largoCm, anchoCm, altoCm, largoPlg, anchoPlg, altoPlg, 
                piesCubicos, metrosCubicos, libras, kilogramos, valorFactura
            ) VALUES (
                '$idFtl', '$cantidad_val', '$unidadMedida_val', '$nmfc_val', '$descripcion_val', 
                '$largoCm_val', '$anchoCm_val', '$altoCm_val', '$largoPlg_val', '$anchoPlg_val', '$altoPlg_val', 
                '$piesCubicos_val', '$metrosCubicos_val', '$libras_val', '$kilogramos_val', '$valorFactura_val'
            )";

            mysqli_query($con, $sql_detalle);
        }


        if (!empty($_POST['incrementable'])) {
            $incrementables = $_POST['incrementable'];
            $incrementablesUsd = $_POST['incrementableUsd'];
            $incrementablesMx = $_POST['incrementableMx'];

            for ($i = 0; $i < count($incrementables); $i++) {
                $incrementable = mysqli_real_escape_string($con, $incrementables[$i]);
                $incrementableUsd = mysqli_real_escape_string($con, $incrementablesUsd[$i]);
                $incrementableMx = mysqli_real_escape_string($con, $incrementablesMx[$i]);

                $sql_incrementable = "INSERT INTO incrementablesFtl (idFtl, incrementable, incrementableUsd, incrementableMx) 
                                      VALUES ('$idFtl', '$incrementable', '$incrementableUsd', '$incrementableMx')";

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
                $iva = isset($ivaGasto[$i]) ? 1 : 0; // Si el checkbox está marcado

                $sql_gasto = "INSERT INTO gastosftl (idFtl, conceptoGasto, montoGasto, ivaGasto) VALUES ('$idFtl', '$concepto', '$monto', '$iva')";
                mysqli_query($con, $sql_gasto);
            }
        }


        $_SESSION['message'] = "Se registró exitosamente";
        header("Location: ftl.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Error, contacte a soporte";
        header("Location: ftl.php");
        exit(0);
    }
}
