<?php

session_start();
require 'dbcon.php';
require 'vendor/autoload.php';
require 'dompdf/src/Dompdf.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($con, $_GET['id']);
    // Configurar opciones de DomPDF
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);


    $query = "SELECT a.*,
    a.id,
    a.fecha,
    a.distanciaOrigenDestinoMillas,
    a.distanciaOrigenDestinoKms,
    a.tiempoRecorridoOrigenDestino,
    a.servicio,
    c.cliente AS cliente_nombre,
    c.calle AS cliente_calle,
    c.colonia AS cliente_colonia,
    c.municipio AS cliente_municipio,
    c.telefono AS cliente_telefono,
    c.contacto AS cliente_contacto,
    c.rfc AS cliente_rfc,
    p_origen.proveedor AS origen_nombre,
    p_origen.domicilio AS origen_calle,
    p_origen.fraccionamiento AS origen_colonia,
    p_origen.ciudad AS origen_municipio,
    p_origen.phone AS origen_telefono,
    p_origen.contact AS origen_contacto,
    p_destino.proveedor AS destino_nombre,
    p_destino.domicilio AS destino_calle,
    p_destino.fraccionamiento AS destino_colonia,
    p_destino.ciudad AS destino_municipio,
    p_destino.phone AS destino_telefono,
    p_destino.contact AS destino_contacto,
    p_final.proveedor AS destino_final_nombre,
    p_final.domicilio AS destino_final_calle,
    p_final.fraccionamiento AS destino_final_colonia,
    p_final.ciudad AS destino_final_municipio,
    p_final.phone AS destino_final_telefono,
    p_final.contact AS destino_final_contacto
FROM 
    aereoimportacion a
JOIN 
    clientes c ON a.idCliente = c.id
JOIN 
    proveedores p_origen ON a.idOrigen = p_origen.id
JOIN 
    proveedores p_destino ON a.idDestino = p_destino.id
JOIN 
    proveedores p_final ON a.idDestinoFinal = p_final.id
WHERE a.id = $id
ORDER BY 
    a.id DESC 
LIMIT 1;
";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        foreach ($query_run as $registro) {

            $html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización LYSCE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
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
        .table td,
        .table th {
            border: 1px solid #666666;
            padding: 10px;
        }
        .bg-warning {
            background-color: #ffc107;
            padding: 10px;
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
            <td class="text-center" style="width: 30%;border: 0px;">
                <img class="logo" src="https://lysce.com.mx/assets/img/lysce/lysce_logo.png">
                <p>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</p>
            </td>
            <td style="width: 40%;border: 0px;">
                <h2><b>GRUPO LYSCE S.C.</b></h2>
                <p style="margin: 0;">R.F.C GLY170421ES6</p>
                <p style="margin: 0;">Sierra del Laurel 312 piso 2, Bosques del Prado Norte, Aguascalientes, Ags. C.P. 20127</p>
                <p style="margin: 0;">Tel / Fax +52 (449) 300 3265</p>
            </td>
            <td style="width: 30%;border: 0px;">
                <p style="margin-bottom:10px;"><b>COTIZACIÓN</b></p>
                <p>LYSCE-' . $registro['id'] . '</p>
                <p>Aguascalientes, Ags a ' . $registro['fecha'] . '</p>
            </td>
        </tr>
    </table>
    <p class="text-center bg-warning" style="border: 1px solid #666666;"><b>COTIZACION DE FLETE TRAILER COMPLETO / FTL USA / DRY VAN 53 FT</b></p>
    <table class="table">
        <tr>
            <td colspan="3"><b>Cliente</b>
                <p>' . $registro['cliente_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['cliente_calle'] . ', ' . $registro['cliente_colonia'] . ', ' . $registro['cliente_municipio'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['cliente_telefono'] . '</p>
                <p><b>Contacto:</b> ' . $registro['cliente_contacto'] . '</p>
                <p><b>RFC:</b> ' . $registro['cliente_rfc'] . '</p>
            </td>
        </tr>
        <tr>
            <td>
                <b>Origen:</b>
                <p>' . $registro['origen_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['origen_calle'] . ', ' . $registro['origen_colonia'] . ', ' . $registro['origen_municipio'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['origen_telefono'] . '</p>
                <p><b>Contacto:</b> ' . $registro['origen_contacto'] . '</p>
            </td>
            <td>
                <b>Destino en frontera:</b>
                <p>' . $registro['destino_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['destino_calle'] . ', ' . $registro['destino_colonia'] . ', ' . $registro['destino_municipio'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['destino_telefono'] . '</p>
                <p><b>Contacto:</b> ' . $registro['destino_contacto'] . '</p>
            </td>
            <td>
                <b>Destino Final:</b>
                <p>' . $registro['destino_final_nombre'] . '</p>
                <p><b>Domicilio:</b> ' . $registro['destino_final_calle'] . ', ' . $registro['destino_final_colonia'] . ', ' . $registro['destino_final_municipio'] . '</p>
                <p><b>Teléfono:</b> ' . $registro['destino_final_telefono'] . '</p>
                <p><b>Contacto:</b> ' . $registro['destino_final_contacto'] . '</p>
            </td>
        </tr>
        <tr style="border: 0px;">
            <td colspan="3" style="border: 0px;">
            <table style="width:100%;border: 0px;">
                <tr style="width:100%;border: 0px;">
                    <td style="width:33%;border: 0px;">
                        <p><b>Distancia:</b> ' . $registro['distanciaOrigenDestinoMillas'] . ' millas | ' . $registro['distanciaOrigenDestinoKms'] . ' kms</p>
                        <p><b>Tiempo/Recorrido:</b> ' . $registro['tiempoRecorridoOrigenDestino'] . ' hrs</p>
                        <p><b>Operador:</b> ' . $registro['servicio'] . '</p>
                    </td>
                    
                    <td style="width:33%;border: 0px;">
                        <p><b>Total CFT:</b> ' . $registro['totalFt3'] . '</p>
                        <p><b>Total m3:</b> ' . $registro['totalM3'] . '</p>
                    </td>
                    
                    <td style="width:33%;border: 0px;">
                        <p><b>Distancia:</b> ' . $registro['distanciaDestinoFinalMillas'] . ' millas | ' . $registro['distanciaDestinoFinalKms'] . ' kms</p>
                        <p><b>Tiempo/Recorrido:</b> ' . $registro['tiempoRecorridoDestinoFinal'] . ' hrs</p>
                        <p><b>Operador:</b> ' . $registro['operador'] . '</p>
                        <p><b>Unidad:</b> ' . $registro['unidad'] . '</p>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
    <h2>DETERMINACION DE INCREMENTABLES</h2>
    <table class="table">
        <tr>
            <td>Incrementable</td>
            <td>USD</td>
            <td>MX</td>
        </tr>
    </table>
</body>
</html>';
        }
    }
    // Cargar el contenido HTML en DomPDF
    $dompdf->loadHtml($html);

    // Configurar el tamaño y orientación de la página
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar el PDF
    $dompdf->render();

    // Salida del PDF (descarga)
    $dompdf->stream('cotizacion.pdf', ['Attachment' => true]);
}
