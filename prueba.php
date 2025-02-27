<?php
session_start();
require 'dbcon.php';

$query = "SELECT * FROM aereoimportacion ORDER BY id DESC LIMIT 1";
$query_run = mysqli_query($con, $query);
if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $registro) {
?>


        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico">
            <title>Importacion LTL USA | LYSCE</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
            <link rel="stylesheet" href="css/styles.css">
        </head>

        <body>
            <div class="container-fluid p-5">
                <div class="row justify-content-evenly">
                    <div class="col-3 mb-3 text-center">
                        <img style="width: 70%;" src="images/logo.png" alt="">
                        <p>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</p>
                    </div>
                    <div class="col-4 mb-3">
                        <h2><b>GRUPO LYSCE S.C.</b></h2>
                        <p style="margin: 0px;">R.F.C GLY170421ES6</p>
                        <p style="margin: 0px;">Sierra del Laurel 312 piso 2, Bosques del Prado Norte, Aguascalientes, Ags. C.P. 20127</p>
                        <p style="margin: 0px;">Tel / Fax +52 (449) 300 3265</p>
                    </div>
                    <div class="col-3 mb-3">
                        <p style="margin: 5px;"><b>COTIZACIÓN</b></p>
                        <p>LYSCE-<?= $registro['id']; ?></p>
                        <p style="margin: 5px;">Aguascalientes, Ags a</p>
                    </div>
                    <div class="col-12 text-center bg-warning p-1" style="border: 1px solid #666666;border-bottom:0px;">
                        <p><b>COTIZACION DE FLETE TRAILER COMPLETO / FTL USA / DRY VAN 53 FT</b></p>
                    </div>
                    <div class="col-12 p-3" style="border: 1px solid #666666; border-bottom:0px;">
                        <p class="mb-1"><b>Cliente</b></p>
                        <p id="detalleCliente"></p>
                    </div>
                    <div class="col-4 p-3" style="border: 1px solid #666666;">
                        <p class="mb-1"><b>Origen</b></p>
                        <p id="detalleOrigen"></p>
                    </div>
                    <div class="col-4 p-3" style="border: 1px solid #666666;">
                        <p class="mb-1"><b>Destino en frontera</b></p>
                        <p id="detalleAduana"></p>
                    </div>
                    <div class="col-4 p-3" style="border: 1px solid #666666;">
                        <p class="mb-1"><b>Destino Final</b></p>
                        <p id="detalleDestino"></p>
                    </div>

                    <div class="col-8 mt-3 mb-3">
                        <div class="row justify-content-start">
                            <div class="col-6">
                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Distancia:</b>
                                <p><?= $registro['distanciaOrigenDestinoMillas']; ?></p>
                                millas | Kms
                                </p><br>

                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Tiempo / Recorrido:</b>
                                </p><br>

                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Operador:</b>
                                </p>
                            </div>
                            <div class="col-6">
                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Total CFT:</b>
                                </p><br>

                                <p style="display: inline-block;margin-bottom: 5px;">
                                    <b>Total m3:</b>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 mt-3 mb-3">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Distancia:</b>
                            millas | Kms
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Tiempo / Recorrido:</b>
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Operador:</b>
                        </p>

                        <p style="display: inline-block;">
                            <b>Unidad:</b>
                        </p>

                    </div>


                    <form action="generate_pdf.php" method="POST">
                        <div class="modal-footer">
                            <a href="dashboard.php" class="btn btn-secondary">Cerrar</a>
                            <button type="submit" class="btn btn-primary" name="save">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
    <?php
    }
} else {
    echo "<td colspan='8'><p> No se encontro ningun registro </p></td>";
}
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>

        </body>

        </html>