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
    p_origen.proveedor AS origen_nombre,
    p_origen.domicilio AS origen_domicilio,
    p_origen.exterior AS origen_exterior,
    p_origen.interior AS origen_interior,
    p_origen.estado AS origen_estado,
    p_origen.fraccionamiento AS origen_fraccionamiento,
    p_origen.ciudad AS origen_ciudad,
    p_origen.postal AS origen_postal,
    p_origen.country AS origen_country,
    p_origen.phone AS origen_phone,
    p_origen.contact AS origen_contact,
    p_origen.email AS origen_email,
    p_origen.tax AS origen_tax,
    p_destino.proveedor AS destino_nombre,
    p_destino.domicilio AS destino_domicilio,
    p_destino.exterior AS destino_exterior,
    p_destino.interior AS destino_interior,
    p_destino.estado AS destino_estado,
    p_destino.fraccionamiento AS destino_fraccionamiento,
    p_destino.ciudad AS destino_ciudad,
    p_destino.postal AS destino_postal,
    p_destino.country AS destino_country,
    p_destino.phone AS destino_phone,
    p_destino.contact AS destino_contact,
    p_destino.email AS destino_email,
    p_destino.tax AS destino_tax,
    p_final.proveedor AS final_nombre,
    p_final.domicilio AS final_domicilio,
    p_final.exterior AS final_exterior,
    p_final.interior AS final_interior,
    p_final.estado AS final_estado,
    p_final.fraccionamiento AS final_fraccionamiento,
    p_final.ciudad AS final_ciudad,
    p_final.postal AS final_postal,
    p_final.country AS final_country,
    p_final.phone AS final_phone,
    p_final.contact AS final_contact,
    p_final.email AS final_email,
    p_final.tax AS final_tax
FROM 
    ftl a
LEFT JOIN 
    clientes c ON a.idCliente = c.id
LEFT JOIN 
    proveedores p_origen ON a.idOrigen = p_origen.id
LEFT JOIN 
    proveedores p_destino ON a.idDestino = p_destino.id
LEFT JOIN
    proveedores p_final ON a.idDestinoFinal = p_final.id
WHERE 
    a.id = $id;
