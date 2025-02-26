<?php
include 'dbcon.php';

if (isset($_POST['idCliente'])) {
    $idCliente = $_POST['idCliente'];

    $query = "SELECT * FROM clientes WHERE id = '$idCliente' AND estatus = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $registro = mysqli_fetch_assoc($result);
        $cliente = $registro['cliente'];
        $calle = $registro['calle'];
        $numexterior = $registro['numexterior'];
        $numinterior = $registro['numinterior'];
        $colonia = $registro['colonia'];
        $city = $registro['city'];
        $state = $registro['state'];
        $pais = $registro['pais'];
        $cpostal = $registro['cpostal'];
        $telefono = $registro['telefono'];
        $contacto = $registro['contacto'];
        $rfc = $registro['rfc'];
        $correo = $registro['correo'];

        echo "$cliente<br>";
        echo "$calle $numexterior $numinterior, $colonia, $city, $state, $pais, CP $cpostal<br>";
        echo "<div class='row justify-content-evenly'><div class='col-3'><b>Teléfono:</b> $telefono</div><div class='col-3'><b>Contacto:</b> $contacto</div><div class='col-2'><b>RFC:</b> $rfc</div><div class='col-4'><b>Email:</b> $correo</div></div>";
    } else {
        echo "No se encontraron datos.";
    }
} 
if (isset($_POST['idOrigen'])) {
    $idOrigen = $_POST['idOrigen'];

    $query = "SELECT * FROM proveedores WHERE id = '$idOrigen' AND estatus = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $registro = mysqli_fetch_assoc($result);
        $proveedor = $registro['proveedor'];
        $domicilio = $registro['domicilio'];
        $fraccionamiento = $registro['fraccionamiento'];
        $ciudad = $registro['ciudad'];
        $phone = $registro['phone'];
        $email = $registro['email'];
        $tax = $registro['tax'];
        $contact = $registro['contact'];

        echo "$proveedor<br>";
        echo "$domicilio, $fraccionamiento, $ciudad<br>";
        echo "<div class='row justify-content-evenly'><div class='col-12'><b>Contacto:</b> $contact</div><div class='col-12'><b>Teléfono:</b> $phone<br>$email</div><div class='col-12'><b>RFC:</b> $tax</div></div>";
    } else {
        echo "No se encontraron datos.";
    }
}

if (isset($_POST['idAduana'])) {
    $idAduana = $_POST['idAduana'];

    $query = "SELECT * FROM proveedores WHERE id = '$idAduana' AND estatus = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $registro = mysqli_fetch_assoc($result);
        $proveedor = $registro['proveedor'];
        $domicilio = $registro['domicilio'];
        $fraccionamiento = $registro['fraccionamiento'];
        $ciudad = $registro['ciudad'];
        $phone = $registro['phone'];
        $email = $registro['email'];
        $tax = $registro['tax'];
        $contact = $registro['contact'];

        echo "$proveedor<br>";
        echo "$domicilio, $fraccionamiento, $ciudad<br>";
        echo "<div class='row justify-content-evenly'><div class='col-12'><b>Contacto:</b> $contact</div><div class='col-12'><b>Teléfono:</b> $phone<br>$email</div><div class='col-12'><b>RFC:</b> $tax</div></div>";
    } else {
        echo "No se encontraron datos.";
    }
}

if (isset($_POST['idDestino'])) {
    $idDestino = $_POST['idDestino'];

    $query = "SELECT * FROM proveedores WHERE id = '$idDestino' AND estatus = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $registro = mysqli_fetch_assoc($result);
        $proveedor = $registro['proveedor'];
        $domicilio = $registro['domicilio'];
        $fraccionamiento = $registro['fraccionamiento'];
        $ciudad = $registro['ciudad'];
        $phone = $registro['phone'];
        $email = $registro['email'];
        $tax = $registro['tax'];
        $contact = $registro['contact'];

        echo "$proveedor<br>";
        echo "$domicilio, $fraccionamiento, $ciudad<br>";
        echo "<div class='row justify-content-evenly'><div class='col-12'><b>Contacto:</b> $contact</div><div class='col-12'><b>Teléfono:</b> $phone<br>$email</div><div class='col-12'><b>RFC:</b> $tax</div></div>";
    } else {
        echo "No se encontraron datos.";
    }
}
