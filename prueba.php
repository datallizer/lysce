<?php

if (isset($_GET['id'])) {
    $registro_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM ftl WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $registro = mysqli_fetch_array($query_run);
        $titulo = $registro['tipoFtl'];
?>
        <form action="codeftl.php" method="POST" class="row justify-content-evenly">
        <input class="form-control" value="<?= $registro['id']; ?>" type="hidden" name="id">
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
                <input class="form-control" value="LYSCE-<?= $registro['id']; ?>" disabled>
                <p style="margin: 5px;">Aguascalientes, Ags a</p>
                <input class="form-control" type="text" name="fecha" id="" value="<?= $registro['fecha']; ?>">
            </div>
            <div class="col-12 text-center bg-warning p-1" style="border: 1px solid #666666;border-bottom:0px;">
                <select class="form-select bg-warning" name="tipoFtl" required>
                    <option disabled>Selecciona un servicio</option>
                    <?php
                    $query = "SELECT * FROM tiposervicio WHERE tipoServicio = 'ftl'";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($titulo = mysqli_fetch_assoc($result)) {
                            $nombre = $titulo['nombreServicio'];
                            $selected = ($registro['tipoFtl'] == $nombre) ? "selected" : ""; // Verifica si es el seleccionado
                            echo "<option value='$nombre' $selected>$nombre</option>";
                        }
                    }
                    ?>
                </select>

            </div>
            <div class="col-12 p-3" style="border: 1px solid #666666; border-bottom:0px;">
                <p class="mb-1"><b>Cliente</b></p>
                <select class="form-select mb-3" name="idCliente" id="cliente">
                    <option disabled>Selecciona un cliente</option>
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
                <select class="form-select" name="idOrigen" id="origen">
                    <option disabled>Selecciona el origen</option>
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
                <select class="form-select" name="idAduana" id="aduana">
                    <option disabled>Selecciona el aduana</option>
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
                <select class="form-select" name="idDestino" id="destino">
                    <option disabled>Selecciona el destino final</option>
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

            <div class="col-7 mt-3 mb-3">
                <div class="row justify-content-start">
                    <div class="col-8">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Distancia:</b>
                            <input name="distanciaOrigenDestinoMillas" class="form-control" style="width: 90px; display: inline-block;" value="<?= $registro['distanciaOrigenDestinoMillas']; ?>" type="text" id="millas" oninput="convertirAMetros()">
                            millas
                            <input name="distanciaOrigenDestinoKms" value="<?= $registro['distanciaOrigenDestinoKms']; ?>" class="form-control" style="width: 90px; display: inline-block;" type="text" id="km" oninput="convertirAMillas()"> Kms
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Tiempo / Recorrido:</b>
                            <input name="tiempoRecorridoOrigenDestino" value="<?= $registro['tiempoRecorridoOrigenDestino']; ?>" class="form-control" style="width: 110px; display: inline-block;" type="text">
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Operador:</b>
                            <input name="servicio" value="<?= $registro['servicio']; ?>" class="form-control" style="width: 167px; display: inline-block;" type="text">
                        </p>
                    </div>
                    <div class="col-4">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Total CFT:</b>
                            <input name="totalFt3" class="form-control" style="width: 80px; display: inline-block;" type="text" id="ft3Total" readonly>
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Total m3:</b>
                            <input name="totalM3" class="form-control" style="width: 80px; display: inline-block;" type="text" id="m3Total" readonly>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-5 mt-3 mb-3 text-end">
                <p style="display: inline-block;margin-bottom: 5px;">
                    <b>Distancia:</b>
                    <input name="distanciaDestinoFinalMillas" value="<?= $registro['distanciaDestinoFinalMillas']; ?>" class="form-control" style="width: 90px; display: inline-block;" type="text" id="milla" oninput="convertirAMetrosDos()">
                    millas
                    <input name="distanciaDestinoFinalKms" value="<?= $registro['distanciaDestinoFinalKms']; ?>" class="form-control" style="width: 90px; display: inline-block;" type="text" id="kms" oninput="convertirAMillasDos()"> Kms
                </p><br>

                <p style="display: inline-block;margin-bottom: 5px;">
                    <b>Tiempo / Recorrido:</b>
                    <input name="tiempoRecorridoDestinoFinal" value="<?= $registro['tiempoRecorridoDestinoFinal']; ?>" class="form-control" style="width: 80px; display: inline-block;" type="text">
                </p><br>

                <p style="display: inline-block;margin-bottom: 5px;">
                    <b>Operador:</b>
                    <input name="operador" value="<?= $registro['operador']; ?>" class="form-control" style="width: 167px; display: inline-block;" type="text">
                </p>

                <p style="display: inline-block;">
                    <b>Unidad:</b>
                    <input name="unidad" value="<?= $registro['unidad']; ?>" class="form-control" style="width: 230px; display: inline-block;" type="text">
                </p>

            </div>

            <div class="col-12 text-center p-2">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <p style="color: #fff;"><b>DESCRIPCIÓN DE LAS MERCANCÍAS</b></p>
                    </div>
                    <table class="table table-striped" id="miTablaCotizacion" style="margin-bottom: 0px;">
                        <tr>
                            <th>Cantidad</th>
                            <th>Unidad medida</th>
                            <th>Descripción</th>
                            <th>Dimensiones</th>
                            <th>Peso</th>
                            <th>Valor factura</th>
                        </tr>
                        <?php
                        // Obtener los registros de descripcionmercanciasftl relacionados con el ID de ftl
                        $query_desc = "SELECT * FROM descripcionmercanciasftl WHERE idFtl='$registro_id'";
                        $query_run_desc = mysqli_query($con, $query_desc);

                        if (mysqli_num_rows($query_run_desc) > 0) {
                            while ($mercancia = mysqli_fetch_assoc($query_run_desc)) {
                        ?>
                                <tr>
                                   
                                    <td>
                                        <input style="width: 60px;" class="form-control mb-3" type="text" name="cantidad[]" value="<?= $mercancia['cantidad']; ?>" oninput="convertToCmAndCalculateVolume(this)">
                                        <p>NMFC</p>
                                    </td>
                                    <td>
                                        <input class="form-control mb-1" type="text" name="unidadMedida[]" value="<?= $mercancia['unidadMedida']; ?>">
                                        <input class="form-control" type="text" name="nmfc[]" value="<?= $mercancia['nmfc']; ?>" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="descripcion[]" value="<?= $mercancia['descripcion']; ?>">
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6">
                                                <input class="form-control mb-1" type="text" name="largoPlg[]" placeholder="Largo (pulgadas)" oninput="convertToCmAndCalculateVolume(this)" value="<?= $mercancia['largoPlg']; ?>">
                                                <input class="form-control mb-1" type="text" name="anchoPlg[]" placeholder="Ancho (pulgadas)" oninput="convertToCmAndCalculateVolume(this)" value="<?= $mercancia['anchoPlg']; ?>">
                                                <input class="form-control" type="text" name="altoPlg[]" placeholder="Alto (pulgadas)" oninput="convertToCmAndCalculateVolume(this)" value="<?= $mercancia['altoPlg']; ?>">
                                                <p class="mb-3">pulgadas</p>
                                                <input class="form-control" type="text" name="piesCubicos[]" placeholder="pies cúbicos" value="<?= $mercancia['piesCubicos']; ?>" readonly>
                                                <p>ft³</p>
                                            </div>
                                            <div class="col-6">
                                                <input class="form-control mb-1" type="text" id="altoFilaCm" name="largoCm[]" placeholder="Largo (mts)" oninput="convertToInchesAndCalculateVolume(this)" value="<?= $mercancia['largoCm']; ?>">
                                                <input class="form-control mb-1" type="text" id="anchoFilaCm" name="anchoCm[]" placeholder="Ancho (mts)" oninput="convertToInchesAndCalculateVolume(this)" value="<?= $mercancia['anchoCm']; ?>">
                                                <input class="form-control" type="text" id="profundidadFilaCm" name="altoCm[]" placeholder="Alto (mts)" oninput="convertToInchesAndCalculateVolume(this)" value="<?= $mercancia['altoCm']; ?>">
                                                <p class="mb-3">mts</p>
                                                <input class="form-control" type="text" name="metrosCubicos[]" placeholder="metros cúbicos" value="<?= $mercancia['metrosCubicos']; ?>" readonly>
                                                <p>m³</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input class="form-control mb-1" type="text" placeholder="lbs" name="libras[]" id="pesoFilaMercanciaLbs" oninput="convertToKg(this); actualizarTotales();" value="<?= $mercancia['libras']; ?>">
                                        <input class="form-control" type="text" placeholder="kgs" name="kilogramos[]" id="pesoFilaMercanciaKgs" oninput="convertToLbs(this); actualizarTotales();" value="<?= $mercancia['kilogramos']; ?>">
                                    </td>
                                    <td><input class="form-control" id="valorFilaMercancia" type="text" name="valorFactura[]" placeholder="Precio total" oninput="actualizarTotales()" value="<?= $mercancia['valorFactura']; ?>">
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                        }
                        ?>
                    </table>
                    <div class="text-center p-2">
                        <button class="btn btn-danger" type="button" onclick="eliminarUltimaFila()">-</button>
                        <button class="btn btn-secondary" type="button" onclick="agregarFila()">+</button>
                    </div>
                </div>

                <div class="row mt-3 mb-3">
                    <div class="col-3 text-center">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            Total bultos <input class="form-control" type="text" name="totalBultos" id="totalBultos" style="width: 80px; display: inline-block;" readonly>
                        </p>
                        <p style="display: inline-block;margin-bottom: 5px;">
                            1 <input class="form-control" style="width: 80px; display: inline-block;" type="text" name="moneda" id="moneda" value="<?= $registro['moneda']; ?>"> = <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="valorMoneda" name="valorMoneda" value="<?= $registro['valorMoneda']; ?>" oninput="actualizarTotales()">
                        </p>
                    </div>

                    <div class="col-4">
                        <table class="text-end">
                            <tr>
                                <td>Peso total de la mercancía</td>
                                <td><input class="form-control" style="width: 120px; display: inline-block;" type="text" id="pesoMercanciaLbs" name="pesoMercanciaLbs" readonly> lbs</td>
                            </tr>
                            <tr>
                                <td colspan="2"><input class="form-control" style="width: 120px; display: inline-block;" type="text" id="pesoMercanciaKgs" name="pesoMercanciaKgs" readonly> kgs</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-5">
                        <table class="text-end w-100">
                            <tr>
                                <td>VALOR TOTAL DE LA MERCANCÍA USD</td>
                                <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorMercancia" name="valorMercancia" readonly oninput="actualizarSubtotal();"></td>
                            </tr>
                            <tr>
                                <td>VALOR TOTAL DE LA MERCANCÍA MXN</td>
                                <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorComercial" name="valorComercial" readonly></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <p class="text-center" style="color: #fff;"><b>TIPO DE SERVICIO</b></p>
                    </div>
                    <table class="table table-striped table-bordered" style="margin-bottom: 0px;" id="servicioTable">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Tiempo de transito</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_servicio = "SELECT * FROM servicioftl WHERE idFtl='$registro_id'";
                            $query_run_servicio = mysqli_query($con, $query_servicio);

                            if (mysqli_num_rows($query_run_servicio) > 0) {
                                while ($servicio = mysqli_fetch_assoc($query_run_servicio)) {
                            ?>
                                    <tr>
                                        
                                        <td>
                                            <select class="form-select" name="conceptoServicio[]">
                                                <option disabled>Selecciona un tipo de servicio</option>
                                                <?php
                                                $query = "SELECT * FROM tiposervicio WHERE tipoServicio = 'ftl'";
                                                $result = mysqli_query($con, $query);
                                                $conceptoSeleccionado = $servicio['conceptoServicio'];

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($registro_servicio = mysqli_fetch_assoc($result)) {
                                                        $nombre = $registro_servicio['nombreServicio'];
                                                        $selected = ($nombre == $conceptoSeleccionado) ? "selected" : "";
                                                        echo "<option value='$nombre' $selected>$nombre</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>

                                        <td><input type="text" name="tiempoServicio[]" value="<?= $servicio['tiempoServicio']; ?>" class="form-control"></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="col-12 text-center p-2">
                        <button class="btn btn-danger" id="removeServiceButton" type="button">-</button>
                        <button class="btn btn-secondary" id="addServiceButton" type="button" onclick="agregarTipoServicio()">+</button>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <p class="text-center" style="color: #fff;"><b>DETERMINACIÓN DE INCREMENTABLES</b></p>
                    </div>
                    <table class="table table-striped tabñe-bordered" id="incrementableTable" style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th>Incrementable</th>
                                <th>USD</th>
                                <th>MXN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_incrementable = "SELECT * FROM incrementablesftl WHERE idFtl='$registro_id'";
                            $query_run_incrementable = mysqli_query($con, $query_incrementable);

                            if (mysqli_num_rows($query_run_incrementable) > 0) {
                                while ($incrementable = mysqli_fetch_assoc($query_run_incrementable)) {
                            ?>
                                    <tr>
                                        <td>
                                            <select class="form-select" name="incrementable[]">
                                                <option disabled>Selecciona un incrementable</option>
                                                <?php
                                                $query = "SELECT * FROM tipoincrementable WHERE tipo = 'ftl'";
                                                $result = mysqli_query($con, $query);
                                                $actual_incrementable = $incrementable['incrementable']; // Valor actual de la BD

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($registro_incrementable = mysqli_fetch_assoc($result)) {
                                                        $option_incrementable = $registro_incrementable['incrementable'];
                                                        $selected_incrementable = ($option_incrementable == $actual_incrementable) ? "selected" : ""; // Comparar y marcar como seleccionado
                                                        echo "<option value='$option_incrementable' $selected_incrementable>$option_incrementable</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>

                                        <td><input type="number" name="incrementableUsd[]" class="form-control usd-input" value="<?= $incrementable['incrementableUsd']; ?>" oninput="actualizarMontoGasto(this); updateRow(this);"></td>
                                        <td><input type="text" name="incrementableMx[]" class="form-control mxn-input" value="<?= $incrementable['incrementableMx']; ?>" readonly></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr id="totalRow">
                                <td class="text-end"><b>TOTAL</b></td>
                                <td><input type="text" id="totalUSD" name="totalIncrementableUsd" class="form-control" value="0" readonly></td>
                                <td><input type="text" id="totalMXN" name="totalIncrementableMx" class="form-control" value="0" readonly></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="col-12 text-center p-2">
                        <button class="btn btn-danger" id="removeRowButton" type="button">-</button>
                        <button class="btn btn-secondary" id="addRowButton" type="button" onclick="agregarIncrementable()">+</button>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <p class="text-center" style="color: #fff;"><b>GASTOS POR FLETE TERRESTRE</b></p>
                    </div>
                    <table class="table table-striped table-bordered" id="tablaGasto" style="margin-bottom: 0px;">
                        <tbody>
                            <?php
                            $query_gasto = "SELECT * FROM gastosftl WHERE idFtl='$registro_id'";
                            $query_run_gasto = mysqli_query($con, $query_gasto);

                            if (mysqli_num_rows($query_run_gasto) > 0) {
                                while ($gasto = mysqli_fetch_assoc($query_run_gasto)) {
                            ?>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-9">
                                                    <input type="text" class="form-control" name="conceptoGasto[]" value="<?= $gasto['conceptoGasto']; ?>">
                                                </div>
                                                <?php if ($gasto['conceptoGasto'] == "Seguro de tránsito de mercancía") : ?>
                                                    <div class="col-3">
                                                        <input type="text" class="form-control" name="porcentajeSeguro" value="<?= $registro['porcentajeSeguro']; ?>" oninput="actualizarSubtotal();">
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check float-end">
                                                <input class="form-check-input" type="checkbox" name="ivaGasto[]" id="flexCheck4"
                                                    <?php if ($gasto['ivaGasto'] == 1) echo 'checked'; ?>>
                                                <label class="form-check-label" for="flexCheck4"> IVA 16% </label>
                                            </div>
                                        </td>
                                        <td colspan="2" class="text-end">
                                            <input type="text" <?php if ($gasto['conceptoGasto'] == "Seguro de tránsito de mercancía") echo 'id="montoSeguro"'; ?> value="<?= $gasto['montoGasto']; ?>" class="form-control" name="montoGasto[]" oninput="actualizarSubtotal()" <?php if ($gasto['conceptoGasto'] == "Seguro de tránsito de mercancía") echo 'readonly'; ?>>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No se encontraron registros</td></tr>";
                            }
                            ?>
                            <tr class="text-end">
                                <td colspan="2">Subtotal</td>
                                <td colspan="2" style="width:20%;"><input class="form-control" name="subtotalFlete" type="text" readonly></td>
                            </tr>
                            <tr class="text-end">
                                <td colspan="2">I.V.A 16%</td>
                                <td colspan="2"><input class="form-control" name="impuestosFlete" type="text" readonly></td>
                            </tr>
                            <tr class="text-end">
                                <td colspan="2">
                                    <div class="form-check float-end">
                                        <input class="form-check-input" type="checkbox" name="retencionFleteCheck" id="retencionCheck"
                                            <?= (!empty($registro['retencionFlete']) && $registro['retencionFlete'] > 0) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="retencionCheck"> Retención 4% </label>
                                    </div>
                                </td>
                                <td colspan="2"><input class="form-control" name="retencionFlete" type="text" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center p-2">
                        <button type="button" class="btn btn-secondary" onclick="nuevoGasto()">+</button>
                    </div>
                </div>
            </div>

            <script>
                function actualizarSubtotal() {
                    let subtotal = 0;
                    let iva = 0;
                    let retencion = 0;

                    document.querySelectorAll("#tablaGasto tbody tr").forEach((fila) => {
                        let montoInput = fila.querySelector('input[name="montoGasto[]"]');
                        let checkboxIVA = fila.querySelector('input[name="ivaGasto[]"]');

                        if (montoInput) {
                            let monto = parseFloat(montoInput.value) || 0;
                            subtotal += monto;

                            if (checkboxIVA && checkboxIVA.checked) {
                                iva += monto * 0.16;
                            }
                        }
                    });

                    let checkboxRetencion = document.querySelector('#retencionCheck');
                    if (checkboxRetencion && checkboxRetencion.checked) {
                        retencion = subtotal * 0.04;
                    } else {
                        retencion = 0;
                    }

                    // Actualizar los valores de los inputs
                    document.querySelector('input[name="subtotalFlete"]').value = subtotal.toFixed(2);
                    document.querySelector('input[name="impuestosFlete"]').value = iva.toFixed(2);
                    document.querySelector('input[name="retencionFlete"]').value = retencion.toFixed(2);

                    var sumaGastos = 0;

                    // Obtener todos los inputs de montoGasto y sumar sus valores
                    var montoGastoInputs = document.querySelectorAll("[name='montoGasto[]']");
                    montoGastoInputs.forEach(input => {
                        var valor = parseFloat(input.value) || 0;
                        sumaGastos += valor;
                    });

                    // Calcular el monto del seguro
                    let valorMercancia = parseFloat(document.querySelector('input[name="valorMercancia"]').value) || 0;
                    let porcentajeSeguroInput = document.querySelector('input[name="porcentajeSeguro"]').value;
                    let montoSeguroInput = document.querySelector('input[id="montoSeguro"]');

                    // Convertir porcentaje a decimal (ejemplo: "38%" -> 0.38)
                    let porcentajeSeguro = parseFloat(porcentajeSeguroInput.replace('%', '')) / 100;
                    let montoSeguro = valorMercancia * porcentajeSeguro;

                    // Si el monto calculado es menor que 120, se fija en 120
                    if (montoSeguro < 120) {
                        montoSeguro = 120;
                    }

                    montoSeguroInput.value = montoSeguro.toFixed(2);

                    // Actualizar el campo subtotalFlete con la suma
                    var subtotalFleteInput = document.querySelector("[name='subtotalFlete']");
                    if (subtotalFleteInput) {
                        subtotalFleteInput.value = sumaGastos.toFixed(2);
                    }

                    // Calcular el total de la cotización
                    let totalCotizacion = (subtotal + iva - retencion).toFixed(2);
                    document.querySelector('input[name="totalCotizacionNumero"]').value = totalCotizacion;
                }

                document.addEventListener("input", actualizarSubtotal);
                document.addEventListener("change", actualizarSubtotal);
            </script>






            <table class="mt-3 bg-warning w-100" style="border: 1px solid #000000;padding:5px;">
                <tr class="text-end">
                    <td style="border-right: 1px solid #000000;padding:5px;"><b>TOTAL USD</b></td>
                    <td style="width: 180px;">
                        <input class="form-control bg-warning" name="totalCotizacionNumero" id="totalCotizacionNumero" value="<?= $registro['totalCotizacionNumero']; ?>" type="text" readonly>
                    </td>
                </tr>
                <tr class="text-center" style="border-top: 1px solid #000000;padding:5px;">
                    <td colspan="2">
                        <input class="form-control bg-warning" name="totalCotizacionTexto" id="totalCotizacionTexto" type="text" readonly>
                    </td>
                </tr>
            </table>


            <div class="col-12 p-0">
                <table class="mt-3 w-100" style="border: 1px solid #000000;">
                    <tr class="text-center bg-secondary">
                        <td colspan="2" style="border-bottom: 1px solid #000000;padding:5px;color:#fff;"><b>OBSERVACIONES</b></td>
                    </tr>
                    <tr>
                        <td>
                            <textarea value="" class="form-control" name="observaciones" style="min-height: 200px;" id="observaciones"><?= $registro['observaciones']; ?></textarea>
                        </td>
                    </tr>
                    <tr style="padding:5px;">
                        <td> </td>
                        <td></td>
                    </tr>
                    <tr style="padding:5px;">
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="padding:5px;">
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer mt-5">
                <a href="ftl.php" class="btn btn-secondary m-1">Cancelar</a>
                <button type="submit" class="btn btn-success m-1" name="update">Guardar</button>
            </div>
        </form>
<?php
    } else {
        echo "<h4>No Such Id Found</h4>";
    }
}
?>