";

    $query_mercancias = "SELECT * FROM descripcionmercanciasftl WHERE idFtl = $id";
    $resultado_mercancias = mysqli_query($con, $query_mercancias);

    $mercancias_html = '';
    if (mysqli_num_rows($resultado_mercancias) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_mercancias)) {
            $mercancias_html .= '
        <tr>
            <td>
                <p>' . htmlspecialchars($row['cantidad']) . '</p>
                <p>NMFC</p>
            </td>
            <td>
                <p>' . htmlspecialchars($row['unidadMedida']) . '</p>
                <p>' . htmlspecialchars($row['nmfc']) . '</p>
            </td>
            <td>' . htmlspecialchars($row['descripcion']) . '</td>
            <td>
                <p>' . htmlspecialchars($row['largoCm']) . ' x ' . htmlspecialchars($row['anchoCm']) . ' x ' . htmlspecialchars($row['altoCm']) . ' inches ' . htmlspecialchars($row['piesCubicos']) . ' ft3</p>
                <p>' . htmlspecialchars($row['largoPlg']) . ' x ' . htmlspecialchars($row['anchoPlg']) . ' x ' . htmlspecialchars($row['altoPlg']) . ' cm ' . htmlspecialchars($row['metrosCubicos']) . ' m3</p>
            </td>
            <td>
                <p>' . htmlspecialchars($row['libras']) . ' Lbs</p>
                <p>' . htmlspecialchars($row['kilogramos']) . ' Kgs</p>
            </td>
            <td>$' . htmlspecialchars($row['valorFactura']) . '</td>
        </tr>';
        }
    }

    $query_servicios = "SELECT conceptoServicio, tiempoServicio FROM servicioftl WHERE idFtl = $id";
    $resultado_servicios = mysqli_query($con, $query_servicios);

    $servicios_html = '';
    if (mysqli_num_rows($resultado_servicios) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_servicios)) {
            $servicios_html .= '
        <tr>
            <td>' . htmlspecialchars($row['conceptoServicio']) . '</td>
            <td>' . htmlspecialchars($row['tiempoServicio']) . '</td>
        </tr>';
        }
    }

    $query_incrementables = "SELECT incrementable, incrementableUSD, incrementableMx FROM incrementablesftl WHERE idFtl = $id";
    $resultado_incrementables = mysqli_query($con, $query_incrementables);

    $incrementables_html = '';
    if (mysqli_num_rows($resultado_incrementables) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_incrementables)) {
            $incrementables_html .= '
        <tr>
            <td>' . htmlspecialchars($row['incrementable']) . '</td>
            <td>$' . htmlspecialchars($row['incrementableUSD']) . ' USD</td>
            <td>$' . htmlspecialchars($row['incrementableMx']) . ' MXN</td>
        </tr>';
        }
    }

    $query_gasto = "SELECT * FROM gastosftl WHERE idFtl = $id";
    $resultado_gasto = mysqli_query($con, $query_gasto);

    $gasto_html = '';
    if (mysqli_num_rows($resultado_gasto) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_gasto)) {
            $gasto_html .= '
        <tr>
            <td>' . htmlspecialchars($row['conceptoGasto']) . '</td>
            <td>$' . htmlspecialchars($row['montoGasto']) . '</td>
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
    <title>Cotización FTL | LYSCE</title>
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
                <p>LYSCE-' . str_pad($registro['id'], 5, '0', STR_PAD_LEFT) . '</p>
                <p>Aguascalientes, Ags a ' . $registro['fecha'] . '</p>
                <br>
                <br>
            </td>
        </tr>
    </table>
    <br>
    <p class="text-center bg-warning" style="border: 1px solid #666666;margin: 5px 0px;padding:5px;"><b>' . $registro['tipoFtl'] . '</b></p>
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
                        <p><b>Distancia:</b> ' . $registro['distanciaOrigenDestinoMillas'] . ' millas | ' . $registro['distanciaOrigenDestinoKms'] . ' kms</p>
                        <p><b>Tiempo/Recorrido:</b> ' . $registro['tiempoRecorridoOrigenDestino'] . '</p>
                        <p><b>Operador:</b> ' . $registro['servicio'] . '</p>
                        <br>
                    </td>
                    
                    <td style="width:33%;border: 0px;">
                        <p><b>Total CFT:</b> ' . $registro['totalFt3'] . '</p>
                        <p><b>Total m3:</b> ' . $registro['totalM3'] . '</p>
                        <br>
                        <br>
                    </td>
                    
                    <td style="width:33%;border: 0px;">
                        <p><b>Distancia:</b> ' . $registro['distanciaDestinoFinalMillas'] . ' millas | ' . $registro['distanciaDestinoFinalKms'] . ' kms</p>
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
                <td>' . $registro['totalBultos'] . ' Total de bultos</td>
                <td></td>
                <td>1 ' . $registro['moneda'] . ' = ' . $registro['valorMoneda'] . '</td>
                <td></td>
                <td>
                    <p>Peso total de la mercacía</p>
                    <p>' . $registro['pesoMercanciaLbs'] . ' Lbs</p>
                    <p>' . $registro['pesoMercanciaKgs'] . ' Kgs</p>
                </td>
                <td></td>
            </tr>
            <tr class="bg-secondary">
                <td colspan="5" style="text-align:right;"><b>VALOR TOTAL COMERCIAL USD</b></td>
                <td><b>$' . $registro['valorMercancia'] . '</b></td>
            </tr>
            <tr class="bg-secondary">
                <td colspan="5" style="text-align:right;"><b>VALOR TOTAL COMERCIAL MXN</b></td>
                <td><b>$' . $registro['valorComercial'] . '</b></td>
            </tr>
        </tbody>
    </table>
    <h3>TIPO DE SERVICIO</h3>
  <table id="servicios" class="table">
        <thead>
            <tr style="background-color:#e7e7e7;">
                <td>Servicio</td>
                <td>Tiempo de servicio</td>
            </tr>
        </thead>
        <tbody>
            ' . $servicios_html . '
        </tbody>
    </table>
    <h3>DETERMINACION DE INCREMENTABLES</h3>
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
                <td><b>$' . $registro['totalIncrementableUsd'] . ' USD</b></td>
                <td><b>$' . $registro['totalIncrementableMx'] . ' MXN</b></td>
            </tr>
        </tbody>
    </table>
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
                <td>$' . $registro['subtotalFlete'] . ' USD</td>
            </tr>
            <tr class="bg-secondary">
                <td style="text-align:right;">IVA</td>
                <td>$' . $registro['impuestosFlete'] . ' USD</td>
            </tr>
            <tr class="bg-secondary">
                <td style="text-align:right;">Retención</td>
                <td>$' . $registro['retencionFlete'] . ' USD</td>
            </tr><tr class="bg-warning">
                <td style="text-align:right;"><b>TOTAL USD</b></td>
                <td><b>$' . $registro['totalCotizacionNumero'] . ' USD</b></td>
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
    $cliente_nombre_sin_espacios = str_replace(' ', '', $registro['cliente_nombre']);
    $dompdf->stream('cotizacionFTL' . $cliente_nombre_sin_espacios . '_' . str_pad($registro['id'], 5, '0', STR_PAD_LEFT) . '.pdf', ['Attachment' => true]);
}
