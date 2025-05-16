<?php

session_start();
require 'dbcon.php';
require 'vendor/autoload.php';
require 'dompdf/src/Dompdf.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id'])) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $id = mysqli_real_escape_string($con, $_GET['id']);
    // Configurar opciones de DomPDF
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);


    $query = "SELECT 
    a.*,
    a.id,
    a.fecha,
    c.cliente AS cliente_nombre,
    c.calle AS cliente_calle,
    c.numexterior AS cliente_numexterior,
    c.numinterior AS cliente_numinterior,
    c.colonia AS cliente_colonia,
    c.city AS cliente_city,
    c.cpostal AS cliente_cpostal,
    c.state AS cliente_state,
    c.pais AS cliente_pais,
    c.telefono AS cliente_telefono,
    c.contacto AS cliente_contacto,
    c.rfc AS cliente_rfc,
    c.correo AS cliente_correo,
    p_origen.cliente AS origen_nombre,
    p_origen.calle AS origen_domicilio,
    p_origen.numexterior AS origen_exterior,
    p_origen.numinterior AS origen_interior,
    p_origen.state AS origen_estado,
    p_origen.colonia AS origen_fraccionamiento,
    p_origen.city AS origen_ciudad,
    p_origen.cpostal AS origen_postal,
    p_origen.pais AS origen_country,
    p_origen.telefono AS origen_phone,
    p_origen.contacto AS origen_contact,
    p_origen.correo AS origen_email,
    p_origen.rfc AS origen_tax,
    p_destino.cliente AS destino_nombre,
    p_destino.calle AS destino_domicilio,
    p_destino.numexterior AS destino_exterior,
    p_destino.numinterior AS destino_interior,
    p_destino.state AS destino_estado,
    p_destino.colonia AS destino_fraccionamiento,
    p_destino.city AS destino_ciudad,
    p_destino.cpostal AS destino_postal,
    p_destino.pais AS destino_country,
    p_destino.telefono AS destino_phone,
    p_destino.contacto AS destino_contact,
    p_destino.correo AS destino_email,
    p_destino.rfc AS destino_tax,
    p_final.cliente AS final_nombre,
    p_final.calle AS final_domicilio,
    p_final.numexterior AS final_exterior,
    p_final.numinterior AS final_interior,
    p_final.state AS final_estado,
    p_final.colonia AS final_fraccionamiento,
    p_final.city AS final_ciudad,
    p_final.cpostal AS final_postal,
    p_final.pais AS final_country,
    p_final.telefono AS final_phone,
    p_final.contacto AS final_contact,
    p_final.correo AS final_email,
    p_final.rfc AS final_tax
FROM 
    lcl a
LEFT JOIN 
    clientes c ON a.idCliente = c.id
LEFT JOIN 
    clientes p_origen ON a.idOrigen = p_origen.id
LEFT JOIN 
    clientes p_destino ON a.idDestino = p_destino.id
LEFT JOIN
    clientes p_final ON a.idDestinoFinal = p_final.id
WHERE 
    a.id = $id;
