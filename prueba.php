<?php
// Configuración inicial
session_start();
require 'dbcon.php';
require_once('TCPDF/tcpdf.php');

error_reporting(E_ERROR | E_PARSE); // Ignorar warnings como los de "iCCP"

// Crear instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre o Empresa');
$pdf->SetTitle('Título del PDF');
$pdf->SetSubject('Asunto del PDF');
$pdf->SetKeywords('TCPDF, PDF, ejemplo');

// Eliminar las cabeceras y pies de página automáticos
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Establecer fuente predeterminada
$pdf->SetFont('helvetica', '', 12);

// Agregar página
$pdf->AddPage();

// Generar contenido HTML dinámico
$solicitante = mysqli_real_escape_string($con, $_POST['solicitante']);
$html = '

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="images/logo.png" alt="Logo" class="img-fluid">
            <p>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</p>
        </div>
        <div class="col-md-6">
            <h2><b>GRUPO LYSCE S.C.</b></h2>
            <p>R.F.C GLY170421ES6</p>
            <p>Sierra del Laurel 312 piso 2, Bosques del Prado Norte, Aguascalientes, Ags. C.P. 20127</p>
            <p>Tel / Fax +52 (449) 300 3265</p>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
                ';

// Convertir HTML a PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF
$pdf->Output('documento.pdf', 'I');
?>
