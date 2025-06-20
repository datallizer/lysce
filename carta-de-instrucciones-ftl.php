<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
    } else {
        $_SESSION['alert'] = [
            'title' => 'USUARIO NO ENCONTRADO',
            'icon' => 'ERROR'
        ];
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['alert'] = [
        'message' => 'Para acceder debes iniciar sesión primero',
        'title' => 'SESIÓN NO INICIADA',
        'icon' => 'info'
    ];
    header('Location: login.php');
    exit();
}

$ftlId = null;
if (isset($_GET['id'])) {
    $stmt = $con->prepare("SELECT id FROM ftl WHERE identificador = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $ftlId = $row['id'];
    }

    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carta de instrucciones FTL | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/ics.ico" />
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="sb-nav-fixed">
    <?php include 'sidenav.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container mt-3">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="ftl.php" class="btn btn-danger btn-sm float-end">Regresar</a>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($_GET['id'])) {
                                    $registro_id = mysqli_real_escape_string($con, $_GET['id']);
                                    $query = "SELECT * FROM cartainstruccionesftl WHERE folio='$registro_id' ";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $registro = mysqli_fetch_array($query_run);
                                        $titulo = $registro['tipoFtl'];
                                ?>
                                        <form action="codeftl.php" method="POST" class="row justify-content-evenly">
                                            <input class="form-control" value="<?= $registro['id']; ?>" type="hidden" name="id">
                                            <div class="col-4 mb-3 text-start">
                                                <img style="width: 70%;" src="images/logo.png" alt="">
                                                <p><b>LOGÍSTICA Y SERVICIOS DE COMERCIO EXTERIOR</b></p>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <h2><b>CARTA DE INSTRUCCIONES</b></h2>
                                                <input class="form-control bg-warning" name="tipoFtl" value="<?= $registro['tipoFtl']; ?>" readonly>
                                                <p>No.</p>
                                                <input class="form-control" type="text" name="identificador" value="<?= $registro['folio']; ?>" readonly>
                                                <p>Fecha</p>
                                                <input class="form-control mb-1" type="text" name="fecha" id="expedicion" value="">
                                            </div>
                                            <div class="col-12 p-3" style="border: 1px solid #666666; border-bottom:0px;">
                                                <p class="mb-1"><b>Cliente</b></p>
                                                <select class="form-select mb-3" id="cliente" disabled>
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1 AND tipo = 'Cliente'";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($cliente = mysqli_fetch_assoc($result)) {
                                                            $nombre = $cliente['cliente'];
                                                            $idCliente = $cliente['id'];
                                                            $selected = ($registro['idCliente'] == $idCliente) ? "selected" : ""; // Verifica si es el seleccionado
                                                            echo "<option value='$idCliente' $selected>$nombre</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <p id="detalleCliente"></p>
                                            </div>

                                            <div class="col-4 p-3" style="border: 1px solid #666666;">
                                                <p class="mb-1"><b>Origen</b></p>
                                                <select class="form-select" id="origen" disabled>
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($origen = mysqli_fetch_assoc($result)) {
                                                            $nombre = $origen['cliente'];
                                                            $id = $origen['id'];
                                                            $tipo = $origen['tipo'];
                                                            $selected = ($registro['idOrigen'] == $id) ? "selected" : "";
                                                            echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <p id="detalleOrigen"></p>
                                            </div>

                                            <div class="col-4 p-3" style="border: 1px solid #666666;">
                                                <p class="mb-1"><b>Destino en frontera</b></p>
                                                <select class="form-select" disabled id="aduana">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($aduana = mysqli_fetch_assoc($result)) {
                                                            $nombre = $aduana['cliente'];
                                                            $id = $aduana['id'];
                                                            $tipo = $aduana['tipo'];
                                                            $selected = ($registro['idDestino'] == $id) ? "selected" : "";
                                                            echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <p id="detalleAduana"></p>
                                            </div>

                                            <div class="col-4 p-3" style="border: 1px solid #666666;">
                                                <p class="mb-1"><b>Destino Final</b></p>
                                                <select class="form-select" disabled id="destino">
                                                    <option value="" disabled selected>Selecciona una opción</option>
                                                    <?php
                                                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                                                    $result = mysqli_query($con, $query);

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($destino = mysqli_fetch_assoc($result)) {
                                                            $nombre = $destino['cliente'];
                                                            $id = $destino['id'];
                                                            $tipo = $destino['tipo'];
                                                            $selected = ($registro['idDestinoFinal'] == $id) ? "selected" : ""; // Verifica si es el seleccionado
                                                            echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <p id="detalleDestino"></p>
                                            </div>

                                            <div class="col-6 mt-3 mb-3 p-1">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <p style="display: inline-block;margin-bottom: 5px;">
                                                            <b>Transportista:</b>
                                                            <input name="servicio" class="form-control" style="width: 217px; display: inline-block;" type="text">
                                                        </p>
                                                    </div>
                                                    <div class="col-4">
                                                        <select class="form-select bg-info" name="idDestino" id="destino">
                                                            <option value="" disabled selected>Transportistas</option>
                                                            <?php
                                                            $query = "SELECT * FROM transportistas WHERE estatus = 1";
                                                            $result = mysqli_query($con, $query);

                                                            if (mysqli_num_rows($result) > 0) {
                                                                while ($destino = mysqli_fetch_assoc($result)) {
                                                                    $nombre = $destino['transportista'];
                                                                    $id = $destino['id'];
                                                                    $tipo = $destino['placas'];
                                                                    $selected = ($registro['idDestinoFinal'] == $id) ? "selected" : ""; // Verifica si es el seleccionado
                                                                    echo "<option value='$id' $selected>$nombre - $tipo</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Unidad:</b>
                                                    <input name="servicio" class="form-control" style="width: 262px; display: inline-block;" type="text">
                                                </p><br>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>No.</b>
                                                    <input name="servicio" class="form-control" style="width: 292px; display: inline-block;" type="text">
                                                </p><br>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Placas:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                            </div>

                                            <div class="col-6 mt-3 mb-3 p-1 text-end">
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Transfer:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p><br>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>CAAT:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p><br>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>SCAC:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                            </div>

                                            <div class="col-12 text-center p-2">
                                                <div class="card">
                                                    <div class="card-header bg-secondary">
                                                        <p style="color: #fff;"><b>DESCRIPCIÓN DE LAS MERCANCÍAS</b></p>
                                                    </div>
                                                    <table class="table table-striped" style="margin-bottom: 0px;">
                                                        <tr>
                                                            <th colspan="5">Contenido</th>
                                                            <th colspan="2">Referencia</th>
                                                        </tr>
                                                        <?php
                                                        $query_desc = "SELECT * FROM descripcionmercanciasftl WHERE idFtl= $ftlId";
                                                        $query_run_desc = mysqli_query($con, $query_desc);

                                                        if (mysqli_num_rows($query_run_desc) > 0) {
                                                            while ($mercancia = mysqli_fetch_assoc($query_run_desc)) {
                                                        ?>
                                                                <tr>
                                                                    <td>
                                                                        <p><?= $mercancia['cantidad']; ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <p><?= $mercancia['unidadMedida']; ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <p><?= $mercancia['descripcion']; ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <p><?= $mercancia['largoCm']; ?> x <?= $mercancia['anchoCm']; ?> x <?= $mercancia['altoCm']; ?> mts</p>
                                                                        <p><?= $mercancia['largoPlg']; ?> x <?= $mercancia['anchoPlg']; ?> x <?= $mercancia['altoPlg']; ?> plg</p>
                                                                    </td>
                                                                    <td>
                                                                        <p><?= $mercancia['libras']; ?> Lbs</p>
                                                                        <p><?= $mercancia['kilogramos']; ?> Kgs</p>
                                                                    </td>
                                                                    <td class="text-end">
                                                                        <p>Factura</p>
                                                                        <p class="mt-4">Pedimento</p>
                                                                    </td>
                                                                    <td>
                                                                        <div>
                                                                            <input class="form-control mb-1" type="text">
                                                                            <input class="form-control mt-1" type="text">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                                                        }
                                                        ?>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-5 mb-5">
                                                <div class="card">
                                                    <div class="card-header bg-secondary">
                                                        <p class="text-center" style="color: #fff;"><b>COMPLEMETO DE CARTA PORTE (CCP)</b></p>
                                                    </div>
                                                    <table class="table table-striped table-bordered" id="incrementableTable" style="margin-bottom: 0px;">
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <div class="col-12 text-center p-2">
                                                        <button class="btn btn-danger" id="removeRowButton" type="button">-</button>
                                                        <button class="btn btn-secondary" id="addRowButton" type="button">+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Fecha de carga:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Horario de carga:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Tiempo de recorrido:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Fecha de arribo:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                                <p style="display: inline-block;margin-bottom: 5px;">
                                                    <b>Horario de descarga:</b>
                                                    <input name="servicio" class="form-control" style="width: 267px; display: inline-block;" type="text">
                                                </p>
                                            </div>

                                            <div class="col-12 mt-5">
                                                <p><b>Observaciones:</b></p>
                                                <textarea class="form-control" style="min-height: 150px;" name="" id=""></textarea>
                                                <div class="text-center">
                                                    <p><b>ATENTAMENTE</b></p>
                                                    <p>EQUIPO LYSCE</p>
                                                </div>
                                            </div>

                                            <div class="col-6 mt-5">
                                                <h2><b>GRUPO LYSCE S.C.</b></h2>
                                                <p style="margin: 0px;">R.F.C GLY170421ES6</p>
                                                <p style="margin: 0px;">Sierra del Laurel 312 piso 2, Bosques del Prado Norte, Aguascalientes, Ags. C.P. 20127</p>
                                                <p style="margin: 0px;">Tel / Fax +52 (449) 300 3265</p>
                                            </div>

                                             <div class="col-6 mt-5">
                                                <h2 class="text-danger"><b>MOVIENDO SOLUCIONES</b></h2>
                                            </div>


                                            <div class="modal-footer mt-5">
                                                <a href="ftl.php" class="btn btn-secondary m-1">Cancelar</a>
                                                <button type="submit" class="btn btn-success m-1" name="save" disabled>Guardar</button>
                                            </div>
                                        </form>
                                <?php
                                    } else {
                                        echo '<h4>No Such Id Found</h4>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
        <script>
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            document.getElementById('expedicion').value = formattedDate;

            var idCliente = "<?php echo $registro['idCliente']; ?>";
            var idOrigen = "<?php echo $registro['idOrigen']; ?>";
            var idAduana = "<?php echo $registro['idDestino']; ?>";
            var idDestino = "<?php echo $registro['idDestinoFinal']; ?>";

            window.onload = function() {
                document.getElementById("cliente").value = idCliente;
                obtenerDetalleCliente(idCliente);
                document.getElementById("origen").value = idOrigen;
                obtenerDetalleOrigen(idOrigen);
                document.getElementById("aduana").value = idAduana;
                obtenerDetalleAduana(idAduana);
                document.getElementById("destino").value = idDestino;
                obtenerDetalleDestino(idDestino);

            };

            function obtenerDetalleCliente(idCliente) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleCliente").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idCliente=" + idCliente);
            }

            function obtenerDetalleOrigen(idOrigen) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleOrigen").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idOrigen=" + idOrigen);
            }

            function obtenerDetalleAduana(idAduana) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleAduana").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idAduana=" + idAduana);
            }

            function obtenerDetalleDestino(idDestino) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "obtener_cliente.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("detalleDestino").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("idDestino=" + idDestino);
            }


            // Select de cliente
            document.getElementById("cliente").addEventListener("change", function() {
                var idCliente = this.value;

                if (idCliente) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleCliente").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idCliente=" + idCliente);
                } else {
                    document.getElementById("detalleCliente").innerHTML = "";
                }
            });

            // Select de origen
            document.getElementById("origen").addEventListener("change", function() {
                var idOrigen = this.value;

                if (idOrigen) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleOrigen").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idOrigen=" + idOrigen);
                } else {
                    document.getElementById("detalleOrigen").innerHTML = "";
                }
            });

            // Select de Aduana destino
            document.getElementById("aduana").addEventListener("change", function() {
                var idAduana = this.value;

                if (idAduana) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleAduana").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idAduana=" + idAduana);
                } else {
                    document.getElementById("detalleAduana").innerHTML = "";
                }
            });

            // Select de Destino final
            document.getElementById("destino").addEventListener("change", function() {
                var idDestino = this.value;

                if (idDestino) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "obtener_cliente.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            document.getElementById("detalleDestino").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send("idDestino=" + idDestino);
                } else {
                    document.getElementById("detalleDestino").innerHTML = "";
                }
            });

            function agregarIncrementable() {
                var tbody = document.getElementById("incrementableTable").getElementsByTagName("tbody")[0];

                // HTML que contiene múltiples filas
                var filasHTML = `
        <tr class="bg-dark text-center">
            <td class="text-light">CLAVE SAT DEL PRODUCTO</td>
            <td class="text-light">DESCRIPCION CATALOGO SAT</td>
            <td class="text-light">CANTIDAD</td>
            <td class="text-light">CLAVE DE UNIDAD</td>
            <td class="text-light">KILOGRAMOS</td>
            <td class="text-light">FRACCION ARANCELARIA</td>
            <td class="text-light">TIPO DE MATERIAL</td>
        </tr>
        <tr>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
        </tr>
        <tr class="text-center">
            <td>PEDIMENTO</td>
            <td>CLAVE MATERIAL PELIGROSO</td>
            <td>CLAVE TIPO DE EMBALAJE</td>
            <td>DOCUMENTO ADUANERO</td>
            <td>REGIMEN ADUANERO</td>
            <td colspan="2">RFC DE IMPORTADOR</td>
        </tr>
        <tr>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td><input class="form-control" type="text" name=""></td>
            <td colspan="2"><input class="form-control" type="text" name=""></td>
        </tr>
    `;

                // Añadir filas al final del tbody
                tbody.insertAdjacentHTML('beforeend', filasHTML);
            }

            function eliminarUltimaSerie() {
                var tbody = document.getElementById("incrementableTable").getElementsByTagName("tbody")[0];
                var totalFilas = tbody.rows.length;

                // Verifica que existan al menos 4 filas para eliminar una serie completa
                if (totalFilas >= 4) {
                    for (let i = 0; i < 4; i++) {
                        tbody.deleteRow(tbody.rows.length - 1); // Elimina la última fila
                    }
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("addRowButton").addEventListener("click", agregarIncrementable);
                document.getElementById("removeRowButton").addEventListener("click", eliminarUltimaSerie);
            });
        </script>
</body>

</html>