";

    $query_mercancias = "SELECT * FROM descripcionmercanciaslcl WHERE idLcl = $id";
    $resultado_mercancias = mysqli_query($con, $query_mercancias);

    $mercancias_html = '';
    if (mysqli_num_rows($resultado_mercancias) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_mercancias)) {
            $mercancias_html .= '
        <tr>
            <td>
                <p>' . htmlspecialchars($row['cantidad']) . '</p>
            </td>
            <td>
                <p>' . htmlspecialchars($row['unidadMedida']) . '</p>
            </td>
            <td>' . htmlspecialchars($row['descripcion']) . '</td>
            <td>
                <p>' . htmlspecialchars($row['largoCm']) . ' x ' . htmlspecialchars($row['anchoCm']) . ' x ' . htmlspecialchars($row['altoCm']) . ' inches ' . number_format($row['piesCubicos'], 2, '.', ',') . ' ft3</p>
                <p>' . htmlspecialchars($row['largoPlg']) . ' x ' . htmlspecialchars($row['anchoPlg']) . ' x ' . htmlspecialchars($row['altoPlg']) . ' cm ' . number_format($row['metrosCubicos'], 2, '.', ',') . ' m3</p>
            </td>
            <td>
                <p>' . number_format($row['libras'], 2, '.', ',') . ' Lbs</p>
                <p>' . number_format($row['kilogramos'], 2, '.', ',') . ' Kgs</p>
            </td>
            <td>$' . number_format($row['valorFactura'], 2, '.', ',') . '</td>
        </tr>';
        }
    }

    $query_origen = "SELECT * FROM gastosorigenlcl WHERE idLcl = $id";
    $resultado_origen = mysqli_query($con, $query_origen);

    $origen_html = '';
    $origen_count = 0;
    if (mysqli_num_rows($resultado_origen) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_origen)) {
            $origen_html .= '
            <tr>
                <td>' . htmlspecialchars($row['gastosOrigen']) . '</td>
                <td>$' . number_format($row['euros'], 2, '.', ',') . '</td>
                <td>$' . number_format($row['equivalenciaOrigen'], 2, '.', ',') . '</td>
                <td>$' . number_format($row['usdOrigen'], 2, '.', ',') . '</td>
            </tr>';
            $origen_count++;
        }
    }

    $query_destino = "SELECT * FROM gastosdestinolcl WHERE idLcl = $id";
    $resultado_destino = mysqli_query($con, $query_destino);

    $destino_html = '';
    $destino_count = 0;
    if (mysqli_num_rows($resultado_destino) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_destino)) {
            $destino_html .= '
            <tr>
                <td>' . htmlspecialchars($row['gastoDestino']) . '</td>
                <td>$' . number_format($row['usdDestino'], 2, '.', ',') . '</td>
                <td>$' . number_format($row['mxnDestino'], 2, '.', ',') . '</td>
            </tr>';
            $destino_count++;
        }
    }

    // Calcular la diferencia y agregar filas vacías
    $max_filas = max($origen_count, $destino_count);

    for ($i = $origen_count; $i < $max_filas; $i++) {
        $origen_html .= '
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        ';
    }

    for ($i = $destino_count; $i < $max_filas; $i++) {
        $destino_html .= '
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>';
    }


    $query_incrementables = "SELECT incrementable, incrementableUSD, incrementableMx FROM incrementableslcl WHERE idLcl = $id";
    $resultado_incrementables = mysqli_query($con, $query_incrementables);

    $incrementables_html = '';
    if (mysqli_num_rows($resultado_incrementables) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_incrementables)) {
            $incrementables_html .= '
        <tr>
            <td>' . htmlspecialchars($row['incrementable']) . '</td>
            <td>$' . number_format($row['incrementableUSD'], 2, '.', ',') . ' USD</td>
            <td>$' . number_format($row['incrementableMx'], 2, '.', ',') . ' MXN</td>
        </tr>';
        }
    }

    $query_gasto = "SELECT * FROM gastoslcl WHERE idLcl = $id";
    $resultado_gasto = mysqli_query($con, $query_gasto);

    $gasto_html = '';
    if (mysqli_num_rows($resultado_gasto) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_gasto)) {
            // Excluir la fila si conceptoGasto es "Seguro de tránsito de mercancía" y montoGasto es "0"
            if ($row['conceptoGasto'] === "Seguro de tránsito de mercancía" && floatval($row['montoGasto']) == 0) {
                continue; // Saltar esta iteración del bucle
            }

            $gasto_html .= '
            <tr>
                <td>' . htmlspecialchars($row['conceptoGasto']) . '</td>
                <td>$' . number_format($row['montoGasto'], 2, '.', ',') . '</td>
            </tr>';
        }
    }


    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        foreach ($query_run as $registro) {
            $html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="https://lysce.com.mx/images/ics.ico">
    <title>Cotización marítimo LCL | LYSCE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
        }
        .logo {
            width: 70%;
        }
        .header {
            text-align: center;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table td {
            border: 1px solid #858585;
            padding: 0px 5px;
        }
            .bg-secondary {
            background-color: #fff1c7;
        }
        .bg-warning {
            background-color: #ffc107;
        }
        .text-center {
            text-align: center;
        }
        p{
            margin:0;
            padding:0;
        }
    </style>
</head>

<body>
    <table class="table" style="border: 0px;">
        <tr style="border: 0px;">
            <td class="text-center" style="width: 20%;border: 0px;">
                <img class="logo" src="https://lysce.com.mx/assets/img/lysce/lysce_logo.png">
                <p>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</p>
            </td>
            <td style="width: 40%;border: 0px;">
                <h2><b>GRUPO LYSCE S.C.</b></h2>
                <p style="margin: 0;">R.F.C GLY170421ES6</p>
                <p style="margin: 0;">Sierra del Laurel 312 piso 2, Bosques del Prado Norte,<br> Aguascalientes, Ags. C.P. 20127</p>
                <p style="margin: 0;">Tel / Fax +52 (449) 300 3265</p>
            </td>
            <td style="width: 30%;border: 0px;">
                <h2 style="margin-bottom:10px;"><b>COTIZACIÓN</b></h2>
                <p>Folio: <span style="color:rgb(159, 41, 41);text-transform:uppercase;">' . $registro['identificador'] . '</span></p>
                <p>Aguascalientes, Ags a ' . $registro['fecha'] . '</p>
                <br>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <p class="text-center bg-warning" style="border: 1px solid #666666;margin: 5px 0px;padding:5px;"><b>' . $registro['tipoLcl'] . '</b></p>
    <table class="table">
        <tr>
            <td colspan="3"><b>Cliente</b>
                <p>' . $registro['cliente_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['cliente_calle'] . ' #' . $registro['cliente_numexterior'] . ' ' . $registro['cliente_numinterior'] . ', ' . $registro['cliente_colonia'] . ', ' . $registro['cliente_city'] . ', ' . $registro['cliente_state'] . ', ' . $registro['cliente_pais'] . ', ' . $registro['cliente_cpostal'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['cliente_telefono'] . '</p>
                <p><b>Email:</b> ' . $registro['cliente_correo'] . '</p>
                <p><b>Contacto:</b> ' . $registro['cliente_contacto'] . '</p>
                <p><b>RFC:</b> ' . $registro['cliente_rfc'] . '</p>
            </td>
        </tr>
        <tr>
            <td>
                <b>Origen:</b>
                <p>' . $registro['origen_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['origen_domicilio'] . ' #' . $registro['origen_exterior'] . ' ' . $registro['origen_interior'] . ', ' . $registro['origen_fraccionamiento'] . ', ' . $registro['origen_ciudad'] . ', ' . $registro['origen_estado'] . ', ' . $registro['origen_country'] . ' ' . $registro['origen_postal'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['origen_phone'] . '</p>
                <p><b>Email:</b> ' . $registro['origen_email'] . '</p>
                <p><b>Contacto:</b> ' . $registro['origen_contact'] . '</p>
                <p><b>RFC:</b> ' . $registro['origen_tax'] . '</p>
            </td>
            <td>
                <b>Destino en frontera:</b>
                <p>' . $registro['destino_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['destino_domicilio'] . ' #' . $registro['destino_exterior'] . ' ' . $registro['destino_interior'] . ', ' . $registro['destino_fraccionamiento'] . ', ' . $registro['destino_ciudad'] . ', ' . $registro['destino_estado'] . ', ' . $registro['destino_country'] . ' ' . $registro['destino_postal'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['destino_phone'] . '</p>
                <p><b>Email:</b> ' . $registro['destino_email'] . '</p>
                <p><b>Contacto:</b> ' . $registro['destino_contact'] . '</p>
                <p><b>RFC:</b> ' . $registro['destino_tax'] . '</p>
            </td>
            <td>
                <b>Destino Final:</b>
                <p>' . $registro['final_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['final_domicilio'] . ' #' . $registro['final_exterior'] . ' ' . $registro['final_interior'] . ', ' . $registro['final_fraccionamiento'] . ', ' . $registro['final_ciudad'] . ', ' . $registro['final_estado'] . ', ' . $registro['final_country'] . ' ' . $registro['final_postal'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['final_phone'] . '</p>
                <p><b>Email:</b> ' . $registro['final_email'] . '</p>
                <p><b>Contacto:</b> ' . $registro['final_contact'] . '</p>
                <p><b>RFC:</b> ' . $registro['final_tax'] . '</p>
                 
            </td>
        </tr>
         <tr style="border: 0px;">
            <td colspan="3" style="border: 0px;">
            <table style="width:100%;border: 0px;">
                <tr style="width:100%;border: 0px;">
                    <td style="width:33%;border: 0px;">
                        <p><b>Distancia:</b> ' . number_format($registro['distanciaOrigenDestinoMillas'], 2, '.', ',') . ' millas | ' . number_format($registro['distanciaOrigenDestinoKms'], 2, '.', ',') . ' kms</p>
                        <p><b>Tiempo/Recorrido:</b> ' . $registro['tiempoRecorridoOrigenDestino'] . '</p>
                        <p><b>Operador:</b> ' . $registro['servicio'] . '</p>
                        <br>
                    </td>
                    
                    <td style="width:33%;border: 0px;">
                        <p><b>Total CFT:</b> ' . number_format($registro['totalFt3'], 2, '.', ',') . '</p>
                        <p><b>Total m3:</b> ' . number_format($registro['totalM3'], 2, '.', ',') . '</p>
                        <br>
                        <br>
                    </td>
                    
                    <td style="width:33%;border: 0px;">
                        <p><b>Distancia:</b> ' . number_format($registro['distanciaDestinoFinalMillas'], 2, '.', ',') . ' millas | ' . number_format($registro['distanciaDestinoFinalKms'], 2, '.', ',') . ' kms</p>
                        <p><b>Tiempo/Recorrido:</b> ' . $registro['tiempoRecorridoDestinoFinal'] . '</p>
                        <p><b>Operador:</b> ' . $registro['operador'] . '</p>
                        <p><b>Unidad:</b> ' . $registro['unidad'] . '</p>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
    <h3>DESCRIPCIÓN DE LAS MERCANCIAS</h3>
  <table id="mercancias" class="table">
        <thead>
            <tr style="background-color:#e7e7e7;">
                <td>Cantidad</td>
                <td>Unidad medida</td>
                <td>Descripción</td>
                <td>Dimensiones</td>
                <td>Peso</td>
                <td>Valor factura</td>
            </tr>
        </thead>
        <tbody>
            ' . $mercancias_html . '
            <tr class="bg-secondary">
                <td></td>
                <td>1 ' . $registro['moneda'] . ' = ' . $registro['valorMoneda'] . '</td>
                <td></td>
                <td colspan="2">
                    <p><b>Peso total de la mercancía</b></p>
                    <p>' . number_format($registro['pesoMercanciaLbs'], 2, '.', ',') . ' Lbs</p>
                    <p>' . number_format($registro['pesoMercanciaKgs'], 2, '.', ',') . ' Kgs</p>
                </td>
                <td></td>
            </tr>
            <tr class="bg-secondary">
                <td colspan="5" style="text-align:right;"><b>VALOR TOTAL COMERCIAL USD</b></td>
                <td><b>$' . number_format($registro['valorMercanciaUSD'], 2, '.', ',') . '</b></td>
            </tr>
            <tr class="bg-secondary">
                <td colspan="5" style="text-align:right;"><b>VALOR TOTAL COMERCIAL MXN</b></td>
                <td><b>$' . number_format($registro['valorMercanciaMXN'], 2, '.', ',') . '</b></td>
            </tr>
        </tbody>
    </table>';

            $html .= '
    <h3>GASTOS POR TRASLADO DE MERCANCIAS PUERTO A PUERTO</h3>
    <table class="table">
        <tbody>
            <tr>
                <td style="border: 0px !important;">
                <p><b>Lugar origen:</b> ' . $registro['lugarOrigen'] . '</p>';
            if ($origen_html != '') {
                $html .= '
  <table id="servicios" class="table">
        <thead>
            <tr style="background-color:#e7e7e7;">
                <td>GASTOS EN ORIGEN</td>
                <td>EUROS</td>
                <td>EQUIVALENCIA DLLS</td>
                <td>TOTAL USD</td>
            </tr>
        </thead>
        <tbody>
            ' . $origen_html . '
            <tr><td colspan="4" style="color:#ffffff;">-</td></tr>
            <tr><td colspan="4" style="color:#ffffff;">-</td></tr>
            <tr class="bg-secondary">
                <td colspan="3" style="text-align:right;">Total</td>
                <td>$' . number_format($registro['totalOrigenAll'], 2, '.', ',') . '</td>
            </tr>
        </tbody>
    </table>';
            }
            $html .= '</td>
                <td style="border: 0px !important;">
                <p><b>Lugar destino:</b> ' . $registro['lugarDestino'] . '</p>';
            if ($destino_html != '') {
                $html .= '
      <table id="servicios" class="table">
            <thead>
                <tr style="background-color:#e7e7e7;">
                    <td>GASTOS EN DESTINO</td>
                    <td>USD</td>
                    <td>MX</td>
                </tr>
            </thead>
            <tbody>
                ' . $destino_html . '
                <tr class="bg-secondary">
                    <td style="text-align:right;">Subtotal</td>
                    <td>$' . number_format($registro['subtotalDestinoUsd'], 2, '.', ',') . '</td>
                    <td>$' . number_format($registro['subtotalDestinoMx'], 2, '.', ',') . '</td>
                </tr>
                <tr class="bg-secondary">
                    <td style="text-align:right;">Impuestos</td>
                    <td>$' . number_format($registro['impuestosDestinoUsd'], 2, '.', ',') . '</td>
                    <td>$' . number_format($registro['impuestosDestinoMx'], 2, '.', ',') . '</td>
                </tr>
                <tr class="bg-secondary">
                    <td style="text-align:right;">Total</td>
                    <td>$' . number_format($registro['totalDestinoUsd'], 2, '.', ',') . '</td>
                    <td>$' . number_format($registro['totalDestinoMx'], 2, '.', ',') . '</td>
                </tr>
            </tbody>
        </table>';
            }
            $html .= '</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 0px !important;text-align:center;color:#ffffff;">-</td>
             </tr>
             <tr class="bg-warning">
                <td style="text-align:right;"><b>VALOR TOTAL FLETE INT (USD)</b></td>
                 <td><b>$' . number_format($registro['valorTotalFlete'], 2, '.', ',') . '</b></td>
            </tr>
        </tbody>
    </table>';





            if ($incrementables_html != '') {
                $html .= '
        <h3>INCREMENTABLES</h3>
        <table id="incrementables" class="table">
            <thead>
            <tr style="background-color:#e7e7e7;">
                <td>Incrementable</td>
                <td>USD</td>
                <td>MX</td>
            </tr>
        </thead>
            <tbody>
                ' . $incrementables_html . '
            <tr class="bg-secondary">
                <td style="text-align:right;"><b>Total</b></td>
                <td><b>$' . number_format($registro['totalIncrementableUsd'], 2, '.', ',') . ' USD</b></td>
                <td><b>$' . number_format($registro['totalIncrementableMx'], 2, '.', ',') . ' MXN</b></td>
            </tr>
        </tbody>
    </table>';
            }

            $html .= '
    <h3>GASTOS POR FLETE TERRESTRE</h3>
  <table id="gastos" class="table">
        <thead>
            <tr style="background-color:#e7e7e7;">
                <td>Concepto</td>
                <td>Monto</td>
            </tr>
        </thead>
        <tbody>
            ' . $gasto_html . '
            <tr class="bg-secondary">
                <td style="text-align:right;">Subtotal</td>
                <td>$' . number_format($registro['subtotalFlete'], 2, '.', ',') . ' USD</td>
            </tr>
            <tr class="bg-secondary">
                <td style="text-align:right;">IVA</td>
                <td>$' . number_format($registro['impuestosFlete'], 2, '.', ',') . ' USD</td>
            </tr>
            <tr class="bg-secondary">
                <td style="text-align:right;">Retención</td>
                <td>$' . number_format($registro['retencionFlete'], 2, '.', ',') . ' USD</td>
            </tr><tr class="bg-warning">
                <td style="text-align:right;"><b>TOTAL USD</b></td>
                <td><b>$' . number_format($registro['totalCotizacionNumero'], 2, '.', ',') . ' USD</b></td>
            </tr>
            <tr class="bg-warning" style="text-align:center;">
                <td colspan="2"><b>' . $registro['totalCotizacionTexto'] . '</b></td>
            </tr>
        </tbody>
    </table>
    <h2>OBSERVACIONES</h2>
    <pre>' . $registro['observaciones'] . '</pre>
</body>
</html>';
        }
    }
    // Cargar el contenido HTML en DomPDF
    $dompdf->loadHtml($html);

    // Configurar el tamaño y orientación de la página
    $dompdf->setPaper('letter', 'portrait');

    // Renderizar el PDF
    $dompdf->render();

    // Salida del PDF (descarga)
    $cliente_nombre_sin_espacios = str_replace(' ', '_', $registro['cliente_nombre']);
    $dompdf->stream('cotizacion_Marítimo_LCL_' . $cliente_nombre_sin_espacios . '_' . $registro['identificador'] . '.pdf', ['Attachment' => true]);
}
