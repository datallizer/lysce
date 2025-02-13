<?php
session_start();
require 'dbcon.php';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : ''; // Obtener el mensaje de la sesión

if (!empty($message)) {
    // HTML y JavaScript para mostrar la alerta...
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const message = " . json_encode($message) . ";
                Swal.fire({
                    title: 'NOTIFICACIÓN',
                    text: message,
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hacer algo si se confirma la alerta
                    }
                });
            });
        </script>";
    unset($_SESSION['message']); // Limpiar el mensaje de la sesión
}
//Verificar si existe una sesión activa y los valores de usuario y contraseña están establecidos
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Consultar la base de datos para verificar si los valores coinciden con algún registro en la tabla de usuarios
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    // Si se encuentra un registro coincidente, el usuario está autorizado
    if (mysqli_num_rows($result) > 0) {
        // El usuario está autorizado, se puede acceder al contenido
    } else {
        // Redirigir al usuario a una página de inicio de sesión
        header('Location: login.php');
        exit(); // Finalizar el script después de la redirección
    }
} else {
    // Redirigir al usuario a una página de inicio de sesión si no hay una sesión activa
    header('Location: login.php');
    exit(); // Finalizar el script después de la redirección
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="images/ico.ico">
    <title>Importacion LTL USA | LYSCE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container-fluid p-5">
        <form action="codeaereoimportacion.php" method="POST" class="row justify-content-evenly">
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
                <?php
                $query = "SELECT MAX(id) AS max_id FROM aereoimportacion"; // Consulta para obtener el ID más alto
                $result = mysqli_query($con, $query);
                if ($result && $row = $result->fetch_assoc()) {
                    $lastID = $row['max_id'];
                } else {
                    $lastID = 0;
                }
                $newNumber = $lastID + 1;
                ?>
                <input class="form-control" value="<?php echo "LYSCE-" . str_pad($newNumber, 5, "0", STR_PAD_LEFT); ?>" disabled>
                <p style="margin: 5px;">Aguascalientes, Ags a</p>
                <input class="form-control" type="text" name="fecha" id="expedicion" value="">
            </div>
            <div class="col-12 text-center bg-warning p-1" style="border: 1px solid #666666;border-bottom:0px;">
                <p><b>COTIZACION DE FLETE TRAILER COMPLETO / FTL USA / DRY VAN 53 FT / IN BOND FREIGHT CANADA</b></p>
            </div>
            <div class="col-12 p-3" style="border: 1px solid #666666; border-bottom:0px;">
                <p class="mb-1"><b>Cliente</b></p>
                <select class="form-select mb-3" name="idCliente" id="cliente">
                    <option selected>Selecciona un cliente</option>
                    <?php
                    $query = "SELECT * FROM clientes WHERE estatus = 1";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($registro = mysqli_fetch_assoc($result)) {
                            $nombre = $registro['cliente'];
                            $idCliente = $registro['id'];
                            echo "<option value='$idCliente'>" . $nombre . "</option>";
                        }
                    }
                    ?>
                </select>
                <p id="detalleCliente"></p>
            </div>
            <div class="col-4 p-3" style="border: 1px solid #666666;">
                <p class="mb-1"><b>Origen</b></p>
                <select class="form-select" name="idOrigen" id="origen">
                    <option selected>Selecciona el origen</option>
                    <?php
                    $query = "SELECT * FROM proveedores WHERE estatus = 1";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($registro = mysqli_fetch_assoc($result)) {
                            $nombre = $registro['proveedor'];
                            $idOrigen = $registro['id'];
                            echo "<option value='$idOrigen'>" . $nombre . "</option>";
                        }
                    }
                    ?>
                </select>
                <p id="detalleCliente"></p>
            </div>
            <div class="col-4 p-3" style="border: 1px solid #666666;">
                <p class="mb-1"><b>Destino en frontera</b></p>
                <select class="form-select" name="idDestinoFr" id="Destino">
                    <option selected>Selecciona el destino en frontera</option>
                    <?php
                    $query = "SELECT * FROM proveedores WHERE estatus = 1";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($registro = mysqli_fetch_assoc($result)) {
                            $nombre = $registro['proveedor'];
                            $idOrigen = $registro['id'];
                            echo "<option value='$idOrigen'>" . $nombre . "</option>";
                        }
                    }

                    ?>
                </select>
                <p id="detalleCliente"></p>
            </div>
            <div class="col-4 p-3" style="border: 1px solid #666666;">
                <p class="mb-1"><b>Destino Final</b></p>
                <select class="form-select" name="idOrigen" id="origen">
                    <option selected>Selecciona el destino final</option>
                    <?php
                    $query = "SELECT * FROM proveedores WHERE estatus = 1";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($registro = mysqli_fetch_assoc($result)) {
                            $nombre = $registro['proveedor'];
                            $idOrigen = $registro['id'];
                            echo "<option value='$idOrigen'>" . $nombre . "</option>";
                        }
                    }

                    ?>
                </select>
                <p id="detalleDestino"></p>
            </div>

            <div class="col-8 mt-3 mb-3">
                <div class="row justify-content-start">
                    <div class="col-6">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Distancia:</b>
                            <input name="distanciaOrigenDestinoMillas" class="form-control" style="width: 90px; display: inline-block;" type="text" id="millas" oninput="convertirAMetros()">
                            millas |
                            <input name="distanciaOrigenDestinoKilometros" class="form-control" style="width: 90px; display: inline-block;" type="text" id="km" oninput="convertirAMillas()"> Kms
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Tiempo / Recorrido:</b>
                            <input class="form-control" style="width: 110px; display: inline-block;" type="text" id="recorrido">
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Operador:</b>
                            <input class="form-control" style="width: 167px; display: inline-block;" type="text" id="servicio">
                        </p>
                    </div>
                    <div class="col-6">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Total CFT:</b>
                            <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="ft3Total" readonly>
                        </p><br>

                        <p style="display: inline-block;margin-bottom: 5px;">
                            <b>Total m3:</b>
                            <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="m3Total" readonly>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-4 mt-3 mb-3">
                <p style="display: inline-block;margin-bottom: 5px;">
                    <b>Distancia:</b>
                    <input name="distanciaOrigenDestinoMillas" class="form-control" style="width: 90px; display: inline-block;" type="text" id="milla" oninput="convertirAMetrosDos()">
                    millas |
                    <input name="distanciaOrigenDestinoKilometros" class="form-control" style="width: 90px; display: inline-block;" type="text" id="kms" oninput="convertirAMillasDos()"> Kms
                </p><br>

                <p style="display: inline-block;margin-bottom: 5px;">
                    <b>Tiempo / Recorrido:</b>
                    <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="recorrido">
                </p><br>

                <p style="display: inline-block;margin-bottom: 5px;">
                    <b>Operador:</b>
                    <input class="form-control" style="width: 167px; display: inline-block;" type="text" id="servicio">
                </p>

                <p style="display: inline-block;">
                    <b>Unidad:</b>
                    <input class="form-control" style="width: 230px; display: inline-block;" type="text" id="servicio" value="Servicio trailer directo FTL">
                </p>

            </div>

            <div class="col-12 bg-light text-center p-2">
                <p><b>DESCRIPCIÓN DE LAS MERCANCIAS</b></p>
                <table class="table table-striped" id="miTablaCotizacion">
                    <tr>
                        <th>Cantidad</th>
                        <th>Unidad medida</th>
                        <th>Descripción</th>
                        <th>Dimensiones</th>
                        <th>Peso</th>
                        <th>Valor factura</th>
                        <th>Precio total</th>
                    </tr>
                </table>
                <button class="btn btn-danger" type="button" onclick="eliminarUltimaFila()">-</button>
                <button class="btn btn-secondary" type="button" onclick="agregarFila()">+</button>

                <div class="row mt-3 mb-3">
                    <div class="col-3 text-center">
                        <p style="display: inline-block;margin-bottom: 5px;">
                            1 <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="moneda" value="USD"> = <input class="form-control" style="width: 80px; display: inline-block;" type="text" id="valorMoneda" name="valorMoneda" value="18.6" oninput="actualizarTotales()">
                        </p>
                    </div>

                    <div class="col-4">
                        <table class="text-end">
                            <tr>
                                <td>Peso total de la mercancia</td>
                                <td><input class="form-control" style="width: 120px; display: inline-block;" type="text" id="pesoMercanciaLbs" name="pesoMercanciaLbs" readonly> lbs</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input class="form-control" style="width: 120px; display: inline-block;" type="text" id="pesoMercanciaKgs" name="pesoMercanciaKgs" readonly> kgs</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-5">
                        <table class="text-end w-100">
                            <tr>
                                <td>Valor total de la mercancia</td>
                                <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorMercancia" name="valorMercancia" readonly></td>
                            </tr>
                            <tr>
                                <td>VALOR TOTAL COMERCIAL USD</td>
                                <td>$<input class="form-control mt-1" style="width: 110px; display: inline-block;" type="text" id="valorComercial" name="valorComercial" readonly></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-12 mt-5">
                <p class="text-center"><b>DETERMINACION DE INCREMENTABLES</b></p>
                <table class="table table-striped mt-3" id="incrementableTable">
                    <thead>
                        <tr>
                            <th>Incrementable</th>
                            <th>USD</th>
                            <th>MXN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas predeterminadas -->
                        <tr>
                            <td>FLETE EXTRANJERO</td>
                            <td><input type="number" class="usd-input" value="" oninput="updateRow(this)"></td>
                            <td><input type="text" class="mxn-input" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>MANIOBRAS</td>
                            <td><input type="number" class="usd-input" value="" oninput="updateRow(this)"></td>
                            <td><input type="text" class="mxn-input" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>ALMACENAJE</td>
                            <td><input type="number" class="usd-input" value="" oninput="updateRow(this)"></td>
                            <td><input type="text" class="mxn-input" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>CANCELACION BOND</td>
                            <td><input type="number" class="usd-input" value="" oninput="updateRow(this)"></td>
                            <td><input type="text" class="mxn-input" value="0" readonly></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><b>TOTAL</b></td>
                            <td><span id="totalUSD">$0.00</span></td>
                            <td><span id="totalMXN">$0.00</span></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="col-12 text-center">
                    <button class="btn btn-secondary" id="addRowButton" type="button">+</button>
                    <button class="btn btn-danger" id="removeRowButton" type="button">-</button>
                </div>
            </div>


            <script>
                document.addEventListener("DOMContentLoaded", () => {

                    const tableBody = document.querySelector("#incrementableTable tbody");
                    const totalUSD = document.getElementById("totalUSD");
                    const totalMXN = document.getElementById("totalMXN");
                    const addRowButton = document.getElementById("addRowButton");
                    const removeRowButton = document.getElementById("removeRowButton");

                    function addRow() {
                        const newRow = document.createElement("tr");
                        newRow.innerHTML = `
            <td><input type="text" placeholder="Incrementable"></td>
            <td><input type="text" class="usd-input" value="0" oninput="updateTotal()"></td>
            <td><input type="text" class="mxn-input" value="0" oninput="updateTotal()"></td>
        `;
                        tableBody.appendChild(newRow);
                        updateTotal();
                    }

                    function removeRow() {
                        const rows = tableBody.getElementsByTagName("tr");
                        if (rows.length > 4) {
                            tableBody.removeChild(rows[rows.length - 1]);
                            updateTotal();
                        } else {
                            alert("No puedes eliminar más filas predeterminadas.");
                        }
                    }

                    function updateTotal() {
                        let totalUSDValue = 0;
                        let totalMXNValue = 0;

                        document.querySelectorAll(".usd-input").forEach(input => {
                            totalUSDValue += parseFloat(input.value) || 0;
                        });

                        document.querySelectorAll(".mxn-input").forEach(input => {
                            totalMXNValue += parseFloat(input.value) || 0;
                        });

                        totalUSD.textContent = `$${totalUSDValue.toFixed(2)}`;
                        totalMXN.textContent = `$${totalMXNValue.toFixed(2)}`;
                    }

                    addRowButton.addEventListener("click", addRow);
                    removeRowButton.addEventListener("click", removeRow);

                    updateTotal();
                });
            </script>

            <div class="col-12 mt-5">
                <p class="text-center"><b>GASTOS POR FLETE TERRESTRE</b></p>
                <table class="table table-striped mt-3">
                    <tr>
                        <td>Flete Terrestre EUA</td>
                        <td class="text-end"><input type="text" name="" value=""></td>
                    </tr>
                    <tr>
                        <td>Cruce de fontera / transfer</td>
                        <td class="text-end"><input type="text" name="" value=""></td>
                    </tr>
                    <tr>
                        <td>Flete Terrestre MEXICO</td>
                        <td class="text-end"><input type="text" name="" value=""></td>
                    </tr>
                    <tr>
                        <td>Seguro Transito de Mercancias = Valor total MN * .0035</td>
                        <td class="text-end"><input type="text" name="" value=""></td>
                    </tr>
                    <tr class="text-end">
                        <td>Subtotal</td>
                        <td><span id="">$</span></td>
                    </tr>
                    <tr class="text-end">
                        <td>I.V.A 16%</td>
                        <td><span id="">$</span></td>
                    </tr>
                    <tr class="text-end">
                        <td>Retención 4%</td>
                        <td><span id="">$</span></td>
                    </tr>
                    <tr class="text-end">
                        <td>Total</td>
                        <td><span id="">$</span></td>
                    </tr>
                </table>

                <table class="mt-3 bg-warning w-100" style="border: 1px solid #000000;padding:5px;">
                    <tr class="text-end">
                        <td style="border-right: 1px solid #000000;padding:5px;"><b>TOTAL USD</b></td>
                        <td><b><span id="">$</span></b></td>
                    </tr>
                    <tr class="text-center" style="border-top: 1px solid #000000;padding:5px;">
                        <td colspan="2"><b><span id=""></span> DOLARES /100 USD</b></td>
                    </tr>
                </table>
            </div>

            <div class="col-12">
                <table class="mt-3 w-100" style="border: 1px solid #000000;padding:5px;">
                    <tr class="text-center bg-secondary">
                        <td colspan="2" style="border-bottom: 1px solid #000000;padding:5px;color:#fff;"><b>OBSERVACIONES</b></td>
                    </tr>
                    <tr style="padding:5px;">
                        <td>Se recomienda servicio de seguro de transito de mercancias </td>
                        <td>Precio valido por 30 dias</td>
                    </tr>
                    <tr style="padding:5px;">
                        <td>Solicitar equipo con 24 Hrs de anticipacion</td>
                        <td>Precio sujet a cambio a base de disponibilidad</td>
                    </tr>
                    <tr style="padding:5px;">
                        <td>La mercancia viaja por cuenta y riesgo del cliente</td>
                        <td>costo del seguro .0035% sobre valor mcncias / min $ 120.00 usd</td>
                    </tr>
                </table>
            </div>
    </div>
    <div class="modal-footer">
        <a href="dashboard.php" class="btn btn-secondary">Cerrar</a>
        <button type="submit" class="btn btn-primary" name="save">Guardar</button>
    </div>
    </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script>
        function updateTotals() {
            const dolarInputs = document.querySelectorAll(".dolarInput");
            const mxnOutputs = document.querySelectorAll(".mxnOutput");

            let totalUSD = 0;
            let totalMXN = 0;

            // Suma todos los valores USD y MXN
            dolarInputs.forEach((input) => {
                totalUSD += parseFloat(input.value) || 0;
            });
            mxnOutputs.forEach((output) => {
                totalMXN += parseFloat(output.value) || 0;
            });

            // Actualiza los totales en la tabla
            document.getElementById("totalDolar").textContent = `$${totalUSD.toFixed(2)}`;
            document.getElementById("totalMXN").textContent = `$${totalMXN.toFixed(2)}`;
        }

        function addRow() {
            const tabla = document.getElementById("tablaIncrementables").querySelector("tbody"); // Selecciona <tbody>
            const totalRow = document.getElementById("totalRow"); // Fila del total
            const newRow = document.createElement("tr");

            newRow.innerHTML = `
    <td><input type="text" placeholder="Incrementable"></td>
    <td><input type="number" class="dolarInput" value="" oninput="updateRow(this)"></td>
    <td><input type="text" class="mxnOutput" value="" readonly></td>
  `;

            tabla.insertBefore(newRow, totalRow); // Inserta la nueva fila antes de la fila de totales
        }

        function removeRow() {
            const tabla = document.getElementById("tablaIncrementables");
            const rows = tabla.querySelectorAll("tr");
            if (rows.length > 2) {
                tabla.deleteRow(rows.length - 2);
            } else {
                alert("No hay más filas para eliminar.");
            }
        }


        // Obtiene la fecha actual para la cotizacion
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('expedicion').value = formattedDate;

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

        function agregarFila() {
            const tabla = document.getElementById("miTablaCotizacion");
            const nuevaFila = tabla.insertRow();
            nuevaFila.innerHTML = `
                <td>
                <input style="width: 60px;" class="form-control" type="text" id="cantidadFila" name="cantidadFila" oninput="actualizarTotales()">
                <input style="width: 60px;" class="form-control" type="text" id="cantidadFila" name="cantidadCode"></td>
                <td>
                    <input class="form-control" type="text">
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                    <input class="form-control" type="text">
                </td>
                <td>
                    <div class="row">
                        <div class="col-6">
                            <input class="form-control" type="text" placeholder="Largo (pulgadas)" oninput="convertToCmAndCalculateVolume(this)">
                            <input class="form-control" type="text" placeholder="Ancho (pulgadas)" oninput="convertToCmAndCalculateVolume(this)">
                            <input class="form-control" type="text" placeholder="Alto (pulgadas)" oninput="convertToCmAndCalculateVolume(this)">
                            <p>pulgadas</p>
                            <input class="form-control" type="text" placeholder="pies cúbicos" readonly>
                            <p>ft³</p>
                        </div>
                        <div class="col-6">
                            <input class="form-control" type="text" id="altoFilaCm" name="altoFilaCm" placeholder="Largo (cms)" oninput="convertToInchesAndCalculateVolume(this)">
                            <input class="form-control" type="text" id="anchoFilaCm" name="anchoFilaCm" placeholder="Ancho (cms)" oninput="convertToInchesAndCalculateVolume(this)">
                            <input class="form-control" type="text" id="profundidadFilaCm" name="profundidadFilaCm" placeholder="Alto (cms)" oninput="convertToInchesAndCalculateVolume(this)">
                            <p>cms</p>
                            <input class="form-control" type="text" placeholder="metros cúbicos" readonly>
                            <p>m³</p>
                        </div>
                    </div>
                </td>
                <td>
                    <input class="form-control" type="text" placeholder="lbs" name="pesoFilaMercanciaLbs" id="pesoFilaMercanciaLbs" oninput="convertToKg(this); actualizarTotales();">
                    <input class="form-control" type="text" placeholder="kgs" name="pesoFilaMercanciaKgs" id="pesoFilaMercanciaKgs" oninput="convertToLbs(this); actualizarTotales();">
                </td>
                <td><input class="form-control" type="text"></td>
                <td><input class="form-control" id="valorFilaMercancia" type="text" placeholder="Precio total" oninput="actualizarTotales()"></td>
            `;
            actualizarTotales(); // Actualiza los totales al agregar la fila
        }

        function eliminarUltimaFila() {
            const tabla = document.getElementById("miTablaCotizacion");
            if (tabla.rows.length > 1) {
                tabla.deleteRow(tabla.rows.length - 1);
            } else {
                alert("No hay más filas para eliminar.");
            }
            actualizarTotales(); // Actualiza los totales después de eliminar la fila
        }

        // Calcular millas a Km de origen a aduana destino
        function convertirAMetros() {
            var millas = document.getElementById("millas").value;
            var kilometros = millas * 1.60934;
            document.getElementById("km").value = kilometros.toFixed(2);
        }

        function convertirAMillas() {
            var kilometros = document.getElementById("km").value;
            var millas = kilometros / 1.60934;
            document.getElementById("millas").value = millas.toFixed(2);
        }

        // Calcular millas a Km de origen a aduana final
        function convertirAMetrosDos() {
            var millas = document.getElementById("milla").value;
            var kilometros = millas * 1.60934;
            document.getElementById("kms").value = kilometros.toFixed(2);
        }

        function convertirAMillasDos() {
            var kilometros = document.getElementById("kms").value;
            var millas = kilometros / 1.60934;
            document.getElementById("milla").value = millas.toFixed(2);
        }

        function convertToCmAndCalculateVolume(element) {
            const row = element.closest('tr');
            const height = parseFloat(row.querySelector("[placeholder='Largo (pulgadas)']").value) || 0;
            const width = parseFloat(row.querySelector("[placeholder='Ancho (pulgadas)']").value) || 0;
            const deep = parseFloat(row.querySelector("[placeholder='Alto (pulgadas)']").value) || 0;

            // Convertir pulgadas a centímetros y calcular volumen
            row.querySelector("[placeholder='Largo (cms)']").value = (height * 2.54).toFixed(3);
            row.querySelector("[placeholder='Ancho (cms)']").value = (width * 2.54).toFixed(3);
            row.querySelector("[placeholder='Alto (cms)']").value = (deep * 2.54).toFixed(3);
            const volumeFt3 = (height * width * deep) / 1728;
            row.querySelector("[placeholder='pies cúbicos']").value = volumeFt3.toFixed(3);
            const volumeM3 = volumeFt3 * 0.0283168;
            row.querySelector("[placeholder='metros cúbicos']").value = volumeM3.toFixed(3);

            actualizarTotales(); // Actualiza los totales después de convertir
        }

        function convertToInchesAndCalculateVolume(element) {
            const row = element.closest('tr');
            const altura = parseFloat(row.querySelector("[placeholder='Largo (cms)']").value) || 0;
            const ancho = parseFloat(row.querySelector("[placeholder='Ancho (cms)']").value) || 0;
            const profundidad = parseFloat(row.querySelector("[placeholder='Alto (cms)']").value) || 0;

            // Convertir centímetros a pulgadas y calcular volumen
            row.querySelector("[placeholder='Largo (pulgadas)']").value = (altura / 2.54).toFixed(3);
            row.querySelector("[placeholder='Ancho (pulgadas)']").value = (ancho / 2.54).toFixed(3);
            row.querySelector("[placeholder='Alto (pulgadas)']").value = (profundidad / 2.54).toFixed(3);
            const height = altura / 2.54;
            const width = ancho / 2.54;
            const deep = profundidad / 2.54;
            const volumeFt3 = (height * width * deep) / 1728;
            row.querySelector("[placeholder='pies cúbicos']").value = volumeFt3.toFixed(3);
            const volumeM3 = volumeFt3 * 0.0283168;
            row.querySelector("[placeholder='metros cúbicos']").value = volumeM3.toFixed(3);

            actualizarTotales(); // Actualiza los totales después de convertir
        }

        function convertToKg(element) {
            const row = element.closest('tr');
            const lbs = parseFloat(row.querySelector("[placeholder='lbs']").value) || 0;
            const kg = lbs * 0.453592;
            row.querySelector("[placeholder='kgs']").value = kg.toFixed(3);
        }

        function convertToLbs(element) {
            const row = element.closest('tr');
            const kg = parseFloat(row.querySelector("[placeholder='kgs']").value) || 0;
            const lbs = kg / 0.453592;
            row.querySelector("[placeholder='lbs']").value = lbs.toFixed(3);
        }

        function actualizarTotales() {
            const tabla = document.getElementById("miTablaCotizacion");
            let totalLbs = 0;
            let totalKgs = 0;
            let totalValor = 0;
            let totalFt3 = 0;
            let totalM3 = 0;

            // Iterar sobre cada fila para sumar los totales
            for (let i = 1; i < tabla.rows.length; i++) {
                const fila = tabla.rows[i];

                // Obtener peso en libras, kilogramos, y valor
                const pesoLbsInput = fila.querySelector("input[name='pesoFilaMercanciaLbs']");
                const pesoKgsInput = fila.querySelector("input[name='pesoFilaMercanciaKgs']");
                const valorInput = fila.querySelector("input[id='valorFilaMercancia']");
                const ft3Input = fila.querySelector("[placeholder='pies cúbicos']");
                const m3Input = fila.querySelector("[placeholder='metros cúbicos']");

                // Obtener las dimensiones y cantidad de la fila
                const altoCm = parseFloat(fila.querySelector("input[id='altoFilaCm']").value) || 0;
                const anchoCm = parseFloat(fila.querySelector("input[id='anchoFilaCm']").value) || 0;
                const profundidadCm = parseFloat(fila.querySelector("input[id='profundidadFilaCm']").value) || 0;
                const cantidad = parseFloat(fila.querySelector("input[id='cantidadFila']").value) || 1; // Default a 1 si no hay cantidad

                // Sumar valores de peso, volumen, y valor
                if (pesoLbsInput) {
                    const pesoLbs = parseFloat(pesoLbsInput.value) || 0;
                    totalLbs += pesoLbs;
                }

                if (pesoKgsInput) {
                    const pesoKgs = parseFloat(pesoKgsInput.value) || 0;
                    totalKgs += pesoKgs;
                }

                if (valorInput) {
                    const valor = parseFloat(valorInput.value) || 0;
                    totalValor += valor;
                }

                if (ft3Input) {
                    const ft3 = parseFloat(ft3Input.value) || 0;
                    totalFt3 += ft3;
                }

                if (m3Input) {
                    const m3 = parseFloat(m3Input.value) || 0;
                    totalM3 += m3;
                }
            }

            // Mostrar los totales en los inputs correspondientes
            document.getElementById("pesoMercanciaLbs").value = totalLbs.toFixed(2);
            document.getElementById("pesoMercanciaKgs").value = totalKgs.toFixed(2);
            document.getElementById("valorMercancia").value = totalValor.toFixed(2);
            document.getElementById("ft3Total").value = totalFt3.toFixed(3);
            document.getElementById("m3Total").value = totalM3.toFixed(3);

            // Actualizar el valor comercial
            actualizarValorComercial();
            actualizarValoresUSD_MXN();
        }

        function actualizarValorComercial() {
            const valorMercancia = parseFloat(document.getElementById("valorMercancia").value) || 0;
            const valorMoneda = parseFloat(document.getElementById("valorMoneda").value) || 0;

            // Multiplicar el valor de la mercancia por el valor de la moneda
            const valorComercial = valorMercancia * valorMoneda;

            // Mostrar el valor comercial en el input correspondiente
            document.getElementById("valorComercial").value = valorComercial.toFixed(2);
        }
        function updateRow(input) {
            const valorCambio = parseFloat(document.getElementById("valorMoneda").value) || 0;
            const dolarValue = parseFloat(input.value) || 0;

            // Encuentra la fila actual y el campo de salida (MXN)
            const row = input.closest("tr");
            const mxnOutput = row.querySelector(".mxn-input");

            // Calcula y actualiza el valor en MXN
            mxnOutput.value = (dolarValue * valorCambio).toFixed(2);

            // Actualiza los totales
            updateTotalsinc();
        }
        function actualizarValoresUSD_MXN() {
            const valorCambio = parseFloat(document.getElementById("valorMoneda").value) || 0;
            const dolarInputs = document.querySelectorAll(".usd-input");

        dolarInputs.forEach((input) => {
            const row = input.closest("tr");
            const mxnOutput = row.querySelector(".mxn-input");
            const dolarValue = parseFloat(input.value) || 0;
            mxnOutput.value = (dolarValue * valorCambio).toFixed(2);
        });
        updateTotalsinc();
        }
        function updateTotalsinc() {
            const dolarInputs = document.querySelectorAll(".usd-input");
            const mxnOutputs = document.querySelectorAll(".mxn-input");

            let totalUSD = 0;
            let totalMXN = 0;

            // Suma todos los valores USD y MXN
            dolarInputs.forEach((input) => {
                totalUSD += parseFloat(input.value) || 0;
            });
            mxnOutputs.forEach((output) => {
                totalMXN += parseFloat(output.value) || 0;
            });

            // Actualiza los totales en la tabla
            document.getElementById("totalUSD").textContent = `$${totalUSD.toFixed(2)}`;
            document.getElementById("totalMXN").textContent = `$${totalMXN.toFixed(2)}`;
        }
    </script>
</body>

</html>