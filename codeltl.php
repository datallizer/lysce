<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    $delete_gastos = "DELETE FROM gastosltl WHERE idLtl='$id'";
    $delete_incrementables = "DELETE FROM incrementablesltl WHERE idLtl='$id'";
    $delete_descripcion = "DELETE FROM descripcionmercanciasltl WHERE idLtl='$id'";
    $delete_servicio = "DELETE FROM servicioltl WHERE idLtl='$id'";

    $delete_ltl = "DELETE FROM ltl WHERE id='$id'";

    $query_run_gastos = mysqli_query($con, $delete_gastos);
    $query_run_incrementables = mysqli_query($con, $delete_incrementables);
    $query_run_descripcion = mysqli_query($con, $delete_descripcion);
    $query_run_servicio = mysqli_query($con, $delete_servicio);
    $query_run_ltl = mysqli_query($con, $delete_ltl);

    if ($query_run_gastos && $query_run_incrementables && $query_run_descripcion && $query_run_servicio && $query_run_ltl) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: ltl.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: ltl.php");
        exit(0);
    }
}


if (isset($_POST['save'])) {
    $email = $_SESSION['email'];
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
    $subtotalFlete = mysqli_real_escape_string($con, $_POST['subtotalFlete']);
    $impuestosFlete = mysqli_real_escape_string($con, $_POST['impuestosFlete']);
    $retencionFlete = mysqli_real_escape_string($con, $_POST['retencionFlete']);
    $totalCotizacionNumero = mysqli_real_escape_string($con, $_POST['totalCotizacionNumero']);
    $totalCotizacionTexto = mysqli_real_escape_string($con, $_POST['totalCotizacionTexto']);
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);
    $totalIncrementableUsd = mysqli_real_escape_string($con, $_POST['totalIncrementableUsd']);
    $totalIncrementableMx = mysqli_real_escape_string($con, $_POST['totalIncrementableMx']);
    $tipoltl = mysqli_real_escape_string($con, $_POST['tipoltl']);
    $porcentajeSeguro = mysqli_real_escape_string($con, $_POST['porcentajeSeguro']);

    $sql = "INSERT INTO ltl (
        fecha, idCliente, idOrigen, idDestino, idDestinoFinal,
        distanciaOrigenDestinoMillas, distanciaOrigenDestinoKms, tiempoRecorridoOrigenDestino, servicio, 
        totalFt3, totalM3, distanciaDestinoFinalMillas, distanciaDestinoFinalKms, tiempoRecorridoDestinoFinal, 
        operador, unidad, moneda, valorMoneda, pesoMercanciaLbs, pesoMercanciaKgs, totalBultos, 
        valorMercancia, valorComercial, subtotalFlete, impuestosFlete, retencionFlete, totalCotizacionNumero, totalCotizacionTexto, observaciones, totalIncrementableUsd, totalIncrementableMx, tipoltl, porcentajeSeguro, asignado
    ) VALUES (
        '$fecha', '$idCliente', '$idOrigen', '$idDestino', '$idDestinoFinal',
        '$distanciaOrigenDestinoMillas', '$distanciaOrigenDestinoKms', '$tiempoRecorridoOrigenDestino', '$servicio', 
        '$totalFt3', '$totalM3', '$distanciaDestinoFinalMillas', '$distanciaDestinoFinalKms', '$tiempoRecorridoDestinoFinal', 
        '$operador', '$unidad', '$moneda', '$valorMoneda', '$pesoMercanciaLbs', '$pesoMercanciaKgs', '$totalBultos', 
        '$valorMercancia', '$valorComercial', '$subtotalFlete', '$impuestosFlete', '$retencionFlete', '$totalCotizacionNumero', '$totalCotizacionTexto', '$observaciones', '$totalIncrementableUsd', '$totalIncrementableMx', '$tipoltl', '$porcentajeSeguro', '$email'
    )";

    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        // Obtener el ID insertado en la tabla `ltl`
        $idLtl = mysqli_insert_id($con);

        // Insertar los datos de cada fila en `descripcionMercanciasltl`
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

            $sql_detalle = "INSERT INTO descripcionmercanciasltl (
                idLtl, cantidad, unidadMedida, nmfc, descripcion, 
                largoCm, anchoCm, altoCm, largoPlg, anchoPlg, altoPlg, 
                piesCubicos, metrosCubicos, libras, kilogramos, valorFactura
            ) VALUES (
                '$idLtl', '$cantidad_val', '$unidadMedida_val', '$nmfc_val', '$descripcion_val', 
                '$largoCm_val', '$anchoCm_val', '$altoCm_val', '$largoPlg_val', '$anchoPlg_val', '$altoPlg_val', 
                '$piesCubicos_val', '$metrosCubicos_val', '$libras_val', '$kilogramos_val', '$valorFactura_val'
            )";

            mysqli_query($con, $sql_detalle);
        }

        if (!empty($_POST['conceptoServicio'])) {
            $conceptoServicio = $_POST['conceptoServicio'];
            $tiempoServicio = $_POST['tiempoServicio'];

            for ($i = 0; $i < count($conceptoServicio); $i++) {
                $servicios = mysqli_real_escape_string($con, $conceptoServicio[$i]);
                $tiempo = mysqli_real_escape_string($con, $tiempoServicio[$i]);

                $sql_servicio = "INSERT INTO servicioltl (idLtl, conceptoServicio, tiempoServicio) 
                                      VALUES ('$idLtl', '$servicios', '$tiempo')";

                mysqli_query($con, $sql_servicio);
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

                $sql_incrementable = "INSERT INTO incrementablesltl (idLtl, incrementable, incrementableUsd, incrementableMx) 
                                      VALUES ('$idLtl', '$incrementable', '$incrementableUsd', '$incrementableMx')";

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

                $sql_gasto = "INSERT INTO gastosltl (idLtl, conceptoGasto, montoGasto, ivaGasto) VALUES ('$idLtl', '$concepto', '$monto', '$iva')";
                mysqli_query($con, $sql_gasto);
            }
        }

        $_SESSION['alert'] = [
            'title' => 'COTIZACIÓN REGISTRADA EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: ltl.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL CREAR LA COTIZACIÓN',
            'icon' => 'error'
        ];
        header("Location: ltl.php");
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
    $pesoMercanciaLbs = mysqli_real_escape_string($con, $_POST['pesoMercanciaLbs']);
    $pesoMercanciaKgs = mysqli_real_escape_string($con, $_POST['pesoMercanciaKgs']);
    $totalBultos = mysqli_real_escape_string($con, $_POST['totalBultos']);
    $valorMercancia = mysqli_real_escape_string($con, $_POST['valorMercancia']);
    $valorComercial = mysqli_real_escape_string($con, $_POST['valorComercial']);
    $subtotalFlete = mysqli_real_escape_string($con, $_POST['subtotalFlete']);
    $impuestosFlete = mysqli_real_escape_string($con, $_POST['impuestosFlete']);
    $retencionFlete = mysqli_real_escape_string($con, $_POST['retencionFlete']);
    $totalCotizacionNumero = mysqli_real_escape_string($con, $_POST['totalCotizacionNumero']);
    $totalCotizacionTexto = mysqli_real_escape_string($con, $_POST['totalCotizacionTexto']);
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);
    $totalIncrementableUsd = mysqli_real_escape_string($con, $_POST['totalIncrementableUsd']);
    $totalIncrementableMx = mysqli_real_escape_string($con, $_POST['totalIncrementableMx']);
    $tipoLtl = mysqli_real_escape_string($con, $_POST['tipoLtl']);
    $porcentajeSeguro = mysqli_real_escape_string($con, $_POST['porcentajeSeguro']);

    $sql = "UPDATE ltl SET 
        fecha = '$fecha',
        idCliente = '$idCliente',
        idOrigen = '$idOrigen',
        idDestino = '$idDestino',
        idDestinoFinal = '$idDestinoFinal',
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
        pesoMercanciaLbs = '$pesoMercanciaLbs',
        pesoMercanciaKgs = '$pesoMercanciaKgs',
        totalBultos = '$totalBultos',
        valorMercancia = '$valorMercancia',
        valorComercial = '$valorComercial',
        subtotalFlete = '$subtotalFlete',
        impuestosFlete = '$impuestosFlete',
        retencionFlete = '$retencionFlete',
        totalCotizacionNumero = '$totalCotizacionNumero',
        totalCotizacionTexto = '$totalCotizacionTexto',
        observaciones = '$observaciones',
        totalIncrementableUsd = '$totalIncrementableUsd',
        totalIncrementableMx = '$totalIncrementableMx',
        tipoLtl = '$tipoLtl',
        porcentajeSeguro = '$porcentajeSeguro'
    WHERE id = '$id'";

    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        // Actualizar tablas relacionadas

        // Eliminar registros actuales y volver a insertar (Alternativa: UPDATE)
        mysqli_query($con, "DELETE FROM descripcionmercanciasltl WHERE idLtl = '$id'");
        mysqli_query($con, "DELETE FROM servicioltl WHERE idLtl = '$id'");
        mysqli_query($con, "DELETE FROM incrementablesltl WHERE idLtl = '$id'");
        mysqli_query($con, "DELETE FROM gastosltl WHERE idLtl = '$id'");

        // Insertar nueva información
        foreach ($_POST['cantidad'] as $i => $cantidad) {
            $cantidad_val = mysqli_real_escape_string($con, $cantidad);
            $unidadMedida_val = mysqli_real_escape_string($con, $_POST['unidadMedida'][$i]);
            $nmfc_val = mysqli_real_escape_string($con, $_POST['nmfc'][$i]);
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

            // Actualizar los registros en la tabla descripcionmercanciasltl
            $sql_detalle = "INSERT INTO descripcionmercanciasltl (
                idLtl, cantidad, unidadMedida, nmfc, descripcion,
                largoCm, anchoCm, altoCm, largoPlg, anchoPlg, altoPlg,
                piesCubicos, metrosCubicos, libras, kilogramos, valorFactura
            ) VALUES (
                '$id', '$cantidad_val', '$unidadMedida_val', '$nmfc_val', '$descripcion_val',
                '$largoCm_val', '$anchoCm_val', '$altoCm_val', '$largoPlg_val', '$anchoPlg_val', '$altoPlg_val',
                '$piesCubicos_val', '$metrosCubicos_val', '$libras_val', '$kilogramos_val', '$valorFactura_val'
            )";
            mysqli_query($con, $sql_detalle);
        }

        if (!empty($_POST['conceptoServicio']) && is_array($_POST['conceptoServicio'])) {
            foreach ($_POST['conceptoServicio'] as $i => $concepto) {
                $concepto_val = mysqli_real_escape_string($con, $concepto);
                $tiempo_val = mysqli_real_escape_string($con, $_POST['tiempoServicio'][$i]);

                $sql_servicio = "INSERT INTO servicioltl (idLtl, conceptoServicio, tiempoServicio)
                                 VALUES ('$id', '$concepto_val', '$tiempo_val')";
                mysqli_query($con, $sql_servicio);
            }
        }


        if (!empty($_POST['incrementable']) && is_array($_POST['incrementable'])) {
            foreach ($_POST['incrementable'] as $i => $incrementable) {
                $incrementable_val = mysqli_real_escape_string($con, $incrementable);
                $usd_val = mysqli_real_escape_string($con, $_POST['incrementableUsd'][$i]);
                $mx_val = mysqli_real_escape_string($con, $_POST['incrementableMx'][$i]);

                $sql_incrementable = "INSERT INTO incrementablesltl (idLtl, incrementable, incrementableUsd, incrementableMx)
                                      VALUES ('$id', '$incrementable_val', '$usd_val', '$mx_val')";
                mysqli_query($con, $sql_incrementable);
            }
        }


        if (!empty($_POST['conceptoGasto']) && is_array($_POST['conceptoGasto'])) {
            foreach ($_POST['conceptoGasto'] as $i => $gasto) {
                $concepto_val = mysqli_real_escape_string($con, $gasto);
                $monto_val = mysqli_real_escape_string($con, $_POST['montoGasto'][$i]);
                $iva_val = isset($_POST['ivaGasto'][$i]) ? 1 : 0;

                $sql_gasto = "INSERT INTO gastosltl (idLtl, conceptoGasto, montoGasto, ivaGasto)
                              VALUES ('$id', '$concepto_val', '$monto_val', '$iva_val')";
                mysqli_query($con, $sql_gasto);
            }
        }


        $_SESSION['alert'] = ['title' => "COTIZACIÓN ACTUALIZADA EXITOSAMENTE", 'icon' => 'success'];
        header("Location: ltl.php");
        exit;
    } else {
        $_SESSION['alert'] = ['title' => 'ERROR AL ACTUALIZAR', 'icon' => 'error'];
        header("Location: ltl.php");
        exit;
    }
}
