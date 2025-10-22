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
    a.folio,
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
    cartainstruccionesftl a
LEFT JOIN 
    clientes c ON a.idCliente = c.id
LEFT JOIN 
    clientes p_origen ON a.idOrigen = p_origen.id
LEFT JOIN 
    clientes p_destino ON a.idDestino = p_destino.id
LEFT JOIN
    clientes p_final ON a.idDestinoFinal = p_final.id
WHERE 
    a.idFtl = $id;
";

    $query_mercancias = "SELECT d.*, r.factura, r.pedimento
    FROM descripcionmercanciasftl d
    LEFT JOIN referenciaftl r ON r.idDesc = d.id
    WHERE d.idFtl = $id";

    $resultado_mercancias = mysqli_query($con, $query_mercancias);

    $mercancias_html = '';
    if (mysqli_num_rows($resultado_mercancias) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_mercancias)) {
            $mercancias_html .= '
            <tr>
                <td>
                <table class="noBorder">
                    <td style="min-width:30px">
                        <p>' . htmlspecialchars($row['cantidad']) . '</p>
                    </td>
                    <td style="min-width:80px">
                        <p>' . htmlspecialchars($row['unidadMedida']) . '</p>
                    </td>
                    <td style="min-width:80px">' . htmlspecialchars($row['descripcion']) . '</td>
                    <td style="min-width:200px">
                        <p>' . htmlspecialchars($row['largoCm']) . ' x ' . htmlspecialchars($row['anchoCm']) . ' x ' . htmlspecialchars($row['altoCm']) . ' <b>inches</b> - ' . number_format($row['piesCubicos'], 2, '.', ',') . ' <b>ft3</b></p>
                        <p>' . htmlspecialchars($row['largoPlg']) . ' x ' . htmlspecialchars($row['anchoPlg']) . ' x ' . htmlspecialchars($row['altoPlg']) . ' <b>cm</b> - ' . number_format($row['metrosCubicos'], 2, '.', ',') . ' <b>m3</b></p>
                    </td>
                    <td>
                        <p>' . number_format($row['libras'], 2, '.', ',') . ' Lbs</p>
                        <p>' . number_format($row['kilogramos'], 2, '.', ',') . ' Kgs</p>
                    </td>
            </table>
        </td>
        <td>
            <p><b>Factura: </b>' . htmlspecialchars($row['factura']) . '</p>
            <p><b>Pedimento: </b>' . htmlspecialchars($row['pedimento']) . '</p>
        </td>
            </tr>
        ';
        }
    }

    $query_servicios = "SELECT * FROM ccpftl WHERE idFtl = $id";
    $resultado_servicios = mysqli_query($con, $query_servicios);

    $servicios_html = '';
    if (mysqli_num_rows($resultado_servicios) > 0) {
        while ($row = mysqli_fetch_assoc($resultado_servicios)) {
            $servicios_html .= '
        <tr style="background-color: #c8c8c8ff;text-align:center;">
            <td><p><b>CLAVE SAT DEL PRODUCTO</b></p></td>
            <td><p><b>DESCRIPCIÓN CATALOGO SAT</b></p></td>
            <td><p><b>CANTIDAD</b></p></td>
            <td><p><b>CLAVE DE UNIDAD</b></p></td>
            <td><p><b>KILOGRAMOS</b></p></td>
            <td><p><b>FRACCION ARANCELARIA</b></p></td>
            <td><p><b>TIPO DE MATERIAL</b></p></td>
        </tr>
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['clave']) . '</td>
            <td>' . htmlspecialchars($row['descripcion']) . '</td>
            <td>' . htmlspecialchars($row['cantidad']) . '</td>
            <td>' . htmlspecialchars($row['unidad']) . '</td>
            <td>' . htmlspecialchars($row['kilogramos']) . '</td>
            <td>' . htmlspecialchars($row['fraccion']) . '</td>
            <td>' . htmlspecialchars($row['tipo']) . '</td>
        </tr>
        <tr style="background-color: #c8c8c8ff;text-align:center;">
            <td><p><b>PEDIMENTO</b></p></td>
            <td><p><b>CLAVE MATERIAL PELIGROSO</b></p></td>
            <td><p><b>CLAVE TIPO DE EMBALAJE</b></p></td>
            <td><p><b>DOCUMENTO ADUANERO</b></p></td>
            <td><p><b>REGIMEN ADUANERO</b></p></td>
            <td colspan="2"><p><b>RFC DE IMPORTADOR</b></p></td>
        </tr>
        <tr style="text-align:center;">
            <td>' . htmlspecialchars($row['pedimento']) . '</td>
            <td>' . htmlspecialchars($row['material']) . '</td>
            <td>' . htmlspecialchars($row['embalaje']) . '</td>
            <td>' . htmlspecialchars($row['aduanero']) . '</td>
            <td>' . htmlspecialchars($row['regimen']) . '</td>
            <td colspan="2">' . htmlspecialchars($row['importador']) . '</td>
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
    <title>Carta de instrucciones FTL | LYSCE</title>
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
        .noBorder td {
            border: 0px solid #ffffffff;
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
                <h2 style="margin-bottom:10px;"><b>CARTA DE INSTRUCCIONES</b></h2>
                <p class="bg-warning" style="border: 1px solid #666666;margin: 5px 0px;padding:5px;"><b>' . $registro['tipoFtl'] . '</b></p>
                <p>Folio: <span style="color:rgb(159, 41, 41);text-transform:uppercase;">' . $registro['folio'] . '</span></p>
                <p>Aguascalientes, Ags a ' . $registro['fecha'] . '</p>
                <br>
                <br>
            </td>
        </tr>
    </table>
    <br>
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
    </table>
    <table style="width:100%;">
        <tr>
            <td>
                <p><b>Transportista: </b>' . $registro['transportista'] . '</p>
                <p><b>Unidad: </b>' . $registro['unidad'] . '</p>
                <p><b>No: </b>' . $registro['numero'] . '</p>
                <p><b>Placas: </b>' . $registro['placas'] . '</p>
            </td>
            <td>
                <p><b>Transfer: </b>' . $registro['transfer'] . '</p>
                <p><b>CAAT: </b>' . $registro['caat'] . '</p>
                <p><b>SCAC: </b>' . $registro['scac'] . '</p>
            </td>
        </tr>
    </table>

    <h3>DESCRIPCIÓN DE LAS MERCANCIAS</h3>
  <table id="mercancias" class="table">
        <thead>
            <tr style="background-color:#e7e7e7;">
                <td class="text-center">Contenido</td>
                <td class="text-center">Referencia</td>
            </tr>
        </thead>
        <tbody>
            ' . $mercancias_html . '
        </tbody>
    </table>';

            if ($servicios_html != '') {
                $html .= '
    <h3>COMPLEMETO DE CARTA PORTE (CCP)</h3>
  <table id="servicios" class="table">
        <tbody>
            ' . $servicios_html . '
        </tbody>
    </table>';
            }

            $html .= '
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
    $dompdf->stream('carta_de_instrucciones_FTL_' . $cliente_nombre_sin_espacios . '_' . $registro['folio'] . '.pdf', ['Attachment' => true]);
}
