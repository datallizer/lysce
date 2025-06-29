<?php
require 'dbcon.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    $delete_gastos = "DELETE FROM gastosftl WHERE idFtl='$id'";
    $delete_incrementables = "DELETE FROM incrementablesftl WHERE idFtl='$id'";
    $delete_descripcion = "DELETE FROM descripcionmercanciasftl WHERE idFtl='$id'";
    $delete_servicio = "DELETE FROM servicioftl WHERE idFtl='$id'";

    $delete_ftl = "DELETE FROM ftl WHERE id='$id'";

    $query_run_gastos = mysqli_query($con, $delete_gastos);
    $query_run_incrementables = mysqli_query($con, $delete_incrementables);
    $query_run_descripcion = mysqli_query($con, $delete_descripcion);
    $query_run_servicio = mysqli_query($con, $delete_servicio);
    $query_run_ftl = mysqli_query($con, $delete_ftl);

    if ($query_run_gastos && $query_run_incrementables && $query_run_descripcion && $query_run_servicio && $query_run_ftl) {
        $_SESSION['alert'] = [
            'title' => 'ELIMINADO EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: ftl.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: ftl.php");
        exit(0);
    }
}


if (isset($_POST['save'])) {
    function get_post_value($con, $key, $default = '0')
    {
        return isset($_POST[$key]) && trim($_POST[$key]) !== '' ? mysqli_real_escape_string($con, $_POST[$key]) : $default;
    }

    function get_post_array_value($con, $array, $index, $default = '0')
    {
        return isset($array[$index]) && trim($array[$index]) !== '' ? mysqli_real_escape_string($con, $array[$index]) : $default;
    }

    $identificador = get_post_value($con, 'identificador');
    $email = $_SESSION['email'];
    $fecha = get_post_value($con, 'fecha', '');
    $idCliente = get_post_value($con, 'idCliente');
    $idOrigen = get_post_value($con, 'idOrigen');
    $idDestino = get_post_value($con, 'idAduana');
    $idDestinoFinal = get_post_value($con, 'idDestino');
    $distanciaOrigenDestinoMillas = get_post_value($con, 'distanciaOrigenDestinoMillas');
    $distanciaOrigenDestinoKms = get_post_value($con, 'distanciaOrigenDestinoKms');
    $tiempoRecorridoOrigenDestino = get_post_value($con, 'tiempoRecorridoOrigenDestino');
    $servicio = get_post_value($con, 'servicio');
    $totalFt3 = get_post_value($con, 'totalFt3');
    $totalM3 = get_post_value($con, 'totalM3');
    $distanciaDestinoFinalMillas = get_post_value($con, 'distanciaDestinoFinalMillas');
    $distanciaDestinoFinalKms = get_post_value($con, 'distanciaDestinoFinalKms');
    $tiempoRecorridoDestinoFinal = get_post_value($con, 'tiempoRecorridoDestinoFinal');
    $operador = get_post_value($con, 'operador', '');
    $unidad = get_post_value($con, 'unidad', '');
    $moneda = get_post_value($con, 'moneda', '');
    $valorMoneda = get_post_value($con, 'valorMoneda');
    $pesoMercanciaLbs = get_post_value($con, 'pesoMercanciaLbs');
    $pesoMercanciaKgs = get_post_value($con, 'pesoMercanciaKgs');
    $totalBultos = get_post_value($con, 'totalBultos');
    $valorMercancia = get_post_value($con, 'valorMercancia');
    $valorComercial = get_post_value($con, 'valorComercial');
    $subtotalFlete = get_post_value($con, 'subtotalFlete');
    $impuestosFlete = get_post_value($con, 'impuestosFlete');
    $retencionFlete = get_post_value($con, 'retencionFlete');
    $totalCotizacionNumero = get_post_value($con, 'totalCotizacionNumero');
    $totalCotizacionTexto = get_post_value($con, 'totalCotizacionTexto', '');
    $observaciones = get_post_value($con, 'observaciones', '');
    $totalIncrementableUsd = get_post_value($con, 'totalIncrementableUsd');
    $totalIncrementableMx = get_post_value($con, 'totalIncrementableMx');
    $tipoFtl = get_post_value($con, 'tipoFtl', '');
    $porcentajeSeguro = get_post_value($con, 'porcentajeSeguro', '0%');

    $sql = "INSERT INTO ftl (
        identificador, fecha, idCliente, idOrigen, idDestino, idDestinoFinal,
        distanciaOrigenDestinoMillas, distanciaOrigenDestinoKms, tiempoRecorridoOrigenDestino, servicio, 
        totalFt3, totalM3, distanciaDestinoFinalMillas, distanciaDestinoFinalKms, tiempoRecorridoDestinoFinal, 
        operador, unidad, moneda, valorMoneda, pesoMercanciaLbs, pesoMercanciaKgs, totalBultos, 
        valorMercancia, valorComercial, subtotalFlete, impuestosFlete, retencionFlete, totalCotizacionNumero, totalCotizacionTexto, observaciones, totalIncrementableUsd, totalIncrementableMx, tipoFtl, porcentajeSeguro, asignado
    ) VALUES (
        '$identificador', '$fecha', '$idCliente', '$idOrigen', '$idDestino', '$idDestinoFinal',
        '$distanciaOrigenDestinoMillas', '$distanciaOrigenDestinoKms', '$tiempoRecorridoOrigenDestino', '$servicio', 
        '$totalFt3', '$totalM3', '$distanciaDestinoFinalMillas', '$distanciaDestinoFinalKms', '$tiempoRecorridoDestinoFinal', 
        '$operador', '$unidad', '$moneda', '$valorMoneda', '$pesoMercanciaLbs', '$pesoMercanciaKgs', '$totalBultos', 
        '$valorMercancia', '$valorComercial', '$subtotalFlete', '$impuestosFlete', '$retencionFlete', '$totalCotizacionNumero', '$totalCotizacionTexto', '$observaciones', '$totalIncrementableUsd', '$totalIncrementableMx', '$tipoFtl', '$porcentajeSeguro', '$email'
    )";

    $query_run = mysqli_query($con, $sql);

    $sql_carta = "INSERT INTO ftl (
        tipoFtl, folio, idCliente, idOrigen, idDestino, idDestinoFinal, usuario
    ) VALUES (
        '$tipoFtl', '$identificador', '$idCliente', '$idOrigen', '$idDestino', '$idDestinoFinal', '$email'
    )";

    $query_carta_run = mysqli_query($con, $sql_carta);

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
            $cantidad_val = get_post_array_value($con, $cantidad, $i);
            $unidadMedida_val = get_post_array_value($con, $unidadMedida, $i, '');
            $nmfc_val = get_post_array_value($con, $nmfc, $i);
            $descripcion_val = get_post_array_value($con, $descripcion, $i, '');
            $largoCm_val = get_post_array_value($con, $largoCm, $i);
            $anchoCm_val = get_post_array_value($con, $anchoCm, $i);
            $altoCm_val = get_post_array_value($con, $altoCm, $i);
            $largoPlg_val = get_post_array_value($con, $largoPlg, $i);
            $anchoPlg_val = get_post_array_value($con, $anchoPlg, $i);
            $altoPlg_val = get_post_array_value($con, $altoPlg, $i);
            $piesCubicos_val = get_post_array_value($con, $piesCubicos, $i);
            $metrosCubicos_val = get_post_array_value($con, $metrosCubicos, $i);
            $libras_val = get_post_array_value($con, $libras, $i);
            $kilogramos_val = get_post_array_value($con, $kilogramos, $i);
            $valorFactura_val = get_post_array_value($con, $valorFactura, $i);

            $sql_detalle = "INSERT INTO descripcionmercanciasftl (
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

        if (!empty($_POST['conceptoServicio'])) {
            $conceptoServicio = $_POST['conceptoServicio'];
            $tiempoServicio = $_POST['tiempoServicio'];

            for ($i = 0; $i < count($conceptoServicio); $i++) {
                $servicios = get_post_array_value($con, $conceptoServicio, $i, '');
                $tiempo = get_post_array_value($con, $tiempoServicio, $i);

                $sql_servicio = "INSERT INTO servicioftl (idFtl, conceptoServicio, tiempoServicio) 
                                      VALUES ('$idFtl', '$servicios', '$tiempo')";

                mysqli_query($con, $sql_servicio);
            }
        }

        if (!empty($_POST['incrementable'])) {
            $incrementables = $_POST['incrementable'];
            $incrementablesUsd = $_POST['incrementableUsd'];
            $incrementablesMx = $_POST['incrementableMx'];

            for ($i = 0; $i < count($incrementables); $i++) {
                $incrementable = get_post_array_value($con, $incrementables, $i, '');
                $incrementableUsd = get_post_array_value($con, $incrementablesUsd, $i);
                $incrementableMx = get_post_array_value($con, $incrementablesMx, $i);

                $sql_incrementable = "INSERT INTO incrementablesftl (idFtl, incrementable, incrementableUsd, incrementableMx) 
                                      VALUES ('$idFtl', '$incrementable', '$incrementableUsd', '$incrementableMx')";

                mysqli_query($con, $sql_incrementable);
            }
        }

        if (!empty($_POST['conceptoGasto'])) {
            $conceptoGasto = $_POST['conceptoGasto'];
            $montoGasto = $_POST['montoGasto'];
            $ivaGasto = isset($_POST['ivaGasto']) ? $_POST['ivaGasto'] : [];

            for ($i = 0; $i < count($conceptoGasto); $i++) {
                $concepto = get_post_array_value($con, $conceptoGasto, $i, '');
                $monto = get_post_array_value($con, $montoGasto, $i);
                $iva = isset($ivaGasto[$i]) ? 1 : 0;

                $sql_gasto = "INSERT INTO gastosftl (idFtl, conceptoGasto, montoGasto, ivaGasto) VALUES ('$idFtl', '$concepto', '$monto', '$iva')";
                mysqli_query($con, $sql_gasto);
            }
        }

        $_SESSION['alert'] = [
            'title' => 'COTIZACIÓN REGISTRADA EXITOSAMENTE',
            'icon' => 'success'
        ];
        header("Location: ftl.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Contacte a soporte',
            'title' => 'ERROR AL CREAR LA COTIZACIÓN',
            'icon' => 'error'
        ];
        header("Location: ftl.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $identificador = mysqli_real_escape_string($con, $_POST['identificador']);
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
    $tipoFtl = mysqli_real_escape_string($con, $_POST['tipoFtl']);
    $porcentajeSeguro = mysqli_real_escape_string($con, $_POST['porcentajeSeguro']);

    $sql = "UPDATE ftl SET 
        identificador = '$identificador',
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
        tipoFtl = '$tipoFtl',
        porcentajeSeguro = '$porcentajeSeguro'
    WHERE id = '$id'";

    $query_run = mysqli_query($con, $sql);

    if ($query_run) {
        // Actualizar tablas relacionadas

        // Eliminar registros actuales y volver a insertar (Alternativa: UPDATE)
        mysqli_query($con, "DELETE FROM descripcionmercanciasftl WHERE idFtl = '$id'");
        mysqli_query($con, "DELETE FROM servicioftl WHERE idFtl = '$id'");
        mysqli_query($con, "DELETE FROM incrementablesftl WHERE idFtl = '$id'");
        mysqli_query($con, "DELETE FROM gastosftl WHERE idFtl = '$id'");

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

            // Actualizar los registros en la tabla descripcionmercanciasftl
            $sql_detalle = "INSERT INTO descripcionmercanciasftl (
                idFtl, cantidad, unidadMedida, nmfc, descripcion,
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

                $sql_servicio = "INSERT INTO servicioftl (idFtl, conceptoServicio, tiempoServicio)
                                 VALUES ('$id', '$concepto_val', '$tiempo_val')";
                mysqli_query($con, $sql_servicio);
            }
        }

        if (!empty($_POST['incrementable']) && is_array($_POST['incrementable'])) {
            foreach ($_POST['incrementable'] as $i => $incrementable) {
                $incrementable_val = mysqli_real_escape_string($con, $incrementable);
                $usd_val = mysqli_real_escape_string($con, $_POST['incrementableUsd'][$i]);
                $mx_val = mysqli_real_escape_string($con, $_POST['incrementableMx'][$i]);

                $sql_incrementable = "INSERT INTO incrementablesftl (idFtl, incrementable, incrementableUsd, incrementableMx)
                                      VALUES ('$id', '$incrementable_val', '$usd_val', '$mx_val')";
                mysqli_query($con, $sql_incrementable);
            }
        }


        if (!empty($_POST['conceptoGasto']) && is_array($_POST['conceptoGasto'])) {
            foreach ($_POST['conceptoGasto'] as $i => $gasto) {
                $concepto_val = mysqli_real_escape_string($con, $gasto);
                $monto_val = mysqli_real_escape_string($con, $_POST['montoGasto'][$i]);
                $iva_val = isset($_POST['ivaGasto'][$i]) ? 1 : 0;

                $sql_gasto = "INSERT INTO gastosftl (idFtl, conceptoGasto, montoGasto, ivaGasto)
                              VALUES ('$id', '$concepto_val', '$monto_val', '$iva_val')";
                mysqli_query($con, $sql_gasto);
            }
        }


        $_SESSION['alert'] = ['title' => "COTIZACIÓN ACTUALIZADA EXITOSAMENTE", 'icon' => 'success'];
        header("Location: ftl.php");
        exit;
    } else {
        $_SESSION['alert'] = ['title' => 'ERROR AL ACTUALIZAR', 'icon' => 'error'];
        header("Location: ftl.php");
        exit;
    }
}
